<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request, Directory $directory)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->move($directory->path, $fileName);

            File::create([
                'name' => $fileName,
                'path' => $directory->path,
                'directory_id' => $directory->id,
                'user_id' => $request->user()->id,
                'is_public' => true,
            ]);

            return response()->json(['message' => 'File uploaded successfully'], 201);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

    }
}
