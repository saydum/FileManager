<?php

namespace App\Services;

use App\Models\Directory;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DirectoryService
{
    public function mkdir(string $dirname): void
    {
        try {
            if (!Storage::exists($dirname)) {
                Storage::makeDirectory($dirname);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function delete(string $dirname)
    {
        try {
            if (Storage::exists($dirname)) {
                Storage::deleteDirectory($dirname);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function rename(Directory $directory, string $newDirName): bool
    {
        $oldPath = $directory->path;

        $newPath = dirname($oldPath) . '/' . $newDirName;

        if (Storage::disk('local')->exists($oldPath)) {
            Storage::disk('local')->move($oldPath, $newPath);
        } else {
            return false;
        }
        $directory->path = $newPath;
        $directory->name = $newDirName;
        return $directory->save();
    }
}
