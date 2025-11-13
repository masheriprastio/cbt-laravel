<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['upload' => ['required','image','max:2048']]); // file fieldname = "upload"
        $path = $request->file('upload')->store('uploads/ckeditor', 'public');
        return response()->json(['url' => Storage::url($path)]);
    }
}
