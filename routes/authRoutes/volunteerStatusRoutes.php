<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Volunteer\VolunteerController;
use App\Http\Controllers\Volunteer\VolunteerStatusController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER STATUS ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/volunteer-statuses', VolunteerStatusController::class);

Route::put('/volunteers/{volunteer}/status', [VolunteerController::class, 'updateStatus']);