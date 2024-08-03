<?php

namespace App\Services;

use Log;
use Storage;
use Exception;

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

    public function remove(string $dirname)
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
}
