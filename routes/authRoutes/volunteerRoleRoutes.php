<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Volunteer\VolunteerRoleController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER ROLE ROUTES
-------------------------------------------------------------------------------------*/
Route::resource('/volunteer-roles', VolunteerRoleController::class);