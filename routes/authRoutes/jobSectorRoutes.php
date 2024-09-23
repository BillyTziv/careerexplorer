<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Career\JobSectorController;

Route::resource('/job-sectors', JobSectorController::class)->names([
    'index' => 'job-sectors.index',
    'create' => 'job-sectors.create',
    'store' => 'job-sectors.store',
    'show' => 'job-sectors.show',
    'edit' => 'job-sectors.edit',
    'update' => 'job-sectors.update',
    'destroy' => 'job-sectors.destroy'
]);