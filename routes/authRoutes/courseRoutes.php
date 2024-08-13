<?php
/**************************************************************************
 * COURSES ROUTES
**************************************************************************/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Course\CourseController;
use Inertia\Inertia;

// Course Settings
Route::get('/courses/settings', function () {
    return Inertia::render('Courses/Settings');
});

// Courses
Route::resource('/courses', CourseController::class);