<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DiskUsageController extends Controller
{
    public function index(): JsonResponse
    {
        $pathApp = storage_path('app');

        $totalSpace = disk_total_space($pathApp);
        $usedSpace = $this->getFolderSize($pathApp);
        $freeSpace = disk_free_space($pathApp);

        return response()->json([
            'total_space' => $this->formatSize($totalSpace),
            'used_space' => $this->formatSize($usedSpace),
            'free_space' => $this->formatSize($freeSpace),
        ]);
    }

    private function getFolderSize(string $directory): int
    {
        $size = 0;

        foreach (Storage::allFiles($directory) as $file) {
            $size =+ Storage::size($file);
        }

        return $size;
    }

    private function formatSize(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}
