<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    if (Auth::check()) {
        return Inertia::render('Dashboard');
    } else {
        return Inertia::render('Welcome');
    }
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::redirect('/applications/fg/volunteer', '/volunteers/join');

Route::middleware('auth')->group(function () {
    /*Session Request Routes */
    require __DIR__.'/authRoutes/sessionRequestRoutes.php';

    /* Test Routes */
    require __DIR__.'/authRoutes/testRoutes.php';

    /* Career Routes */
    require __DIR__.'/authRoutes/careerRoutes.php';
    require __DIR__.'/authRoutes/careerInterestRoutes.php';
    require __DIR__.'/authRoutes/careerRequestRoutes.php';

    /* Course Routes */
    require __DIR__.'/authRoutes/courseRoutes.php';
    require __DIR__.'/authRoutes/courseCompanyRoutes.php';

    /* User Management Routes */
    require __DIR__.'/authRoutes/userManagementRoutes.php';
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/permissions', [ProfileController::class, 'getUserPermissions']);

    /* Volunteer Routes */
    require __DIR__.'/authRoutes/volunteerRoutes.php';
    require __DIR__.'/authRoutes/volunteerRoleRoutes.php';
    require __DIR__.'/authRoutes/volunteerStatusRoutes.php';
});

require __DIR__.'/auth.php';
