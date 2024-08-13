<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Career\CareerSkillController;

/**************************************************************************
 *
 * CAREER SKILLS ROUTES

**************************************************************************/

Route::resource('/career-skills', CareerSkillController::class)->names([
    'index' => 'career-skills.index',
    'create' => 'career-skills.create',
    'store' => 'career-skills.store',
    'show' => 'career-skills.show',
    'edit' => 'career-skills.edit',
    'update' => 'career-skills.update',
    'destroy' => 'career-skills.destroy'
]);