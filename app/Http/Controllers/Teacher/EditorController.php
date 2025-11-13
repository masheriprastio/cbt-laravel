<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('file')->store('public/uploads');

        $url = Storage::url($path);

        // Return both 'location' (TinyMCE style) and 'url' (Quill style) for compatibility
        return response()->json(['location' => $url, 'url' => $url]);
    }
}
