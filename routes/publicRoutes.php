<?php
use App\Http\Controllers\SessionRequest\SessionRequestController;
use App\Http\Controllers\Volunteer\VolunteerController;
use App\Http\Controllers\Test\TestSubmissionController;
use App\Http\Controllers\Career\CareerController;
use App\Http\Controllers\Career\CareerRequestController;
// use App\Http\Controllers\BlogController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ------------------------------------------------------------------------------
// STATIC PAGES
// ------------------------------------------------------------------------------
//Route::get('/contact', function () { return Inertia::render('Contact'); })->name('contact');
//Route::get('/faq', function () { return Inertia::render('FAQ'); })->name('faq');

// ------------------------------------------------------------------------------
// CAREER TEST
// ------------------------------------------------------------------------------

// Get Create Holland Test screen.
// Route::get('/tests/holland', [TestSubmissionController::class, 'getHollandTest'])->name('tests.holland');
Route::get('/tests/holland/start', [TestSubmissionController::class, 'startHollandTest'])->name('start.holland.test');

// Store a Holland Test.
Route::post('/tests/holland/submit', [TestSubmissionController::class, 'store']);
Route::post('/tests/holland/submitMetaData', [TestSubmissionController::class, 'storeMetaData']);

Route::get('/demo/tests/holland/results', [TestSubmissionController::class, 'demoHollandTestResults']);


Route::get('/tests/holland/{submissionId}/results', [TestSubmissionController::class, 'getHollandTestResults'])->name('tests.holland.results');
Route::get('/explore', [CareerController::class, 'exploreCareers'])->name('exploreCareers');
Route::get('/career-preview/{id}', [CareerController::class, 'singleCareer'])->name('singleCareer');

// ------------------------------------------------------------------------------
// PUBLIC APPLICATIONS
// ------------------------------------------------------------------------------
Route::prefix('/applications/fg')->group(function () {
    // Volunteer Application
    Route::get('/volunteer', [VolunteerController::class, 'getVolunteerApplicationFields'])
        ->name('applications.fg.volunteer');
        
    // Session Request Application
    Route::get('/session-request', [SessionRequestController::class, 'getSessionRequestApplicationFields'])
        ->name('applications.fg.session-request');

    // Submit Application
    Route::post('/submit', [ApplicationController::class, 'store']);
});

// ------------------------------------------------------------------------------
// CAREERS
// ------------------------------------------------------------------------------
Route::get('/session-requests', [SessionRequestController::class, 'create'])->name('session-requests.create');
Route::post('/session-requests', [SessionRequestController::class, 'store'])->name('session-requests.store');
Route::get('/session-requests/review', [SessionRequestController::class, 'review'])->name('session-requests.review');




// View a list fo all the job descriptions.
Route::get('/careers/list', [CareerController::class, 'list'])->name('careers.list');

// Show a single job description.
Route::get('/careers/single/{id}', [CareerController::class, 'single'])->name('careers.single');

// Store a new job description.
Route::post('/careerRequest', [CareerRequestController::class, 'store']);

Route::get('/careers/single/{id}', [CareerController::class, 'single'])->name('careers.single');

Route::get('/fg-posts', [BlogController::class, 'getPosts']);
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
