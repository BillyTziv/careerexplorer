<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;

/*-------------------------------------------------------------------------------------
 | TASK ROUTES
-------------------------------------------------------------------------------------*/
Route::put('/tasks/{id}/complete', [TaskController::class, 'completeTask'])->name('tasks.complete');

Route::resource('/tasks', TaskController::class);
