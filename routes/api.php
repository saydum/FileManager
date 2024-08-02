<?php

use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__.'/auth.php';


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/directories', [DirectoryController::class, 'store'])->name('directories.store');
    Route::delete('/directories/{directory}', [DirectoryController::class, 'destroy'])->name('directories.delete');

    Route::post('/directories/{directory}/files', [FileController::class, 'upload'])->name('files.upload');
    Route::put('/files/{file}/rename', [FileController::class, 'rename'])->name('files.rename');
});
