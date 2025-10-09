<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\TaskController;

Route::get('/moods', [MoodController::class, 'index']);
Route::post('/moods', [MoodController::class, 'store']); // now returns random actionable advice
Route::put('/moods/{id}', [MoodController::class, 'update']);
Route::delete('/moods/{id}', [MoodController::class, 'destroy']);
Route::get('/moods/summary', [MoodController::class, 'summary']);

// Task endpoints
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::patch('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::get('/tasks/summary', [TaskController::class, 'summary']);
