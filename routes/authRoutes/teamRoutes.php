<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;

/*-------------------------------------------------------------------------------------
 | TASK ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/teams', TeamController::class);
