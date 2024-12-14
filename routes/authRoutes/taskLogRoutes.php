<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER STATUS ROUTES
-------------------------------------------------------------------------------------*/
Route::post('/task-logs', [TaskController::class, 'updateTaskLog']);
