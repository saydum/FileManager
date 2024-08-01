<?php

namespace App\Http\Controllers;

use App\Http\Requests\Directory\StoreDirectoryRequest;
use App\Models\Directory;
use App\Services\DirectoryService;

class DirectoryController extends Controller
{
    private const string ROOT_DIR_NAME = 'directories/';

    public function __construct(
        public DirectoryService $directoryService,
    )
    {}
    public function store(StoreDirectoryRequest $request)
    {
        $directory = Directory::create([
            'name' => $request->name,
            'path' => $request->path,
            'user_id' => $request->user()->id,
        ]);

        $this->directoryService->mkdir(self::ROOT_DIR_NAME, $request->name);

        return response()->json($directory, 201);
    }

    public function destroy(Directory $directory)
    {
        $this->directoryService->remove(self::ROOT_DIR_NAME, $directory->name);
        $directory->delete();

        return response()->json(
            [
                "message" => "Директория успешно удален."
            ]
        );
    }
}
