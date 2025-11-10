<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    public function dashboard() { return 'student dashboard ok'; }
    public function show($testId) { return "exam show $testId"; }
    public function start($testId) { /* ... */ }
    public function submit($testId) { /* ... */ }
}
