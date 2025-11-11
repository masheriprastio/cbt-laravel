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

    // Simpan soal baru
    public function store(Request $request, Test $test)
    {
        abort_unless($test->created_by === auth()->id(), 403);

        $data = $request->validate([
            'type'       => 'required|in:mcq,essay',
            'text'       => 'required|string',
            'score'      => 'required|integer|min:1|max:100',
            'order'      => 'nullable|integer|min:1',
            'answer_key' => 'nullable|in:A,B,C,D,E',
            'options'    => 'array', // opsional: ['A'=>..., 'B'=>..., ...]
        ]);

        $q = Question::create([
            'test_id'    => $test->id,
            'type'       => $data['type'],
            'text'       => $data['text'],
            'score'      => $data['score'],
            'order'      => $data['order'] ?? ($test->questions()->max('order') + 1),
            'answer_key' => $data['type'] === 'mcq' ? ($data['answer_key'] ?? null) : null,
            'created_by' => auth()->id(),
        ]);

        if ($q->type === 'mcq') {
            foreach (['A','B','C','D','E'] as $L) {
                Option::create([
                    'question_id' => $q->id,
                    'label'       => $L,
                    'text'        => $data['options'][$L] ?? '',
                ]);
            }
        }

        return redirect()
            ->route('teacher.tests.show', $test)
            ->with('success', 'Soal ditambahkan.');
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
