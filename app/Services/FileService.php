<?php

namespace App\Services;

use App\Http\Contracts\FileUploadServiceInterface;
use App\Models\Directory;
use App\Models\File;

class FileService implements FileUploadServiceInterface
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
            $path = $file->storeAs($directory->path . '/', $uniqueName);

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
}
