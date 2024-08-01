<?php

use App\Http\Controllers\DirectoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__.'/auth.php';


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/directories', [DirectoryController::class, 'store'])->name('directories.store');
});
