<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestSubmissionController;
use App\Http\Controllers\TestController;

/**************************************************************************
 * TEST ROUTES
**************************************************************************/

Route::get('/riasec', [TestSubmissionController::class, 'calculateRIASECByUserId']);

Route::get('/tests/{id}/start', [TestController::class, 'start']);

Route::group(['middleware' => 'superadmin'], function () {
    Route::resource('/tests', TestController::class);
});

Route::get('/tests/submissions/viewtestresults', [TestSubmissionController::class, 'getHollandTest'])->name('tests.holland.start');
Route::get('/submissions/{id}/start', [TestSubmissionController::class, 'start']);
Route::get('/submissions/getResults/{email}', [TestSubmissionController::class, 'testSubmissions']);
Route::get('/submissions/{userId}/{testId}', [TestSubmissionController::class, 'showTestResults']);

Route::resource('/submissions', TestSubmissionController::class);