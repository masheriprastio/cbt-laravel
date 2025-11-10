<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function dashboard() { return 'teacher dashboard ok'; }
    public function index() { return 'tests index ok'; }
    public function create() { return 'tests create ok'; }
    public function store() { /* ... */ }
    public function show($id) { return "tests show $id"; }
    public function edit($id) { return "tests edit $id"; }
    public function update($id) { /* ... */ }
    public function destroy($id) { /* ... */ }
}
