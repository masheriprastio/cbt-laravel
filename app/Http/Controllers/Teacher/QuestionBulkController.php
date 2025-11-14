<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class QuestionBulkController extends Controller
{
    // Langkah 1: pilih jenis & jumlah
    public function setup(Test $test)
    {
        return view('teacher.questions.bulk.setup', compact('test'));
    }

    // Langkah 2: render N form
   public function build(Request $request, Test $test)
{
    $data = $request->validate([
        'type'          => ['required','in:mcq,essay,tf'],
        'count'         => ['required','integer','min:1','max:100'],
        'start_number'  => ['nullable','integer','min:1','max:10000'],
        'default_score' => ['nullable','integer','min:1','max:1000'],
    ]);

    $type  = $data['type'];
    $count = (int)$data['count'];
    $start = (int)($data['start_number'] ?? 1);
    $score = (int)($data['default_score'] ?? 10);
    $indexes = range(0, $count - 1);

    return view('teacher.questions.bulk.build', compact('test','type','count','start','score','indexes'));
}

    // Simpan massal
    public function store(Request $request, Test $test)
    {
        // Validasi array pertanyaan (per-question types supported)
        $rules = [
            'type'               => ['nullable', Rule::in(['mcq','essay','tf'])],
            'questions'          => ['required','array','min:1'],
            'questions.*.type'   => ['nullable', Rule::in(['mcq','essay','tf'])],
            'questions.*.text'   => ['required','string'],
            'questions.*.score'  => ['required','integer','min:1'],
            'questions.*.sort'   => ['nullable','integer','min:1'],
        ];

        $validated = $request->validate($rules);

        // If a root 'type' is provided and per-question type is missing, apply root type
        if ($request->filled('type')) {
            foreach ($request->input('questions', []) as $k => $q) {
                if (empty($q['type'])) {
                    $request->merge(["questions.$k.type" => $request->input('type')]);
                }
            }
        }

        // Re-fetch validated data now that we may have injected per-question types
        $validated = $request->validate($rules);

        // capture intended totals before we sync counts (so we can decide redirect)
        $intendedMcqTotal = intval($test->mcq_count ?? 0);
        $intendedEssayTotal = intval($test->essay_count ?? 0);

        DB::transaction(function () use ($validated, $test) {
            foreach ($validated['questions'] as $q) {
                $qtype = $q['type'] ?? $validated['type'] ?? 'essay';
                $payload = [
                    'test_id'    => $test->id,
                    'type'       => $qtype,
                    'text'       => $q['text'],
                    'score'      => $q['score'],
                    'sort_order' => $q['sort'] ?? null,
                    'created_by' => auth()->id(),
                ];

                if ($qtype === 'mcq') {
                    $payload['choices']    = array_values($q['choices'] ?? []);
                    $payload['answer_key'] = $q['answer_key'] ?? null;
                } elseif ($qtype === 'tf') {
                    $payload['answer_key'] = $q['answer_key'] ?? null;
                }

                \App\Models\Question::create($payload);
            }

            // Sinkronkan counter di tabel tests dengan jumlah pertanyaan aktual (hindari double-increment)
            $mcqCount = DB::table('questions')->where('test_id', $test->id)->where('type', 'mcq')->count();
            $essayCount = DB::table('questions')->where('test_id', $test->id)->where('type', 'essay')->count();
            DB::table('tests')->where('id', $test->id)->update([
                'mcq_count' => $mcqCount,
                'essay_count' => $essayCount,
            ]);
        });

        // Recompute current counts after transaction
        $currentMcq = DB::table('questions')->where('test_id', $test->id)->where('type', 'mcq')->count();
        $currentEssay = DB::table('questions')->where('test_id', $test->id)->where('type', 'essay')->count();

        // If we just saved MCQ and there remain essay questions to input (based on intended total), redirect to essay build
        if ($validated['type'] === 'mcq') {
            $remainingEssay = max(0, $intendedEssayTotal - $currentEssay);
            if ($remainingEssay > 0) {
                return redirect()->route('teacher.questions.bulk.build', ['test' => $test->id, 'type' => 'essay', 'count' => $remainingEssay])
                    ->with('success', 'Soal MCQ disimpan. Lanjutkan input massal soal Esai.');
            }
        }

        // Similarly, if we just saved essay and there remain mcq (rare), redirect
        if ($validated['type'] === 'essay') {
            $remainingMcq = max(0, $intendedMcqTotal - $currentMcq);
            if ($remainingMcq > 0) {
                return redirect()->route('teacher.questions.bulk.build', ['test' => $test->id, 'type' => 'mcq', 'count' => $remainingMcq])
                    ->with('success', 'Soal Esai disimpan. Lanjutkan input massal soal MCQ.');
            }
        }

        return redirect()->route('teacher.tests.show', $test)->with('success', 'Soal massal berhasil disimpan.');
            
    }

public function destroy(Test $test)
    {
        // (opsional) pembatasan pemilik
        // abort_if($test->created_by !== auth()->id(), 403);

        DB::transaction(function () use ($test) {
            // Ambil id pertanyaan ujian ini (pakai SQL langsung agar netral thd SoftDeletes)
            $qIds = DB::table('questions')->where('test_id', $test->id)->pluck('id');

            if ($qIds->isNotEmpty()) {
                // 1) Hapus tabel anak terlebih dulu bila FK belum cascade
                if (Schema::hasTable('answers') && Schema::hasColumn('answers','question_id')) {
                    DB::table('answers')->whereIn('question_id', $qIds)->delete();
                }
                // contoh lain jika ada:
                // if (Schema::hasTable('question_options')) {
                //     DB::table('question_options')->whereIn('question_id', $qIds)->delete();
                // }

                // 2) Hapus permanen pertanyaan (SQL DELETE, bukan Eloquent)
                DB::table('questions')->whereIn('id', $qIds)->delete();
            }

            // 3) Sinkronkan counter bila kolom ada
            $payload = [];
            if (Schema::hasColumn('tests','mcq_count')) {
                $payload['mcq_count'] = DB::table('questions')->where('test_id',$test->id)->where('type','mcq')->count();
            }
            if (Schema::hasColumn('tests','essay_count')) {
                $payload['essay_count'] = DB::table('questions')->where('test_id',$test->id)->where('type','essay')->count();
            }
            if ($payload) {
                DB::table('tests')->where('id',$test->id)->update($payload);
            }
        });

        return back()->with('success','Semua soal pada ujian telah DIHAPUS PERMANEN.');
    }
}
