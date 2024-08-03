<?php

namespace App\Http\Contracts;

use App\Models\Directory;
use App\Models\File;

interface FileServiceInterface
{
    public function upload(array $files, Directory $directory, int $userId): array;

    public function rename(File $file, string $newName): bool;

    public function delete(File $file): bool;

    public function getFileInfo(File $file): array;

    public function hideFile(File $file): bool;

    public function showFile(File $file): bool;
}
