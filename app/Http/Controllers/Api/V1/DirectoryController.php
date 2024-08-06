<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Directory\RenameDirectoryRequest;
use App\Http\Requests\Directory\StoreDirectoryRequest;
use App\Models\Directory;
use App\Services\Directory\DirectoryService;
use App\Services\Directory\DirectorySystemService;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;

class DirectoryController extends Controller
{
    public function __construct(
        public FileService $fileService,
        public DirectoryService $directoryService,
        public DirectorySystemService $directorySystemService,
    )
    {}
    public function store(StoreDirectoryRequest $request): JsonResponse
    {
        $directory = $this->directoryService->store($request->validated());
        $this->directorySystemService->mkdir($request->name);

        return response()->json($directory, 201);
    }

    public function destroy(Directory $directory): JsonResponse
    {
        if (!$directory->delete()) {
            return response()->json([
                "message" => "Не удалось удалить директорию."
            ]);
        }

        $this->directorySystemService->remove($directory->name);

        return response()->json([
            "message" => "Директория и файлы внутри него успешно удалены."
        ]);
    }

    public function rename(RenameDirectoryRequest $request, Directory $directory)
    {
        if (!$directory) return response()->json(['message' => 'Директория не найдена.'], 404);

        $newName = $request->validated('name');

        $this->directoryService->update($directory, $newName);

        $this->directorySystemService->rename($directory->name, $newName);

        return response()->json(['message' => 'Директория успешно переименована.']);
    }
}
