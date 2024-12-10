<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionRequest\SessionRequestController;
use App\Http\Controllers\SessionRequest\SessionRequestStatusController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER STATUS ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/session-request-statuses', SessionRequestStatusController::class);

Route::put('/session-requests/{sessionRequest}/status', [SessionRequestController::class, 'updateStatus']);
