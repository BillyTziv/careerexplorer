<?php

namespace App\Http\Controllers\Volunteer;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

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

class VolunteerStatusController extends Controller {
    private function getVolunteerStatuses() {
        $query = VolunteerStatus::query();

        // if( request('search') ) {
        //     $query->where('name', 'LIKE', '%'.request('search').'%');
        // }

        $volunteerStatuses = $query->where('deleted', false)->get();

        return $volunteerStatuses;
    }

    public function index(): Response {
        return Inertia::render('Volunteers/Settings/Statuses/Index', [
            'response' => [],
            // 'filters' => [
            //     'search' => request('search') ? request('search') : '',
            //     'role' => request('role') ? request('role') : '',
            //     'status' => request('status') ? request('status') : ''
            // ],
            'volunteerStatuses' => self::getVolunteerStatuses(),
            'volunteerStatusDropdownOptions'=> VolunteerStatus::where('deleted', false)->get()
        ]);
    }

    public function getStatuses()
    {
        // Fetch the statuses from the database
        $statuses = VolunteerStatus::all();

        // Return the statuses as a JSON response
        return response()->json($statuses);
    }

    private function getEmailTemplates() {
        return EmailTemplate::select('id', 'name')->get()->map(function ($item) {
            return ['id' => $item->id, 'label' => $item->name];
        });
    }

    public function create() {
        return Inertia::render('Volunteers/Settings/Statuses/CreateEdit', [
            'volunteerStatus' => [],
            'response' => [],
            'emailTemplates' => self::getEmailTemplates()
        ]);
    }

    private function validateVolunteerStatus( $request ) {
        $rules = [
            'name' => 'required|string|max:255|unique:volunteer_statuses,name',
            'description' => 'required|string|max:255',
            'hexColor' => 'required|string|max:7',
            'isDefault' => 'required|boolean',
            'isActive' => 'required|boolean',
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.',
            'name.unique' => 'Το όνομα πρέπει να είναι μοναδικό.',
            'email.unique' => 'Το email πρέπει να είναι μοναδικό.',
        ];
        
        $validatedData = $request->validate($rules, $messages);

        return ['error' => false];
    }

    public function store(Request $request) {

        $validationResult = $this->validateVolunteerStatus($request);

        if ($validationResult['error']) {
            return back()->withErrors($validationResult['message']);
        }

        try {
            DB::beginTransaction();

            if( $request->isDefault ) {
                VolunteerStatus::where('is_default', true)->update(['is_default' => false]);
            }

            if( $request->isActive ) {
                VolunteerStatus::where('is_active', true)->update(['is_active' => false]);
            }

            $volunteerStatus = new VolunteerStatus();

            $volunteerStatus->name = $request->name;
            $volunteerStatus->description = $request->description;
            $volunteerStatus->hex_color = $request->hexColor;
            $volunteerStatus->is_default = $request->isDefault;
            $volunteerStatus->is_active = $request->isActive;
            $volunteerStatus->email_template_id = $request->emailTemplateId ?? null;;

            $volunteerStatus->save();

            DB::commit();


            return redirect()->route('volunteer-statuses.index')->with([
                'message' => 'Ο κατάσταση δημιουργήθηκε με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Throwable $ex) {
            DB::rollBack();

            dd($ex);

            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                'status' => 'error',
            ]);
        }
    }

    public function edit( $id ) {
        try {
            VolunteerStatus::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(
                'error', 'Ουπς, κάτι πήγε στραβά. Η κατάσταση δεν υπάρχει.'
            );
        }

        return Inertia::render('Volunteers/Settings/Statuses/CreateEdit', [
            'response' => [],
            'volunteerStatus' => VolunteerStatus::find( $id ),
            'emailTemplates' => self::getEmailTemplates()
        ]);
    }

    public function update( Request $request ) {
        $validationResult = $this->validateVolunteerStatus($request);

        if ($validationResult['error']) {
            return back()->withErrors($validationResult['message']);
        }

        return redirect()->back()->withErrors([
            'message', 'Ουπς, κάτι πήγε στραβά. Ο κατάσταση δεν υπάρχει.'
        ]);

        try {
            VolunteerStatus::findOrFail($request->id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(
                'error', 'Ουπς, κάτι πήγε στραβά. Ο κατάσταση δεν υπάρχει.'
            );
        }

        try {
            DB::beginTransaction();

            if( $request->isDefault ) {
                VolunteerStatus::where('is_default', true)->update(['is_default' => false]);
            }

            if( $request->isActive ) {
                VolunteerStatus::where('is_active', true)->update(['is_active' => false]);
            }
            
            $volunteerStatus = VolunteerStatus::find($request->id);

            $volunteerStatus->name = $request->name;
            $volunteerStatus->description = $request->description;
            $volunteerStatus->hex_color = $request->hexColor;
            $volunteerStatus->is_default = $request->isDefault;
            $volunteerStatus->is_active = $request->isActive;
            $volunteerStatus->email_template_id = $request->emailTemplateId ?? null;

            $volunteerStatus->save();

            DB::commit();

            return redirect()->route('volunteer-statuses.index')->with([
                'message' => 'Η κατάσταση ενημερώθηκε με επιτυχία.',
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
            VolunteerStatus::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(
                'error', 'Ουπς, κάτι πήγε στραβά. Η κατάασταση δεν υπάρχει.'
            );
        }

        try {
            DB::beginTransaction();

            $volunteerStatus = VolunteerStatus::find( $id );
            
            $volunteerStatus->deleted = true;

            $volunteerStatus->save();

            DB::commit();

            return back()->with([
                'message' => 'Η κατάσταση έχει διαγραφεί με επιτυχία!',
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