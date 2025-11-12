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

    $rules = [
        'type'       => ['required','in:mcq,essay'],
        'text'       => ['required','string'],
        'score'      => ['required','integer','min:1'],
        'sort_order' => ['nullable','integer','min:1'],
    ];
    if ($request->type==='mcq') {
        $rules += [
            'choices'    => ['required','array','size:5'],
            'choices.*'  => ['required','string'],
            'answer_key' => ['required','in:A,B,C,D,E'],
        ];
    }
    $d = $request->validate($rules);

    $payload = [
        'type'       => $d['type'],
        'text'       => $d['text'],
        'score'      => $d['score'],
        'sort_order' => $d['sort_order'] ?? null,
    ];

    if ($d['type']==='mcq') {
        $payload['choices']    = array_values($d['choices']);
        $payload['answer_key'] = $d['answer_key'];
    } else {
        $payload['choices'] = null;
        $payload['answer_key'] = null;
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($question, $payload) {
        $question->update($payload);
        if ($question->type === 'mcq') {
            $letters = range('A', 'E');
            $options = [];
            foreach ($payload['choices'] as $idx => $choice) {
                $options[] = [
                    'letter'      => $letters[$idx],
                    'text'        => $choice,
                    'is_correct'  => $payload['answer_key'] === $letters[$idx],
                ];
            }
            $question->options()->delete();
            $question->options()->createMany($options);
        }
    });

    return redirect()->route('teacher.tests.show', $test)->with('success', 'Soal diperbarui.');
}


    // app/Http/Controllers/Teacher/QuestionController.php

    // Simpan soal baru
public function store(\Illuminate\Http\Request $request, \App\Models\Test $test)
{
    $base = [
        'type'       => ['required','in:mcq,essay'],
        'text'       => ['required','string'],
        'score'      => ['required','integer','min:1'],
        'sort_order' => ['nullable','integer','min:1'],
    ];
    $mcq = [
        'choices'    => ['required','array','size:5'],
        'choices.*'  => ['required','string'],
        'answer_key' => ['required','in:A,B,C,D,E'],
    ];
    $v = $request->validate($request->type==='mcq' ? ($base+$mcq) : $base);

    $payload = [
        'test_id'    => $test->id,
        'type'       => $v['type'],
        'text'       => $v['text'],
        'score'      => $v['score'],
        'sort_order' => $v['sort_order'] ?? null,
        'created_by' => auth()->id(),
    ];
    if ($v['type']==='mcq') {
        $payload['choices']    = array_values($v['choices']);
        $payload['answer_key'] = $v['answer_key'];
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($payload) {
        $question = \App\Models\Question::create($payload);
        if ($question->type === 'mcq') {
            $letters = range('A', 'E');
            $options = [];
            foreach ($payload['choices'] as $idx => $choice) {
                $options[] = new \App\Models\Option([
                    'letter'      => $letters[$idx],
                    'text'        => $choice,
                    'is_correct'  => $payload['answer_key'] === $letters[$idx],
                ]);
            }
            $question->options()->saveMany($options);
        }
    });

    return redirect()->route('teacher.tests.show', $test)->with('success', 'Soal ditambahkan.');
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
