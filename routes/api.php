<?php

use App\Http\Controllers\Api\V1\DirectoryController;
use App\Http\Controllers\DiskUsageController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__.'/auth.php';


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/directories', [DirectoryController::class, 'store'])->name('directories.store');
    Route::put('directories/{directory}', [DirectoryController::class, 'rename'])->name('directories.rename');
    Route::delete('/directories/{directory}', [DirectoryController::class, 'destroy'])->name('directories.destroy');

    Route::post('/directories/{directory}/files', [FileController::class, 'upload'])->name('files.upload');

    Route::put('/files/{file}', [FileController::class, 'rename'])->name('files.rename');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.delete');
    Route::get('/files/{file}/info', [FileController::class, 'getFileInfo'])->name('files.info');
    Route::post('files/{file}/hidden', [FileController::class, 'hiddenFile'])->name('files.hidden');
    Route::post('files/{file}/show', [FileController::class, 'showFile'])->name('files.show');

    Route::get('/files/{file}/generate-token', [FileController::class, 'generateDownloadToken'])->name('files.generate.token');
    Route::get('/disk-usage', [DiskUsageController::class, 'index'])->name('disk.usage');
});

Route::get('/files/download/{token}', [FileController::class, 'downloadFile'])->name('files.download');

