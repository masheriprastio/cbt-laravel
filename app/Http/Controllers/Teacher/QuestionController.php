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

    

    // app/Http/Controllers/Teacher/QuestionController.php

public function select(Request $request)
{
    // daftar ujian milik guru login, bisa ditambah filter cari
    $tests = \App\Models\Test::where('created_by', auth()->id())
        ->latest()->paginate(12);

    return view('teacher.questions.select', compact('tests'));
}


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
