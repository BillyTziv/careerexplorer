<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Career\CareerRequestController;

Route::put('/career-requests/complete/{id}', [CareerRequestController::class, 'complete'])->name('career-requests.complete');
Route::resource('/career-requests', CareerRequestController::class)->names([
    'index' => 'career-requests.index',
    'create' => 'career-requests.create',
    'store' => 'career-requests.store',
    'show' => 'career-requests.show',
    'edit' => 'career-requests.edit',
    'update' => 'career-requests.update',
    'destroy' => 'career-requests.destroy'
]);