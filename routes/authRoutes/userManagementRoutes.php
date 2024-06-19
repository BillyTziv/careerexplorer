<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\PermissionController;

/**************************************************************************
 *
 * USER MANAGEMENT ROUTES
 * 
**************************************************************************/

Route::resource('/users', UserController::class)->names([
    'index' => 'users.index',
    'create' => 'users.create',
    'store' => 'users.store',
    'show' => 'users.show',
    'edit' => 'users.edit',
    'update' => 'users.update',
    'destroy' => 'users.destroy',
]);

Route::resource('/roles', RoleController::class)->names([
    'index' => 'roles.index',
    'create' => 'roles.create',
    'store' => 'roles.store',
    'show' => 'roles.show',
    'edit' => 'roles.edit',
    'update' => 'roles.update',
    'destroy' => 'roles.destroy',
]);

Route::resource('/permissions', PermissionController::class)->names([
    'index' => 'permissions.index',
    'create' => 'permissions.create',
    'store' => 'permissions.store',
    'show' => 'permissions.show',
    'edit' => 'permissions.edit',
    'update' => 'permissions.update',
    'destroy' => 'permissions.destroy',
]);

// Route::post('/update-version', [UserController::class, 'updateVersion']);