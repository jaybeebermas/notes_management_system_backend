<?php

use App\Http\Controllers\Api\NoteController;
use Illuminate\Support\Facades\Route;

Route::apiResource('notes', NoteController::class);
Route::get('categories', [NoteController::class, 'categories']);
