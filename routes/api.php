<?php

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Show all tasks
Route::get('/tasks', [TaskController::class, 'index']);

// Create a new task
Route::post('/createTask', [TaskController::class, 'store']);

// Find a specific task by ID
Route::get('/tasks/{id}', [TaskController::class, 'show']);

// Show completed tasks
Route::get('/showCompletedTasks', [TaskController::class, 'showCompletedTasks']);

// Show pending tasks
Route::get('/showPendingTasks', [TaskController::class, 'showPendingTasks']);

// Update a task status to completed
Route::patch('/markAsCompleted/{id}', [TaskController::class, 'markAsCompleted']);

// Update the title and description of a task
Route::put('/updateTask/{id}', [TaskController::class, 'update']);

// Return a task to pending status
Route::patch('/markAsPending/{id}', [TaskController::class, 'markAsPending']);
