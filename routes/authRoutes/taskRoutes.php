<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER STATUS ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/tasks', TaskController::class);
