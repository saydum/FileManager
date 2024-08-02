<?php

namespace App\Http\Contracts;

use App\Models\Directory;

interface FileServiceInterface
{
    public function upload(array $files, Directory $directory, int $userId): array;

    public function rename(int $fileId, string $newName): bool;
}
