<?php

namespace App\Services\Directory;

use App\Models\Directory;

class DirectoryService
{
    public function store(array $data): Directory
    {
        return Directory::create([
            'name' => $data['name'],
            'path' => $data['path'],
            'user_id' => $data['user_id'],
        ]);
    }

    public function update(Directory $directory, string $newDirname): Directory
    {
        $oldPath = $directory->path;
        $newPath = dirname($oldPath) . '/' . $newDirname;
        $directory->path = $newPath;
        $directory->name = $newDirname;
        $directory->save();

        return $directory;
    }
}
