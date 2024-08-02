<?php

namespace App\Http\Controllers;

use App\Http\Contracts\FileServiceInterface;
use App\Models\Directory;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(
        public FileServiceInterface $fileService
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

    public function rename(Request $request, File $file)
    {
        $newName = $request->input('name');

        if (empty($newName)) {
            return response()->json([
                'message' => 'Невозможно переименовать файл. Укажите новое имя.'
            ], 400);
        }

        $success = $this->fileService->rename($file->id, $newName);

        if ($success) {
            return response()->json([
                'message' => 'Файл успешно переименован.',
                'data' => $file
            ], 201);
        } else {
            return response()->json([
                'message' => 'Ошибка при переименовании файла.'
            ], 400);
        }
    }
}
