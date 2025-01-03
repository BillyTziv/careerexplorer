<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskStatusController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER STATUS ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/task-statuses', TaskStatusController::class);

Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
