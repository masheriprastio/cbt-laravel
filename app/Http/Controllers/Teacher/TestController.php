<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\User;

class TestController extends Controller
{
    /** Dashboard ringkas berisi ujian milik guru aktif */
    public function dashboard()
    {
        $tests = Test::where('created_by', auth()->id())->latest()->get();
        return view('teacher.dashboard', compact('tests'));
    }

    /** Daftar ujian (paginate) */
    public function index()
    {
        $tests = Test::where('created_by', auth()->id())
            ->latest()
            ->paginate(12);

        return view('teacher.tests.index', compact('tests'));
    }

    /** Form buat ujian */
    public function create()
    {
        return view('teacher.tests.create');
    }

    /** Simpan ujian baru */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject'           => 'required|string|max:100',
            'description'       => 'nullable|string',
            'duration_minutes'  => 'required|integer|min:5|max:300',
            'starts_at'         => 'required|date',
            'ends_at'           => 'required|date|after:starts_at',
            'mcq_count'         => 'required|integer|min:0|max:500',
            'essay_count'       => 'required|integer|min:0|max:500',
            'shuffle_questions' => 'nullable|boolean',
        ]);

        $data['created_by'] = auth()->id();
        $data['shuffle_questions'] = (bool)($data['shuffle_questions'] ?? false);

        // Generate a title automatically based on subject (since title field removed from form)
        if (empty($data['title'])) {
            $data['title'] = trim($data['subject']) ?: ('Ujian ' . now()->format('Y-m-d H:i'));
        }

        $test = Test::create($data);

        // Jika guru memasukkan jumlah soal (MCQ/Essay) > 0, lanjutkan ke bulk build form
        $totalQuestions = intval($data['mcq_count'] ?? 0) + intval($data['essay_count'] ?? 0);
        if ($totalQuestions > 0) {
            // Prefer memulai dengan MCQ jika ada, else essay. Kirimkan parameter via query string.
            $mcq = intval($data['mcq_count'] ?? 0);
            $essay = intval($data['essay_count'] ?? 0);
            if ($mcq > 0) {
                return redirect()
                    ->route('teacher.questions.bulk.build', ['test' => $test->id, 'type' => 'mcq', 'count' => $mcq])
                    ->with('success', 'Ujian dibuat. Lanjutkan input massal soal MCQ.');
            }
            return redirect()
                ->route('teacher.questions.bulk.build', ['test' => $test->id, 'type' => 'essay', 'count' => $essay])
                ->with('success', 'Ujian dibuat. Lanjutkan input massal soal Esai.');
        }

        // default: tunjukkan halaman ujian
        return redirect()
            ->route('teacher.tests.show', $test)
            ->with('success', 'Ujian dibuat. Anda dapat menambahkan soal nanti.');
    }

    /** Detail ujian + daftar soal */
public function show(Test $test)
    {
        // Muat pertanyaan sekali, urut rapi
        $test->load(['questions' => function ($q) {
            $q->orderByRaw('COALESCE(sort_order, 999999), id');
        }]);

        $questions = $test->questions;

        $counts = [
            'mcq'   => $questions->where('type', 'mcq')->count(),
            'essay' => $questions->where('type', 'essay')->count(),
            'total' => $questions->count(),
        ];

        return view('teacher.tests.show', compact('test', 'questions', 'counts'));
    }


    /** Form edit ujian */
    public function edit(Test $test)
    {
        $this->authorizeOwner($test);
        return view('teacher.tests.edit', compact('test'));
    }

    /** Update ujian */
    public function update(Request $request, Test $test)
    {
        $this->authorizeOwner($test);

        $data = $request->validate([
            'subject'           => 'required|string|max:100',
            'description'       => 'nullable|string',
            'duration_minutes'  => 'required|integer|min:5|max:300',
            'starts_at'         => 'required|date',
            'ends_at'           => 'required|date|after:starts_at',
            'mcq_count'         => 'required|integer|min:0|max:500',
            'essay_count'       => 'required|integer|min:0|max:500',
            'shuffle_questions' => 'nullable|boolean',
        ]);

        $data['shuffle_questions'] = (bool)($data['shuffle_questions'] ?? false);
        // ensure title remains valid (keep it in sync with subject if not present)
        if (empty($data['title'])) {
            $data['title'] = trim($data['subject']) ?: $test->title;
        }

        $test->update($data);

        return back()->with('success', 'Ujian diperbarui.');
    }

    /** Hapus ujian */
public function destroy(\App\Models\Test $test)
{
    // (opsional) batasi kepemilikan
    // abort_if($test->created_by !== auth()->id(), 403);

    \DB::transaction(function () use ($test) {
        // ambil semua id pertanyaan milik ujian ini
        $qIds = \DB::table('questions')->where('test_id', $test->id)->pluck('id');

        if ($qIds->isNotEmpty()) {
            // hapus tabel anak jika FK belum cascade
            if (\Schema::hasTable('answers') && \Schema::hasColumn('answers','question_id')) {
                \DB::table('answers')->whereIn('question_id', $qIds)->delete();
            }
            // hapus pertanyaan (permanen)
            \DB::table('questions')->whereIn('id', $qIds)->delete();
        }

        // terakhir: hapus ujian (permanen)
        \DB::table('tests')->where('id', $test->id)->delete();
    });

    return back()->with('success', 'Ujian telah dihapus permanen.');
}


    /** (Opsional) Tugaskan ujian ke siswa */
    public function assign(Request $request, Test $test)
    {
        $this->authorizeOwner($test);

        $ids = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ])['user_ids'];

        $studentIds = User::whereIn('id', $ids)->where('role', 'siswa')->pluck('id');

        // Pastikan di Model Test ada relasi belongsToMany('users') ->table('test_user')
        $test->assignees()->syncWithoutDetaching(
            collect($studentIds)->mapWithKeys(fn ($id) => [$id => ['status' => 'assigned']])->all()
        );

        return back()->with('success', 'Ujian ditugaskan ke siswa.');
    }

    /** Guard kepemilikan ujian oleh guru yang login */
    private function authorizeOwner(Test $test): void
    {
        abort_unless($test->created_by === auth()->id(), 403, 'Akses ditolak.');
    }
}
