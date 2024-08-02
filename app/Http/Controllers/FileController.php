<?php

namespace App\Http\Controllers;

use App\Http\Contracts\FileUploadServiceInterface;
use App\Models\Directory;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(
        public FileUploadServiceInterface $fileService
    )
    {}

    public function upload(Request $request, Directory $directory)
    {
        $files = $request->file('file');
        $fileModels = [];

        if (!$files) return response()->json(['message' => 'Нет файла!']);

        if ($request->hasFile('file')) {
            $fileModels = $this->fileService->upload($files, $directory, $request->user()->id);
        }

        return response()->json([
            'message' => 'Файлы успешно загружены.',
            'data' => $fileModels
        ]);
    }
}
