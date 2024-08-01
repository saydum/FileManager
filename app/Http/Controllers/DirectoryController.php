<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use App\Services\DirectoryService;
use App\Http\Requests\Directory\StoreDirectoryRequest;

class DirectoryController extends Controller
{
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

        $this->directoryService->mkdir($request->name);

        return response()->json($directory, 201);
    }
}
