<?php

namespace App\Services\Directory;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DirectorySystemService
{
    public function mkdir(string $dirname): bool
    {
        try {
            if (!Storage::exists($dirname)) {
                Storage::makeDirectory($dirname);
                return true;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return false;
    }

    public function remove(string $dirname): bool
    {
        try {
            if (Storage::exists($dirname)) {
                Storage::deleteDirectory($dirname);
                return true;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return false;
    }

    public function rename(string $oldDirname, string $newDirname): bool
    {
        try {
            if (Storage::exists($oldDirname)) {
                Storage::move($oldDirname, $newDirname);
                return true;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return false;
    }
}
