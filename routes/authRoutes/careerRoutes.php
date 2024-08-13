<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Career\CareerController;
use App\Http\Controllers\Career\CareerValueController;
use App\Http\Controllers\Career\CareerSkillController;
use App\Http\Controllers\Career\CareerInterestController;
use App\Http\Controllers\Career\CareerRiasecCodeController;
use App\Http\Controllers\Career\CareerStudyController;


/**************************************************************************
 * CAREER ROUTES
**************************************************************************/
Route::resource('/careers', CareerController::class);

// Career Settings Routes
Route::get('/career-settings', [CareerController::class, 'showCareerSettings']);

Route::resource('/career-interests', CareerInterestController::class);
Route::resource('/career-riasec-codes', CareerRiasecCodeController::class);
Route::resource('/career-skills', CareerSkillController::class);
Route::resource('/career-studies', CareerStudyController::class);
Route::resource('/career-values', CareerValueController::class);
