<?php

namespace App\Services;

use Storage;
use Exception;

class DirectoryService
{
    public function mkdir(string $dirname): void
    {
        $fullDirName = 'directories/' . $dirname;

        try {
            if (Storage::exists($fullDirName)) {
                throw new Exception("Директория с именем '{$fullDirName}'");
            }
            Storage::makeDirectory($fullDirName);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            throw $e;
        }
    }
}
