<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class GradingController extends Controller
{
    public function index($testId) { return "grading index for test $testId"; }
    public function grade($answerId) { /* ... */ }
}
