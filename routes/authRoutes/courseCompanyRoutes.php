<?php
/**************************************************************************
 * COURSES COMPANY ROUTES
**************************************************************************/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Course\CourseCompanyController;

// Course Company
Route::resource('/course-companies', CourseCompanyController::class);