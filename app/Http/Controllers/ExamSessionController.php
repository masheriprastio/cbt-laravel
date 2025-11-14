<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamSession;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;

class ExamSessionController extends Controller
{
    // Start a session for an exam (called from demo when user clicks Start)
    public function start(Request $request)
    {
        $testId = $request->input('test_id');
        $test = null;
        if ($testId) {
            $test = Test::find($testId);
            if (!$test) {
                return response()->json(['error' => 'Test not found'], 404);
            }
        }

        $session = ExamSession::create([
            'test_id' => $testId,
            'user_id' => Auth::id(),
            'session_token' => bin2hex(random_bytes(12)),
            'started_at' => now(),
            'status' => 'running',
        ]);

        return response()->json(['id' => $session->id, 'session_token' => $session->session_token]);
    }

    // Record a violation hit (tab switch)
    public function violation(Request $request, ExamSession $session)
    {
        // increment violations
        $session->violations = intval($session->violations) + 1;
        $session->save();

        return response()->json(['violations' => $session->violations]);
    }

    // Teacher: list exam sessions
    public function index()
    {
        $sessions = ExamSession::with(['test','user'])->orderBy('started_at','desc')->get();
        return view('teacher.monitor.index', compact('sessions'));
    }

    // Teacher: resume a running session (e.g. after violation)
    public function resume(ExamSession $session)
    {
        $session->violations = 0;
        $session->status = 'running';
        $session->save();

        return back()->with('success', 'Sesi ujian dilanjutkan.');
    }

    // Teacher: delete a session
    public function destroy(ExamSession $session)
    {
        $session->delete();

        return back()->with('success', 'Sesi ujian dihapus.');
    }
}
