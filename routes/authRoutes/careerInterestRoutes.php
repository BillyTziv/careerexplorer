<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Career\CareerInterestController;

/**************************************************************************
 *
 * CAREER INTERESTS ROUTES

**************************************************************************/

Route::resource('/career-interests', CareerInterestController::class);