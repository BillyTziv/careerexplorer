<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionRequest\SessionRequestController;
use Inertia\Inertia;

Route::get('/session-requests/submit-success', function () {
    return Inertia::render('SessionRequests/SubmitSuccess');
})->name('session-requests.submit-success');

Route::put('/session-requests/{id}/accept/', [SessionRequestController::class, 'accept'])->name('session-requests.accept');
// Route::put('/session-requests/decline/{id}', [SessionRequestController::class, 'decline'])->name('session-requests.complete');
Route::put('/session-requests/{id}/complete', [SessionRequestController::class, 'complete'])->name('session-requests.decline');
Route::put('/session-requests/{id}/transfer-ownership', [SessionRequestController::class, 'transferOwnership'])->name('session-requests.transfer-ownership');
Route::get('/my-session-requests', [SessionRequestController::class, 'getMySessionRequests'])->name('session-requests.my-session-requests');

Route::resource('/session-requests', SessionRequestController::class)->names([
    'index' => 'session-requests.index',
    'create' => 'session-requests.create',
    'store' => 'session-requests.store',
    'show' => 'session-requests.show',
    'edit' => 'session-requests.edit',
    'update' => 'session-requests.update',
    'destroy' => 'session-requests.destroy'
]);