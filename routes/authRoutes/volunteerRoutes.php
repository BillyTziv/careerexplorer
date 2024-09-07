<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Volunteer\VolunteerController;

/*-------------------------------------------------------------------------------------
 | VOLUNTEER ROUTES
 |
 | GET	        /volunteers	                    index	    volunteers.index
 | GET	        /volunteers/create	            create	    volunteers.create
 | POST	        /volunteers	                    store	    volunteers.store
 | GET	        /volunteers/{volunteer}	        show	    volunteers.show
 | GET	        /volunteers/{volunteer}/edit	edit	    volunteers.edit
 | PUT/PATCH	/volunteers/{volunteer}	        update	    volunteers.update
 | DELETE	    /volunteers/{volunteer}	        destroy	    volunteers.destroy
 |
-------------------------------------------------------------------------------------*/

// [ !IMPORTANT ] Must be above the 'crud' operation routes, else they will overriden.
// Route::put('/volunteers/accept', [VolunteerController::class, 'acceptApplication'])
//     ->middleware('can:approve,App\Models\Volunteer');

// [ !IMPORTANT ] Must be above the 'crud' operation routes, else they will overriden.
// Route::put('/volunteers/reject', [VolunteerController::class, 'rejectApplication'])
//     ->middleware('can:disapprove,App\Models\Volunteer');

// [ !IMPORTANT ] Must be above the 'crud' operation routes, else they will overriden.
Route::post('/volunteers/sendInvitation', [VolunteerController::class, 'sendInvitation'])
    ->middleware('can:sendInvitation,App\Models\Volunteer');

Route::post('/volunteers/{volunteer}/notes', [VolunteerController::class, 'updateNotes']);

Route::put('/volunteers/{volunteer}/assign-recruiter', [VolunteerController::class, 'assignRecruiter']);

// Route::get('/volunteers/join', [VolunteerController::class, 'createPublicApplication'])
// ->middleware('can:create, App\Models\Volunteer')
// ->name('volunteers.join');
    

// --------------------------------------------------------------------------------
// PLACE ABOVE ALL ROUTES
// --------------------------------------------------------------------------------
Route::get('/volunteers', [VolunteerController::class, 'index'])
    // ->middleware('can:viewAny, App\Models\Volunteer')
    ->name('volunteers.index');

Route::get('/volunteers/create', [VolunteerController::class, 'create'])
    // ->middleware('can:create, App\Models\Volunteer')
    ->name('volunteers.create');

Route::post('/volunteers', [VolunteerController::class, 'store']);
    // ->middleware('can:create, App\Models\Volunteer');

Route::get('/volunteers/{volunteer}', [VolunteerController::class, 'show'])
    ->name('volunteers.show');
    // ->middleware('can:view, App\Models\Volunteer')
    

Route::get('/volunteers/{volunteer}/edit', [VolunteerController::class, 'edit'])
    // ->middleware('can:update, App\Models\Volunteer')
    ->name('volunteers.edit');

Route::put('/volunteers/{volunteer}', [VolunteerController::class, 'update']);
    // ->middleware('can:update, App\Models\Volunteer');

Route::delete('/volunteers/{volunteer}', [VolunteerController::class, 'destroy']);
    // ->middleware('can:delete, App\Models\Volunteer');