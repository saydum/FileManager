<?php

namespace App\Http\Controllers;

use app\Contracts\FileServiceInterface;
use App\Models\Directory;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class   FileController extends Controller
{
    public function __construct(
        public FileServiceInterface $fileService
    )
    {
    }

    public function upload(Request $request, Directory $directory)
    {
        $files = $request->file('file');
        $fileModels = [];

        if (!$files) return response()->json(['message' => 'Нет файла!']);

        if ($request->hasFile('file')) {
            $fileModels = $this->fileService->upload($files, $directory, $request->user()->id);
        }

        return response()->json([
            'message' => 'Файлы успешно загружены.',
            'data' => $fileModels
        ]);
    }

    public function rename(Request $request, File $file)
    {
        $newName = $request->input('name');

        if (empty($newName)) {
            return response()->json([
                'message' => 'Невозможно переименовать файл. Укажите новое имя.'
            ], 400);
        }

        $success = $this->fileService->rename($file, $newName);

        if ($success) {
            return response()->json([
                'message' => 'Файл успешно переименован.',
                'data' => $file
            ], 201);
        } else {
            return response()->json([
                'message' => 'Ошибка при переименовании файла.'
            ], 400);
        }
    }

    public function delete(File $file)
    {
        if (!$file) {
            return response()->json(['message' => 'Файл не найден.'], 404);
        }

        $success = $this->fileService->delete($file);

        if ($success) {
            return response()->json(['message' => 'Файл успешно удален.']);
        } else {
            return response()->json(['message' => 'Ошибка при удалении файла.'], 500);
        }
    }

    public function getFileInfo(File $file)
    {
        if (!$file) return response()->json(['message' => 'Файл не найден.'], 404);

        $fileInfo = $this->fileService->getFileInfo($file);

        return response()->json($fileInfo);
    }

    public function hiddenFile(Request $request, int $fileId)
    {
        $file = File::find($fileId);

        if (!$file) {
            return response()->json(['message' => 'Файл не найден.'], 404);
        }

        $success = $this->fileService->hideFile($file);

        if ($success) {
            return response()->json(['message' => 'Файл успешно скрыт.']);
        } else {
            return response()->json(['message' => 'Ошибка при скрытии файла.'], 500);
        }
    }

    public function showFile(Request $request, int $fileId)
    {
        $file = File::find($fileId);

        if (!$file) {
            return response()->json(['message' => 'Файл не найден.'], 404);
        }

        $success = $this->fileService->showFile($file);

        if ($success) {
            return response()->json(['message' => 'Файл успешно показан.']);
        } else {
            return response()->json(['message' => 'Ошибка при показе файла.'], 500);
        }
    }

    public function generateDownloadToken(File $file)
    {
        $token = $this->fileService->generateDownloadToken($file);

        return response()->json([
            'message' => 'Токен для скачивания успешно создан.',
            'url' => "http://localhost/api/files/download/{$token}"
        ], 201);
    }

    public function downloadFile($token)
    {
        $file = File::where('download_token', $token)->firstOrFail();

        if (!Storage::exists($file->path)) {
            return response()->json(['message' => 'Файл не найден.'], 404);
        }

        return new StreamedResponse(function () use ($file) {
            $stream = Storage::readStream($file->path);
            fpassthru($stream);
        }, 200, [
            'Content-Type' => Storage::mimeType($file->path),
            'Content-Length' => Storage::size($file->path),
            'Content-Disposition' => 'attachment; filename="' . basename($file->path) . '"',
        ]);
    }
}
