<?php

namespace App\Http\Controllers\SessionRequest;

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

use App\Models\EmailTemplate\EmailTemplate;

use App\Models\SessionRequest\SessionRequestStatus;

/* Services */
use App\Services\EmailService;
use App\Services\HookService;

/* Email */
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

class SessionRequestStatusController extends Controller {
    private function getSessionRequestStatuses() {
        $query = SessionRequestStatus::query();

        // if( request('search') ) {
        //     $query->where('name', 'LIKE', '%'.request('search').'%');
        // }

        $srStatuses = $query->where('deleted', false)->get();

        return $srStatuses;
    }

    public function index(): Response {
        return Inertia::render('SessionRequests/Statuses/Index', [
            'response' => [],
            'sessionRequestStatuses' => self::getSessionRequestStatuses(),
            'sessionRequestStatusDropdownOptions'=> SessionRequestStatus::where('deleted', false)->get()
        ]);
    }

    public function getStatuses()
    {
        // Fetch the statuses from the database
        $statuses = SessionRequestStatus::all();

        // Return the statuses as a JSON response
        return response()->json($statuses);
    }

    private function getEmailTemplates() {
        return EmailTemplate::select('id', 'name')->get()->map(function ($item) {
            return ['id' => $item->id, 'label' => $item->name];
        });
    }

    public function create() {
        return Inertia::render('SessionRequests/Statuses/CreateEdit', [
            'sessionRequestStatus' => [],
            'response' => [],
            'emailTemplates' => self::getEmailTemplates()
        ]);
    }

    private function validateSessionRequestStatus( $request ) {
        $rules = [
            'name' => 'required|string|max:255|unique:session_request_statuses,name',
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

        $validationResult = $this->validateSessionRequestStatus($request);

        if ($validationResult['error']) {
            return back()->withErrors($validationResult['message']);
        }

        try {
            DB::beginTransaction();

            if( $request->isDefault ) {
                SessionRequestStatus::where('is_default', true)->update(['is_default' => false]);
            }

            $sessionRequestStatus = new SessionRequestStatus();

            $sessionRequestStatus->name = $request->name;
            $sessionRequestStatus->description = $request->description;
            $sessionRequestStatus->hex_color = $request->hexColor;
            $sessionRequestStatus->is_default = $request->isDefault;
            $sessionRequestStatus->is_active = true;
            $sessionRequestStatus->email_template_id = $request->emailTemplateId ?? null;;

            $sessionRequestStatus->save();

            DB::commit();


            return redirect()->route('session-request-statuses.index')->with([
                'message' => 'Ο κατάσταση δημιουργήθηκε με επιτυχία!',
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
            SessionRequestStatus::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(
                'error', 'Ουπς, κάτι πήγε στραβά. Η κατάσταση δεν υπάρχει.'
            );
        }

        return Inertia::render('SessionRequests/Statuses/CreateEdit', [
            'response' => [],
            'sessionRequestStatus' => SessionRequestStatus::find( $id ),
            'emailTemplates' => self::getEmailTemplates()
        ]);
    }

    public function update( Request $request ) {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'hexColor' => 'required|string|max:7',
            'isDefault' => 'required|boolean',
            'isActive' => 'required|boolean',
        ];

        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.',
            'email.unique' => 'Το email πρέπει να είναι μοναδικό.',
        ];

        try {
            $validatedData = $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()->withErrors([
                'message', 'Ουπς, κάτι πήγε στραβά. Ο κατάσταση δεν υπάρχει.'
            ]);
        }


        try {
            SessionRequestStatus::findOrFail($request->id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(
                'error', 'Ουπς, κάτι πήγε στραβά. Ο κατάσταση δεν υπάρχει.'
            );
        }

        try {
            DB::beginTransaction();

            $sessionRequestStatus = SessionRequestStatus::find($request->id);

            $sessionRequestStatus->update([
                'name' => $request->name,
                'description' => $request->description,
                'hex_color' => $request->hexColor,
                'is_default' => $request->isDefault,
                'is_active' => true,
                'email_template_id' => $request->emailTemplateId ?? null,
            ]);

            DB::commit();

            return redirect()->route('session-request-statuses.index')->with([
                'message' => 'Η κατάσταση ενημερώθηκε με επιτυχία.',
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors([
                'error' => $th->getMessage(),
                'status' => 'error',
            ]);
        }
    }

    public function destroy( $id ) {
        try {
            SessionRequestStatus::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(
                'error', 'Ουπς, κάτι πήγε στραβά. Η κατάασταση δεν υπάρχει.'
            );
        }

        try {
            DB::beginTransaction();

            $sessionRequestStatus = SessionRequestStatus::find( $id );

            $sessionRequestStatus->deleted = true;

            $sessionRequestStatus->save();

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
