<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index()
    {
        $users = User::orderBy('id','asc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'nullable|email|unique:users,email',
            'role' => 'required|in:guru,siswa,admin',
            'username' => 'nullable|string|max:100|unique:users,username',
        ]);

        // For siswa: auto-generate username & password if email not provided
        // handle username & password generation
        if ($data['role'] === 'siswa') {
            // username may be provided; otherwise generate
            if (empty($data['username'])) {
                $username = Str::slug(substr($data['name'],0,30)) . rand(100,999);
                while (User::where('username',$username)->exists()) {
                    $username = Str::slug(substr($data['name'],0,30)) . rand(1000,9999);
                }
                $data['username'] = $username;
            }
            $passwordPlain = Str::random(8);
            $passwordHash = bcrypt($passwordPlain);
        } else {
            // For guru/admin require password input
            $request->validate([
                'password' => 'required|min:6'
            ]);
            $passwordPlain = $request->input('password');
            $passwordHash = bcrypt($passwordPlain);
        }

        $email = $data['email'];

        // Generate a placeholder email if it's empty to satisfy the NOT NULL constraint.
        if (empty($email)) {
            if ($data['role'] === 'siswa') {
                $email = $data['username'] . '@example.com';
            } else {
                // Create a unique placeholder for other roles (guru, admin)
                $baseEmail = strtolower(Str::slug($data['name'])) . '@example.com';
                $email = $baseEmail;
                $i = 1;
                while (User::where('email', $email)->exists()) {
                    $email = strtolower(Str::slug($data['name'])) . '_' . $i++ . '@example.com';
                }
            }
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $email,
            'password' => $passwordHash,
            'role' => $data['role'],
            'username' => $data['username'] ?? null,
            'class' => $data['class'] ?? null,
        ]);

        if ($data['role'] === 'siswa') {
            return redirect()->route('admin.users.index')->with('success', "Siswa dibuat. Username: {$data['username']}, Password: {$passwordPlain}");
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'nullable|string|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:guru,siswa,admin',
            'username' => 'nullable|string|max:100|unique:users,username,'.$user->id,
        ]);

        $email = $data['email'];

        // Generate a placeholder email if it's empty to satisfy the NOT NULL constraint.
        if (empty($email)) {
            if ($data['role'] === 'siswa') {
                $usernameForEmail = $data['username'] ?? $user->username;
                $email = $usernameForEmail . '@example.com';
            } else {
                // Create a unique placeholder for other roles (guru, admin)
                $baseEmail = strtolower(Str::slug($data['name'])) . '@example.com';
                $email = $baseEmail;
                $i = 1;
                while (User::where('email', $email)->where('id', '!=', $user->id)->exists()) {
                    $email = strtolower(Str::slug($data['name'])) . '_' . $i++ . '@example.com';
                }
            }
        }

        $user->update([
            'name' => $data['name'],
            'email' => $email,
            'role' => $data['role'],
            'username' => $data['username'] ?? $user->username,
            'class' => $data['class'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success','User diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User dihapus.');
    }

    public function resetPassword(User $user)
    {
        $new = Str::random(8);
        $user->password = bcrypt($new);
        $user->save();
        return redirect()->route('admin.users.index')->with('success',"Password direset. Username: {$user->username}, Password baru: {$new}");
    }

    public function printForm(User $user)
    {
        return view('admin.users.print', compact('user'));
    }

    public function printConfirm(Request $request, User $user)
    {
        $data = $request->validate([
            'password' => 'nullable|string|min:6'
        ]);
        if (!empty($data['password'])) {
            $plain = $data['password'];
            $user->password = bcrypt($plain);
            $user->save();
        } else {
            $plain = null;
        }
        return view('admin.users.print', ['user' => $user, 'passwordPlain' => $plain ?? '']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $newlyCreatedUsers = [];
        $errors = [];
        $passwordPlain = '123456'; // Standard password for all imported students

        try {
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $header = null;
                $lineNum = 0;
                $nameIndex = -1;
                $classIndex = -1;

                // Find the last WK username to determine the starting number
                $lastWkUser = User::where('username', 'like', 'WK%')->orderByRaw('CAST(SUBSTRING(username, 3) AS UNSIGNED) DESC')->first();
                $lastWkNumber = $lastWkUser ? (int)substr($lastWkUser->username, 2) : 0;
                $nextWkNumber = $lastWkNumber + 1;

                while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                    $lineNum++;

                    if ($header === null) {
                        $header = array_map('strtolower', array_map('trim', $row));
                        $nameIndex = array_search('nama siswa', $header);
                        if ($nameIndex === false) {
                            $nameIndex = array_search('nama', $header);
                        }
                        if ($nameIndex === false) {
                            throw new \Exception("Format template tidak sesuai. Pastikan ada kolom 'Nama Siswa' di file Anda.");
                        }
                        $classIndex = array_search('kelas', $header);
                        continue;
                    }

                    if (empty(array_filter($row))) continue;

                    $name = trim($row[$nameIndex] ?? '');

                    if (empty($name)) {
                        $errors[] = "Baris $lineNum: Nama siswa tidak boleh kosong.";
                        continue;
                    }

                    $username = 'WK' . str_pad($nextWkNumber, 3, '0', STR_PAD_LEFT);
                    $nextWkNumber++;

                    $class = ($classIndex !== false && isset($row[$classIndex])) ? trim($row[$classIndex]) : null;

                    try {
                        $user = User::create([
                            'name' => $name,
                            'email' => $username . '@example.com',
                            'username' => $username,
                            'password' => bcrypt($passwordPlain),
                            'role' => 'siswa',
                            'class' => $class,
                        ]);

                        $newlyCreatedUsers[] = [
                            'name' => $name,
                            'username' => $username,
                            'password' => $passwordPlain,
                        ];

                    } catch (\Exception $e) {
                        $errors[] = "Baris $lineNum: Gagal membuat pengguna '{$name}'. Error: " . $e->getMessage();
                    }
                }
                fclose($handle);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal memproses file: ' . $e->getMessage()]);
        }

        // Store results in session to show on the results page
        session()->flash('import_results', [
            'newlyCreatedUsers' => $newlyCreatedUsers,
            'errors' => $errors,
        ]);

        return redirect()->route('admin.users.index')->with('show_import_results', true);
    }

    public function importTemplate()
    {
        $filename = "template_import_siswa.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $content = "No;Nama Siswa;Kelas\n1;Nama Siswa Contoh 1;X-A\n2;Nama Siswa Contoh 2;X-B";

        return Response::make($content, 200, $headers);
    }

    public function printAll(Request $request)
    {
        $role = $request->input('role');
        $fromDate = $request->input('from_date');

        $query = User::orderBy('created_at', 'desc');

        if (!empty($role)) {
            $query->where('role', $role);
        }

        if ($role === 'siswa') {
            $query->whereHas('rooms');
        }

        if (!empty($fromDate)) {
            $query->where('created_at', '>=', $fromDate);
        }

        $users = $query->get();
        $passwords = [];

        if ($role === 'siswa') {
            $generatedNumbers = [];
            foreach ($users as $user) {
                if ($user->role === 'siswa') {
                    do {
                        $randomNumber = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                    } while (in_array($randomNumber, $generatedNumbers));

                    $generatedNumbers[] = $randomNumber;
                    $newPasswordPlain = 'WK2025' . $randomNumber;
                    $user->password = bcrypt($newPasswordPlain);
                    $user->save();
                    $passwords[$user->id] = $newPasswordPlain;
                }
            }
        }

        return view('admin.users.print-all', compact('users', 'passwords'));
    }
}
