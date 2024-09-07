<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/* MOdels */
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

use App\Models\Volunteer\Volunteer;
use App\Models\Volunteer\VolunteerRole;
use App\Models\Volunteer\VolunteerStatus;

use App\Models\EmailTemplate\EmailTemplate;

/* Services */
use App\Services\EmailService;
use App\Services\HookService;

/* Email */
use App\Mail\WelcomeVolunteer;
use App\Mail\InviteVolunteer;
use App\Mail\CreateUserAccount;
use App\Mail\DynamicEmailTemplate;

/* Support */
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

use stdClass;

class VolunteerRoleController extends Controller {


    private function getVolunteerRoles() {
        $query = VolunteerRole::query();

        if( request('search') ) {
            $query->where('volunteer_roles.name', 'like', '%' . request('search') . '%');
        }

        $query->leftJoin('volunteers', 'volunteer_roles.id', '=', 'volunteers.role')
            ->leftJoin('volunteer_statuses', 'volunteers.status', '=', 'volunteer_statuses.id')
            ->where('volunteer_roles.deleted', false)
            ->select(
                'volunteer_roles.id', 
                'volunteer_roles.name', 
                'volunteer_roles.description', 
                'volunteer_roles.volunteers_needed', 
            )
            ->groupBy(
                'volunteer_roles.id', 
                'volunteer_roles.name', 
                'volunteer_roles.description', 
                'volunteer_roles.volunteers_needed'
            );

        // foreach ($volunteerRoles as $role) {            
        //     if ($role->volunteers_needed != 0) {
        //         $role->coverage_percentage = min(100, ($role->volunteer_count / $role->volunteers_needed) * 100);
        //     } else {
        //         $role->coverage_percentage = 0;
        //     }
        // }

        return $query->get();
    }
    public function index() {
        return Inertia::render('Volunteers/Settings/Roles/Index', [
            'response' => [],
            'volunteerRoles' => self::getVolunteerRoles(),
        ]);
    }
    public function create() {
        return Inertia::render('Volunteers/Settings/Roles/CreateEdit', [
            'volunteerRole' => [],
            'response' => [],
        ]);
    }

    public function store(Request $request) {        
        try {
            DB::beginTransaction();

            $volunteerRole = new VolunteerRole();
            
            $volunteerRole->name = $request->name;
            $volunteerRole->volunteers_needed = $request->volunteers_needed;
            $volunteerRole->description = $request->description;

            $volunteerRole->save();

            DB::commit();

            return redirect()->route('volunteer-roles.index')->with([
                'message' => 'Ο ρόλος δημιουργήθηκε με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Throwable $ex) {
            DB::rollBack();

            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                'status' => 'error',
            ]);
        }
    }

    public function edit( $id ) {
        try {
            VolunteerRole::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(
                'error', 'Ουπς, κάτι πήγε στραβά. Ο ρόλος δεν υπάρχει.'
            );
        }

        return Inertia::render('Volunteers/Settings/Roles/CreateEdit', [
            'response' => [],
            'volunteerRole' => VolunteerRole::find( $id )
        ]);
    }

    public function update( Request $request ) {
        try {
            VolunteerRole::findOrFail($request->id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(
                'error', 'Ουπς, κάτι πήγε στραβά. Ο ρόλος δεν υπάρχει.'
            );
        }

        try {
            DB::beginTransaction();

            $volunteerRole = VolunteerRole::find($request->id);

            $volunteerRole->name = $request->name;
            $volunteerRole->volunteers_needed = $request->volunteers_needed;
            $volunteerRole->description = $request->description;

            $volunteerRole->save();

            DB::commit();

            return redirect()->route('volunteer-roles.index')->with([
                'message' => 'Ο ρόλος ενημερώθηκε με επιτυχία.',
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                'status' => 'error',
            ]);
        }
    }

    public function destroy( $id ) {
        try {
            VolunteerRole::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(
                'error', 'Ουπς, κάτι πήγε στραβά. Ο ρόλος δεν υπάρχει.'
            );
        }

        try {
            DB::beginTransaction();

            $volunteerRole = VolunteerRole::find( $id );
            
            $volunteerRole->deleted = true;
            $volunteerRole->save();

            DB::commit();

            return back()->with([
                'message' => 'Ο ρόλος έχει διαγραφεί με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                'status' => 'error',
            ]);
        }
    }
}