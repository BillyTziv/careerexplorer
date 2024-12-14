<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER STATUS ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/tasks', TaskController::class);
