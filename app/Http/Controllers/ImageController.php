<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('uploads', 'public'); // Save to 'storage/app/public/uploads'
            $url = Storage::url($path); // Generate URL for the stored file

            return response()->json(['success' => true, 'url' => $url]);
        }

        return response()->json(['success' => false, 'message' => 'No image uploaded.']);
    }
}
