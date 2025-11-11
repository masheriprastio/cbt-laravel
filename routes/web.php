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




Route::middleware(['auth','role:guru'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        // SPA shell

    });


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tests', TeacherTestController::class)
             ->names('tests');

             
});


// Soal
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

Route::middleware(['auth','role:guru'])
  ->prefix('teacher')
  ->as('teacher.')
  ->group(function () {
      Route::get('/dashboard', [TeacherTestController::class, 'dashboard'])->name('dashboard');
      Route::resource('tests', TeacherTestController::class)->names('tests');
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
  });

 



Route::get('/halo', function () {
    return 'Halo';
});

Route::get('/{any}', function () {
    return view('spa');
})->where('any', '.*');
