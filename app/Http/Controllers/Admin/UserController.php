<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index()
    {
        $users = User::orderBy('created_at','desc')->paginate(30);
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

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'password' => $passwordHash,
            'role' => $data['role'],
            'username' => $data['username'] ?? null,
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
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'role' => 'required|in:guru,siswa,admin',
            'username' => 'nullable|string|max:100|unique:users,username,'.$user->id,
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'role' => $data['role'],
            'username' => $data['username'] ?? $user->username,
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
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
            'role' => 'required|in:siswa,guru,admin'
        ]);

        $file = $request->file('file');
        $role = $request->input('role');
        $imported = 0;
        $errors = [];

        try {
            // Read CSV file
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $header = null;
                $lineNum = 0;
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $lineNum++;
                    
                    // Use first row as header
                    if ($header === null) {
                        $header = array_map('strtolower', array_map('trim', $row));
                        continue;
                    }

                    if (count($row) < 1 || empty($row[0])) continue;

                    $data = array_combine($header, array_map('trim', $row));
                    $name = $data['nama'] ?? $data['name'] ?? '';
                    $email = $data['email'] ?? '';
                    $username = $data['username'] ?? '';

                    if (empty($name)) {
                        $errors[] = "Baris $lineNum: Nama tidak boleh kosong";
                        continue;
                    }

                    // Generate username if not provided
                    if (empty($username)) {
                        $username = Str::slug(substr($name, 0, 30)) . rand(100, 999);
                        $counter = 0;
                        while (User::where('username', $username)->exists() && $counter < 10) {
                            $username = Str::slug(substr($name, 0, 30)) . rand(1000, 9999);
                            $counter++;
                        }
                    }

                    // Check if user exists
                    if (User::where('username', $username)->exists()) {
                        $errors[] = "Baris $lineNum: Username '$username' sudah ada";
                        continue;
                    }

                    // Generate password for students
                    $password = Str::random(8);
                    
                    try {
                        User::create([
                            'name' => $name,
                            'email' => !empty($email) ? $email : null,
                            'username' => $username,
                            'password' => bcrypt($password),
                            'role' => $role
                        ]);
                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Baris $lineNum: {$e->getMessage()}";
                    }
                }
                fclose($handle);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca file: ' . $e->getMessage()]);
        }

        $message = "Berhasil import $imported pengguna";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode('; ', array_slice($errors, 0, 5));
        }

        return back()->with('import_message', $message);
    }

    public function printAll(Request $request)
    {
        $role = $request->input('role');
        $fromDate = $request->input('from_date');

        $query = User::orderBy('created_at', 'desc');

        if (!empty($role)) {
            $query->where('role', $role);
        }

        if (!empty($fromDate)) {
            $query->where('created_at', '>=', $fromDate);
        }

        $users = $query->get();

        return view('admin.users.print-all', compact('users'));
    }
}
