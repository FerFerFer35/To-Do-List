<?php

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/createTask', [TaskController::class, 'store']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::get('/showCompletedTasks', [TaskController::class, 'showCompletedTasks']);
Route::get('/showPendingTasks', [TaskController::class, 'showPendingTasks']);
Route::patch('/markAsCompleted/{id}', [TaskController::class, 'markAsCompleted']);
Route::put('/updateTask/{id}', [TaskController::class, 'update']);
