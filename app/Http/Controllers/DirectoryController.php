<?php

namespace App\Http\Controllers;

use app\Contracts\FileServiceInterface;
use App\Http\Requests\Directory\RenameDirectoryRequest;
use App\Http\Requests\Directory\StoreDirectoryRequest;
use App\Models\Directory;
use App\Services\DirectoryService;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    public function __construct(
        public DirectoryService $directoryService,
        public FileServiceInterface $fileService,
    )
    {}
    public function store(StoreDirectoryRequest $request)
    {
        $directory = Directory::create([
            'name' => $request->name,
            'path' => $request->path ,
            'user_id' => $request->user()->id,
        ]);

        $this->directoryService->mkdir($request->name);

        return response()->json($directory, 201);
    }

    public function destroy(Directory $directory)
    {
        // Удвляем файлы связанные с директорием
        foreach ($directory->files as $file) {
            $this->fileService->delete($file);
        }

        // Удаляем директорию
        $this->directoryService->delete($directory->name);
        $directory->delete();

        return response()->json(
            [
                "message" => "Директория и файлы внутри него успешно удалены."
            ]
        );
    }

    public function rename(Request $request, Directory $directory)
    {
        if (!$directory) return response()->json(['message' => 'Директория не найдена.'], 404);

        $newName = $request->input('name');
        $success = $this->directoryService->rename($directory, $newName);

        if ($success) {
            return response()->json(['message' => 'Директория успешно переименована.']);
        } else {
            return response()->json(['message' => 'Ошибка при переименовании директории.'], 500);
        }
    }
}
