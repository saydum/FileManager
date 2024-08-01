<?php

namespace App\Services;

use Log;
use Storage;
use Exception;

class DirectoryService
{
    public function mkdir(string $root, string $dirname): void
    {
        $fullDirName = $root . $dirname;

        try {
            if (!Storage::exists($fullDirName)) {
                Storage::makeDirectory($fullDirName);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function remove(string $root, string $dirname)
    {
        $fullDirName = $root . $dirname;

        try {
            if (Storage::exists($fullDirName)) {
                Storage::deleteDirectory($fullDirName);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
