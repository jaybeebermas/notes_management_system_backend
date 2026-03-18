<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->group(function (): void {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
});

Route::middleware(['api', 'api.token'])->group(function (): void {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::get('notes', [NoteController::class, 'index']);
    Route::post('notes', [NoteController::class, 'store']);
    Route::get('notes/{id}', [NoteController::class, 'show']);
    Route::put('notes/{id}', [NoteController::class, 'update']);
    Route::patch('notes/{id}', [NoteController::class, 'update']);
    Route::delete('notes/{id}', [NoteController::class, 'destroy']);
    Route::get('categories', [NoteController::class, 'categories']);
});
