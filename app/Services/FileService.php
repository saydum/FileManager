<?php

namespace App\Services;

use app\Contracts\FileServiceInterface;
use App\Models\Directory;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService implements FileServiceInterface
{

    /**
     * Upload files
     *
     * @param array $files
     * @param Directory $directory
     * @param int $userId
     * @return array
     */
    #[\Override]
    public function upload(array $files, Directory $directory, int $userId): array
    {
        $fileModels = [];

        foreach ($files as $file) {
            $uniqueName = uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($directory->path, $uniqueName);

            $fileModel = File::create([
                'name' => $uniqueName,
                'path' => $path,
                'directory_id' => $directory->id,
                'user_id' => $userId,
                'is_public' => true,
            ]);
            $fileModels[] = $fileModel;
        }
        return $fileModels;
    }

    #[\Override]
    public function rename(File $file, string $newName): bool
    {
        if (!$file) return false;

        $oldPath = $file->path;
        $directoryPath = dirname($oldPath);
        $newPath = $directoryPath . '/' . $newName;

        // Переименование файла на диске
        if (Storage::disk('local')->exists($oldPath)) {
            Storage::disk('local')->move($oldPath, $newPath);
        }

        $newFileName = pathinfo($newName, PATHINFO_FILENAME);
        $extensionFile = pathinfo($file->name, PATHINFO_EXTENSION);
        $file->name = $newFileName .'.'. $extensionFile;
        $file->path = $newPath;
        $file->save();

        return true;
    }

    #[\Override]
    public function delete(File $file): bool
    {
        if (Storage::disk('local')->exists($file->path)) {
            Storage::disk('local')->delete($file->path);
        }
        return $file->delete();
    }

    #[\Override]
    public function getFileInfo(File $file): array
    {
        return [
            'name' => $file->name,
            'path' => $file->path,
            'size' => $this->formatSize(Storage::disk('local')->size($file->path)),
            'uploaded_date' => $file->created_at,
        ];
    }

    #[\Override]
    public function hideFile(File $file): bool
    {
        $file->is_public = false;
        return $file->save();
    }

    #[\Override]
    public function showFile(File $file): bool
    {
        $file->is_public = true;
        return $file->save();
    }

    private function formatSize(int $bytes): string
    {
        $sizes = ['bytes', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $sizes[$factor]);
    }

    #[\Override]
    public function generateDownloadToken(File $file): string
    {
        $token = uniqid(Str::random(32));

        $file->update([
            'download_token' => $token
        ]);

        return $token;
    }
}
