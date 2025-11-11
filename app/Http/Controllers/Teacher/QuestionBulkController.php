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
        'type'          => ['required','in:mcq,essay'],
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
        // Validasi array pertanyaan
        $rules = [
            'type'               => ['required', Rule::in(['mcq','essay'])],
            'questions'          => ['required','array','min:1'],
            'questions.*.text'   => ['required','string'],
            'questions.*.score'  => ['required','integer','min:1'],
            'questions.*.sort'   => ['nullable','integer','min:1'],
        ];

        // MCQ extra rules
        if ($request->input('type') === 'mcq') {
            $rules = array_merge($rules, [
                'questions.*.choices'    => ['required','array','size:5'],
                'questions.*.choices.*'  => ['required','string'],
                'questions.*.answer_key' => ['required', Rule::in(['A','B','C','D','E'])],
            ]);
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $test) {
            foreach ($validated['questions'] as $q) {
                $payload = [
                    'test_id'    => $test->id,
                    'type'       => $validated['type'],
                    'text'       => $q['text'],
                    'score'      => $q['score'],
                    'sort_order' => $q['sort'] ?? null,
                    'created_by' => auth()->id(),
                ];
                if ($validated['type'] === 'mcq') {
                    $payload['choices']    = array_values($q['choices']);
                    $payload['answer_key'] = $q['answer_key'];
                }
                Question::create($payload);
            }

            // opsional: update counter ringkas di tabel tests
            if ($validated['type'] === 'mcq') {
                $test->increment('mcq_count', count($validated['questions']));
            } else {
                $test->increment('essay_count', count($validated['questions']));
            }
        });

        return redirect()
            ->route('teacher.tests.show', $test)
            ->with('success', 'Soal massal berhasil disimpan.');
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
