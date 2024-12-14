<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER STATUS ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/task-statuses', TaskStatusController::class);

Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
