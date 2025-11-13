<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\Schema;

class TemporaryExamController extends Controller
{
    /**
     * Show a temporary unauthenticated demo exam page for testing student mode.
     */
    public function demo()
    {
        $testId = request()->query('test');

        if ($testId) {
            $test = Test::with(['questions.options'])->find($testId);
            if (!$test) {
                abort(404, 'Test not found');
            }

            // normalize question payload — ensure questions are in deterministic order (by id)
            // and assign a sequential number (1..N) for display regardless of question DB id.
            $questions = $test->questions->sortBy('id')->values()->map(function($q, $i){
                return [
                    'id' => $q->id,
                    'number' => $i + 1,
                    'text' => $q->text,
                    'score' => $q->score ?? 1,
                    // ensure options are ordered by label (A/B/C...) for consistent display
                    'options' => $q->options->sortBy('label')->map(function($o){
                        return ['id'=>$o->id,'label'=>$o->label,'text'=>$o->text];
                    })->values()->toArray(),
                ];
            })->toArray();

            return view('exam.demo', ['questions' => $questions, 'test' => $test]);
        }

        // fallback example single question payload for quick testing
        $question = [
            'id' => 1,
            'text' => 'Contoh soal: Berapakah 2 + 2?',
            'choices' => [
                'A' => '3',
                'B' => '4',
                'C' => '5',
                'D' => '6',
            ],
        ];

        return view('exam.demo', compact('question'));
    }

    public function submit(Request $request)
    {
        $testId = $request->input('test_id');
        if (!$testId) {
            return back()->with('error', 'No test id provided');
        }

        $test = Test::with('questions.options')->find($testId);
        if (!$test) {
            return back()->with('error', 'Test not found');
        }

        $answers = $request->input('answers', []); // answers[question_id] => option_id

        $totalPossible = 0;
        $totalScore = 0;
        $perQuestion = [];

        $hasIsCorrect = Schema::hasColumn('options', 'is_correct');

    // iterate questions in deterministic order (by id) so scoring matches displayed order
    $questions = $test->questions->sortBy('id')->values();
    foreach ($questions as $q) {
            $qScore = $q->score ?? 1;
            $totalPossible += $qScore;
            $given = isset($answers[$q->id]) ? intval($answers[$q->id]) : null;

            // determine correct option: prefer options.is_correct column; fallback to question.answer_key if present
            $correctOption = null;
            if ($hasIsCorrect) {
                $correctOption = $q->options->firstWhere('is_correct', 1);
            }
            if (!$correctOption && isset($q->answer_key) && $q->answer_key) {
                $ak = $q->answer_key;
                // try by id
                $byId = $q->options->firstWhere('id', intval($ak));
                if ($byId) {
                    $correctOption = $byId;
                } else {
                    // try by label (A/B/C) or by exact text
                    $byLabel = $q->options->firstWhere('label', $ak);
                    if ($byLabel) {
                        $correctOption = $byLabel;
                    } else {
                        $byText = $q->options->firstWhere('text', $ak);
                        if ($byText) {
                            $correctOption = $byText;
                        }
                    }
                }
            }

            $isCorrect = false;
            if ($correctOption && $given && $given === (int)$correctOption->id) {
                $isCorrect = true;
                $totalScore += $qScore;
            }

            $perQuestion[$q->id] = [
                'given' => $given,
                'correct' => $correctOption ? $correctOption->id : null,
                'is_correct' => $isCorrect,
                'score' => $qScore,
            ];
        }

        $percent = $totalPossible > 0 ? round(($totalScore / $totalPossible) * 100, 2) : 0;

        // If a session_id was provided, update the exam session record
        $sessionId = $request->input('session_id');
        if ($sessionId) {
            try {
                $es = \App\Models\ExamSession::find($sessionId);
                if ($es) {
                    $es->violations = intval($request->input('violations', $es->violations));
                    $es->finished_at = now();
                    $es->status = 'submitted';
                    $es->save();
                }
            } catch (\Throwable $e) {
                // don't block result view on DB errors
            }
        }

        return view('exam.demo-result', compact('test','totalScore','totalPossible','percent','perQuestion'));
    }

    /**
     * Create a small sample test + questions/options for quick demo and redirect to demo view.
     */
    public function createSample()
    {
        // create a simple Test
        // create test with only commonly available columns to avoid migration mismatch
        $test = Test::create([
            'title' => 'Demo Test (auto-generated)',
            'description' => 'Contoh ujian untuk demo scoring',
            'duration_minutes' => 30,
            'created_by' => auth()->id() ?? 0,
        ]);

        // sample questions
        $hasIsCorrect = Schema::hasColumn('options', 'is_correct');
        $hasAnswerKey = Schema::hasColumn('questions', 'answer_key');

        $q1 = Question::create([
            'test_id' => $test->id,
            'text' => 'Berapa 2 + 2?',
            'score' => 1,
            'created_by' => auth()->id() ?? 1,
        ]);

        $opts = [
            ['label'=>'A','text'=>'3','is_correct'=>0],
            ['label'=>'B','text'=>'4','is_correct'=>1],
            ['label'=>'C','text'=>'5','is_correct'=>0],
            ['label'=>'D','text'=>'6','is_correct'=>0],
        ];
        $createdOpts = [];
        foreach($opts as $o) {
            $data = ['question_id'=>$q1->id, 'label'=>$o['label'], 'text'=>$o['text']];
            if ($hasIsCorrect) { $data['is_correct'] = $o['is_correct']; }
            $createdOpts[] = Option::create($data);
        }
        // if options table doesn't have is_correct, store correct answer in question.answer_key if possible
        if (!$hasIsCorrect && $hasAnswerKey) {
            $correct = collect($createdOpts)->firstWhere('label', 'B');
            if ($correct) {
                $q1->answer_key = $correct->id;
                $q1->save();
            }
        }

        $q2 = Question::create([
            'test_id' => $test->id,
            'text' => 'Ibu kota Indonesia adalah?',
            'score' => 1,
            'created_by' => auth()->id() ?? 1,
        ]);
        $opts = [
            ['label'=>'A','text'=>'Jakarta','is_correct'=>1],
            ['label'=>'B','text'=>'Bandung','is_correct'=>0],
            ['label'=>'C','text'=>'Surabaya','is_correct'=>0],
            ['label'=>'D','text'=>'Medan','is_correct'=>0],
        ];
        $createdOpts = [];
        foreach($opts as $o) {
            $data = ['question_id'=>$q2->id, 'label'=>$o['label'], 'text'=>$o['text']];
            if ($hasIsCorrect) { $data['is_correct'] = $o['is_correct']; }
            $createdOpts[] = Option::create($data);
        }
        if (!$hasIsCorrect && $hasAnswerKey) {
            $correct = collect($createdOpts)->firstWhere('label', 'A');
            if ($correct) {
                $q2->answer_key = $correct->id;
                $q2->save();
            }
        }

        $q3 = Question::create([
            'test_id' => $test->id,
            'text' => 'Pilihan yang merupakan bilangan prima:',
            'score' => 1,
            'created_by' => auth()->id() ?? 1,
        ]);
        $opts = [
            ['label'=>'A','text'=>'4','is_correct'=>0],
            ['label'=>'B','text'=>'6','is_correct'=>0],
            ['label'=>'C','text'=>'7','is_correct'=>1],
            ['label'=>'D','text'=>'8','is_correct'=>0],
        ];
        $createdOpts = [];
        foreach($opts as $o) {
            $data = ['question_id'=>$q3->id, 'label'=>$o['label'], 'text'=>$o['text']];
            if ($hasIsCorrect) { $data['is_correct'] = $o['is_correct']; }
            $createdOpts[] = Option::create($data);
        }
        if (!$hasIsCorrect && $hasAnswerKey) {
            $correct = collect($createdOpts)->firstWhere('label', 'C');
            if ($correct) {
                $q3->answer_key = $correct->id;
                $q3->save();
            }
        }

        return redirect()->route('exam.demo', ['test' => $test->id]);
    }
}
