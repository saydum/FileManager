<?php

namespace App\Http\Contracts;

use App\Models\Directory;

interface FileUploadServiceInterface
{
    public function upload(array $files, Directory $directory, int $userId);
}
