<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function create($testId) { return "question create for test $testId"; }
    public function store($testId) { /* ... */ }
    public function destroy($questionId) { /* ... */ }
}
