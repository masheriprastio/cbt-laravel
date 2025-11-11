<?php
// app/Http/Controllers/Teacher/QuestionController.php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Form tambah soal (MCQ/Esai)
    public function create(Test $test)
    {
        abort_unless($test->created_by === auth()->id(), 403);
        return view('teacher.questions.create', compact('test'));
    }

public function select(Request $request)
{
    $q          = trim((string) $request->input('q',''));
    $hideEmpty  = (int) $request->input('hide_empty', 0) === 1;

    $tests = Test::query()
        ->where('created_by', auth()->id())
        ->when($q !== '', function ($qr) use ($q) {
            $qr->where('title', 'like', '%'.$q.'%');
        })
        // hitung jumlah soal per tipe (pakai withCount agar selalu fresh)
        ->withCount([
            'questions as mcq_cnt'   => fn($x) => $x->where('type','mcq'),
            'questions as essay_cnt' => fn($x) => $x->where('type','essay'),
            'questions as q_total',
        ])
        // jika diminta, tampilkan hanya ujian yang masih punya soal
        ->when($hideEmpty, fn($qr) => $qr->having('q_total', '>', 0))
        ->orderByDesc('id')
        ->paginate(12)
        ->withQueryString();

    return view('teacher.questions.select', compact('tests','q','hideEmpty'));
}


public function edit(\App\Models\Test $test, \App\Models\Question $question)
{
    // pastikan question milik test yang sama
    abort_if($question->test_id !== $test->id, 404);

    // kalau pakai policy/otorisasi, panggil authorize di sini
    // $this->authorize('update', $question);

    // siapkan choices A–E untuk MCQ
    $letters = ['A','B','C','D','E'];
    $choices = $question->choices ?? ['', '', '', '', ''];

    return view('teacher.questions.edit', compact('test','question','letters','choices'));
}

public function update(\Illuminate\Http\Request $request, \App\Models\Test $test, \App\Models\Question $question)
{
    abort_if($question->test_id !== $test->id, 404);
    // $this->authorize('update', $question);

    // Jika ingin izinkan ubah tipe, set 'in:mcq,essay'.
    // Jika tidak, pakai 'in:'.$question->type agar tetap.
    $rules = [
        'type'       => ['required','in:mcq,essay'],
        'text'       => ['required','string'],
        'score'      => ['required','integer','min:1'],
        'sort_order' => ['nullable','integer','min:1'],
    ];

    if ($request->input('type') === 'mcq') {
        $rules = array_merge($rules, [
            'choices'    => ['required','array','size:5'],
            'choices.*'  => ['required','string'],
            'answer_key' => ['required','in:A,B,C,D,E'],
        ]);
    }

    $data = $request->validate($rules);

    // Simpan
    $originalType = $question->type;

    $payload = [
        'type'       => $data['type'],
        'text'       => $data['text'],
        'score'      => $data['score'],
        'sort_order' => $data['sort_order'] ?? null,
    ];

    if ($data['type'] === 'mcq') {
        $payload['choices']    = array_values($data['choices']);
        $payload['answer_key'] = $data['answer_key'];
    } else {
        // jika berubah dari MCQ → esai, kosongkan field MCQ
        $payload['choices']    = null;
        $payload['answer_key'] = null;
    }

    $question->update($payload);

    // (Opsional) sinkronkan counter di tabel tests jika kamu menyimpannya di kolom
    if (\Schema::hasColumn('tests', 'mcq_count') || \Schema::hasColumn('tests', 'essay_count')) {
        $mcq   = \App\Models\Question::where('test_id', $test->id)->where('type','mcq')->count();
        $essay = \App\Models\Question::where('test_id', $test->id)->where('type','essay')->count();
        $test->update([
            'mcq_count'   => \Schema::hasColumn('tests','mcq_count')   ? $mcq   : $test->mcq_count,
            'essay_count' => \Schema::hasColumn('tests','essay_count') ? $essay : $test->essay_count,
        ]);
    }

    return redirect()
        ->route('teacher.tests.show', $test)
        ->with('success','Soal berhasil diperbarui.');
}


    // app/Http/Controllers/Teacher/QuestionController.php

    // Simpan soal baru
    public function store(\Illuminate\Http\Request $request, \App\Models\Test $test)
{
    $baseRules = [
        'type'  => ['required','in:mcq,essay'],
        'text'  => ['required','string'],
        'score' => ['required','integer','min:1'],
        'sort_order' => ['nullable','integer','min:1'],
    ];

    $mcqRules = [
        'choices'    => ['required','array','size:5'],           // A..E
        'choices.*'  => ['required','string'],
        'answer_key' => ['required','in:A,B,C,D,E'],
    ];

    $validated = $request->validate(
        $request->input('type') === 'mcq'
            ? array_merge($baseRules, $mcqRules)
            : $baseRules
    );

    $payload = [
        'test_id'    => $test->id,
        'type'       => $validated['type'],
        'text'       => $validated['text'],
        'score'      => $validated['score'],
        'sort_order' => $validated['sort_order'] ?? null,
        'created_by' => auth()->id(),
    ];

    if ($validated['type'] === 'mcq') {
        $payload['choices']    = array_values($validated['choices']); // [A,B,C,D,E]
        $payload['answer_key'] = $validated['answer_key'];            // "A".."E"
    }

    \App\Models\Question::create($payload);

    return redirect()
        ->route('teacher.tests.show', $test)
        ->with('success', 'Soal berhasil ditambahkan.');
}




    // Hapus soal
    public function destroy(Question $question)
    {
        abort_unless($question->created_by === auth()->id(), 403);
        $test = $question->test;
        $question->delete();

        return redirect()
            ->route('teacher.tests.show', $test)
            ->with('success','Soal dihapus.');
    }
}
