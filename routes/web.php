<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\TestController as TeacherTestController;
use App\Http\Controllers\Teacher\QuestionController as TeacherQuestionController;
use App\Http\Controllers\Teacher\GradingController as TeacherGradingController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
// use App\Http\Controllers\ProfileController;

// use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Teacher\QuestionBulkController;
use App\Http\Controllers\Teacher\EditorController;
use App\Http\Controllers\TemporaryExamController;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;




Route::middleware(['auth','role:guru'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        // SPA shell (empty - routes defined below)
    });


Route::get('/', function () {
    return view('welcome');
});

// Dashboard - accessible to all authenticated users
Route::get('/dashboard', function () {
    $user = Auth::user();
    $stats = ['tests' => 0, 'mcq' => 0, 'essay' => 0, 'active' => 0];
    $latestTests = [];
    if ($user && $user->role === 'guru') {
        // count tests created by this user (guru)
        $stats['tests'] = Test::where('created_by', $user->id)->count();
        $stats['mcq'] = \App\Models\Question::whereHas('test', function($q) use ($user) {
            $q->where('created_by', $user->id);
        })->where('type', 'mcq')->count();
        $stats['essay'] = \App\Models\Question::whereHas('test', function($q) use ($user) {
            $q->where('created_by', $user->id);
        })->where('type', 'essay')->count();
        $stats['active'] = Test::where('created_by', $user->id)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->count();
        $latestTests = Test::where('created_by', $user->id)->orderBy('created_at','desc')->limit(5)->get();
    }

    return view('dashboard', compact('stats','latestTests'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tests', TeacherTestController::class)
             ->names('tests');
});

// Soal routes
Route::get('tests/{test}/questions/create', [TeacherQuestionController::class, 'create'])->name('questions.create');
Route::post('tests/{test}/questions', [TeacherQuestionController::class, 'store'])->name('questions.store');
Route::delete('questions/{question}', [TeacherQuestionController::class, 'destroy'])->name('questions.destroy');

    // Route::middleware(['auth','role:guru'])
    // ->prefix('teacher')
    // ->as('teacher.')
    // ->group(function () {
    //     Route::get('/dashboard', [TeacherTestController::class, 'dashboard'])
    //         ->name('dashboard'); // <-- inilah teacher.dashboard
    //                  // prefix nama rute: teacher.*
    //     Route::resource('tests', TeacherTestController::class)
    //          ->names('tests');       // hasil: teacher.tests.index/create/store/...
    // });


require __DIR__.'/auth.php';

// ============================================================
// ADMIN ROUTES - Must come before SPA catch-all
// ============================================================
Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/reset', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset');
        Route::get('/users/{user}/print', [\App\Http\Controllers\Admin\UserController::class, 'printForm'])->name('users.print.form');
        Route::post('/users/{user}/print', [\App\Http\Controllers\Admin\UserController::class, 'printConfirm'])->name('users.print.confirm');
        Route::post('/users/import', [\App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import');
        Route::get('/users/import-template', [\App\Http\Controllers\Admin\UserController::class, 'importTemplate'])->name('users.import-template');
        Route::get('/users/print-all', [\App\Http\Controllers\Admin\UserController::class, 'printAll'])->name('users.print.all');
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
    });

// Alias route for admin users (shortcut to /admin/users)
Route::get('/users', function () {
    return redirect()->route('admin.users.index');
})->middleware(['auth','role:admin'])->name('users.index');

// ============================================================
// TEACHER ROUTES - Must come before SPA catch-all
// ============================================================

// Temporary demo exam (no auth) for quick student-mode testing
Route::get('/exam/demo', [TemporaryExamController::class, 'demo'])->name('exam.demo');
Route::post('/exam/demo/submit', [TemporaryExamController::class, 'submit'])->name('exam.demo.submit');
// helper: create a small sample test (for demo) and redirect to demo view
Route::get('/exam/demo/create-sample', [TemporaryExamController::class, 'createSample'])->name('exam.demo.sample');

// Exam session endpoints (demo uses these to report start/violations)
Route::post('/exam/session/start', [App\Http\Controllers\ExamSessionController::class, 'start'])->name('exam.session.start');
Route::post('/exam/session/{session}/violation', [App\Http\Controllers\ExamSessionController::class, 'violation'])->name('exam.session.violation');

// TEACHER ROUTES - Must come before SPA catch-all
// ============================================================
Route::middleware(['auth','role:guru'])
  ->prefix('teacher')
  ->as('teacher.')
  ->group(function () {
      Route::get('/dashboard', [TeacherTestController::class, 'dashboard'])->name('dashboard');
      Route::resource('tests', TeacherTestController::class)->names('tests');
    // Monitor exam sessions (guru)
    Route::get('/monitor', [App\Http\Controllers\ExamSessionController::class, 'index'])->name('monitor.index');
    Route::post('/monitor/{session}/resume', [App\Http\Controllers\ExamSessionController::class, 'resume'])->name('monitor.resume');
    Route::delete('/monitor/{session}', [App\Http\Controllers\ExamSessionController::class, 'destroy'])->name('monitor.destroy');
      Route::get('/questions/select', [TeacherQuestionController::class, 'select'])
            ->name('questions.select');

            // Ujian (sudah ada)
        Route::resource('tests', TeacherTestController::class)->names('tests');

        // Soal
        Route::get('tests/{test}/questions/create', [TeacherQuestionController::class, 'create'])
            ->name('questions.create');   // => teacher.questions.create

        Route::post('tests/{test}/questions', [TeacherQuestionController::class, 'store'])
            ->name('questions.store');

        // (opsional) halaman pilih ujian sebelum tambah soal
        Route::get('questions/select', [TeacherQuestionController::class, 'select'])
            ->name('questions.select');

            // Bulk input: step 1 → step 2 → store
            // STEP 1: form pengaturan (GET)
    Route::get('tests/{test}/questions/bulk/setup',
      [QuestionBulkController::class, 'setup'])->name('questions.bulk.setup');

    // STEP 2: build N form (POST) — izinkan juga GET agar mudah di-debug
    Route::match(['POST','GET'], 'tests/{test}/questions/bulk/build',
      [QuestionBulkController::class, 'build'])->name('questions.bulk.build');

    // STORE (POST)
    Route::post('tests/{test}/questions/bulk/store',
      [QuestionBulkController::class, 'store'])->name('questions.bulk.store');

       // hapus semua soal untuk 1 ujian (baru)
Route::delete('tests/{test}/questions/bulk', [QuestionBulkController::class, 'destroy'])
            ->name('questions.bulk.destroy');

            Route::get('tests/{test}/questions/{question}/edit',  [TeacherQuestionController::class,'edit'])->name('questions.edit');
    Route::put('tests/{test}/questions/{question}',       [TeacherQuestionController::class,'update'])->name('questions.update');
    Route::post('editor/upload', [EditorController::class, 'upload'])->name('editor.upload');
    // routes/web.php (di dalam group teacher)
Route::post('editor/upload', [\App\Http\Controllers\Teacher\EditorController::class, 'upload'])
     ->name('teacher.editor.upload');

  });

Route::get('/halo', function () {
    return 'Halo';
});

// ============================================================
// SPA CATCH-ALL - Keep this LAST
// ============================================================
