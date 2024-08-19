<?php

namespace App\Http\Controllers\SessionRequest;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/* Models */
use App\Models\SessionRequest\SessionRequest;
use App\Models\UserManagement\User;
// use App\Models\Volunteer;

/* Services */
use App\Services\HookService;
use App\Services\EmailService;

class SessionRequestController extends Controller
{
    private function getAvailableSessionRequests( Request $request ) {
        $query = SessionRequest::query();

        $query->leftJoin('users', 'users.id', '=', 'session_requests.assignee')
            ->select('session_requests.id', 'session_requests.status', 'session_requests.firstname', 'session_requests.created_at', 'users.id as assignee_id', 'users.firstname as assignee_firstname')    
            ->where('session_requests.status', 1);
            
            if( request('search') && request('search') !== -1 ) {   
                $query->where('session_requests.firstname', 'LIKE', '%'.request('search').'%');
                $query->orWhere('session_requests.lastname', 'LIKE', '%'.request('search').'%');
                $query->orWhere('session_requests.email', 'LIKE', '%'.request('search').'%');
                $query->orWhere('session_requests.phone', 'LIKE', '%'.request('search').'%');
            }
            
        $availableSR = $query->paginate(20);

        return $availableSR;
    }

    private function getActiveSessionRequests( Request $request ) {
        $query = SessionRequest::query();

        $query->leftJoin('users', 'users.id', '=', 'session_requests.assignee')
            ->select('session_requests.*', 'users.id as assignee_id', 'users.firstname as assignee_firstname', 'users.lastname as assignee_lastname')
            ->where('session_requests.status', '!=', 1)
            ->orderBy('session_requests.status');;

        if( request('search') && request('search') !== -1 ) {
            $query->where('session_requests.firstname', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.lastname', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.email', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.phone', 'LIKE', '%'.request('search').'%');
        }
     
        if( !$request->user()->secured ) {
            $query->where('session_requests.assignee', $request->user()->id);
        }

        $activeSR = $query->paginate(30);
        return $activeSR;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('SessionRequests/Index', [
            'response' => [],
            'filters' => [
                'search' => request('search') ? request('search') : '',
            ],
            'sessions' => self::getAvailableSessionRequests( $request ),
            // 'activeSR' => self::getActiveSessionRequests( $request ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('SessionRequests/Create', [
            'submitRoute' => [
                'url' => '/session-requests',
                'method' => 'post',
            ],
            'formFields' => [
                'firstname' => [
                    'label' => 'Όνομα',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => 'πχ. Γιώργος',
                    'required' => true,
                ],
                'lastname' => [
                    'label' => 'Επώνυμο',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => 'πχ. Παπαδόπουλος',
                    'required' => true,
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => '',
                    'placeholder' => 'πχ. gpap@mail.com',
                    'required' => true,
                ],
                'phone' => [
                    'label' => 'Τηλέφωνο',
                    'type' => 'tel',
                    'value' => '',
                    'placeholder' => 'πχ. 6940********',
                    'required' => true,
                ]
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse {
        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255'
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό',
            'unique' => 'Το πεδίο πρέπει να είναι μοναδικό.'
        ];

        $validatedData = $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $sessionRequest = new SessionRequest();
            $sessionRequest->firstname = $request->firstname;
            $sessionRequest->lastname = $request->lastname;
            $sessionRequest->phone = $request->phone;
            $sessionRequest->email = $request->email;
    
            $sessionRequest->save();

            DB::commit();

            return redirect()->route( 'session-requests.submit-success' )->with([
                'status' => 'success',
                'message'=> 'Η αίτησή σας ολοκληρώθηκε.'
            ]);
        } catch (\Throwable $th) {
            return redirect()->route( 'session-requests.create' )->with([
                'status' => 'error',
                'message'=> 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.'
            ]);
        }
    }

    public function show( Request $request, $id ) {
        $sr = SessionRequest::with('assignee')->find( $id );

        // As long as SR is not yet accepted (pending status), hide contact information. Prevent coaches from direct contact user.
        if( $sr->status === 1 && !$request->user()->secured ) {
            $sr->lastname = "*****";
            $sr->phone = "*****";
            $sr->email = "*****";
        }

        if( $sr->status !== 1 ) {
            if( !$request->user()->secured && $request->user()->id !== $sr->assignee )  {
                return redirect()
                    ->route('session-requests.index')
                    ->with([
                        'message'=> 'Η συγκεκριμένη αίτηση συνεδρίας δεν είναι πλέον διαθέσιμη προς επιλογή.',
                        'status' => 'info'
                    ]);
            }
        }

        return Inertia::render('SessionRequests/Show', [
            'session-request' => $sr,
            'careerCoachDropdownOptions' => DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.id', '=', 3)
            ->select('users.id', DB::raw("CONCAT(users.firstname, ' ', users.lastname) AS label"))
            ->get()
        ]);
    }

    public function update( Request $request ) {
      
        $sessionRequest = SessionRequest::find( $request->id );

        if( is_null($sessionRequest) ) {
            return redirect()
                ->route('session-requests.show', ['session_request' => $request->id])
                ->with([
                    'message'=> 'Oups, something went wrong. Please try again.',
                    'status' => 'error'
                ]);
        }

        $sessionRequest->firstname = $request->firstname ?? null;
        $sessionRequest->lastname = $request->lastname ?? null;
        $sessionRequest->phone = $request->phone ?? null;
        $sessionRequest->email = $request->email ?? null;
        $sessionRequest->status = $request->status ?? null;
        $sessionRequest->notes = $request->notes ?? null;
        $sessionRequest->age = $request->age ?? null;
        $sessionRequest->gender = $request->gender ?? null;
        $sessionRequest->other_studies = $request->other_studies ?? null;
        $sessionRequest->career_status = $request->career_status ?? null;
        $sessionRequest->department = $request->department ?? null;
        $sessionRequest->university = $request->university ?? null;
        $sessionRequest->save();

        return redirect()
            ->route('session-requests.show', ['session_request' => $request->id])
            ->with([
                'message'=> 'Session request successfully updated!',
                'status' => 'success'
            ]);
    }

    public function transferOwnership( Request $request ) {        
        if( is_null($request) ) {
            return redirect()
                ->route('session-requests.show', ['session_request' => $request->id])
                ->with([
                    'message'=> 'Oups, something went wrong. Please try again.',
                    'status' => 'error'
                ]);
        }

        $sessionRequest = SessionRequest::find( $request->id );
        $sessionRequest->assignee = $request->ownerid;
        $sessionRequest->status = 2;
        $sessionRequest->save();

        return redirect()
            ->route('session-requests.show', ['session_request' => $request->id])
            ->with([
                'message'=> 'Session request successfully updated!',
                'status' => 'success'
            ]);
    }

    public function destroy( $sessionRequestId ) {
        if( !auth()->user()->secured ) {
            return redirect()
            ->route('session-requests.index')
            ->with([
                'message' => 'Δεν έχετε το δικαίωμα για αυτή την ενέργεια. Επικοινωνήστε με τον διαχειριστή του συστήματος.',
                'status' => 'error',
            ]);
        }

        $sessionRequest = SessionRequest::find( $sessionRequestId );
        $sessionRequest->deleted = true;
        $sessionRequest->save();

        return redirect()
            ->route('session-requests.index')
            ->with([
                'message' => 'Η συνεδρία διαγράφτηκε με επιτυχία!',
                'status' => 'success',
            ]);
    }

    public function accept( $id ) {
        $sessionRequest = SessionRequest::find( $id );
        $sessionRequest->status = 2;
        $sessionRequest->assignee = auth()->user()->id;
        $sessionRequest->save();

        return redirect()
            ->route('session-requests.show', ['session_request' => $id])
            ->with([
                'message' => 'Η συνεδρία επιβεβαιώθηκε με επιτυχία!',
                'status' => 'success',
            ]);
    }

    public function decline( $id ) {
        $sessionRequest = SessionRequest::find( $id );
        $sessionRequest->status = 3;
        $sessionRequest->assignee = auth()->user()->id;
        $sessionRequest->save();

        return redirect()
            ->route('session-requests.show', ['session_request' => $id])
            ->with([
                'message' => 'Η συνεδρία ακυρώθηκε με επιτυχία!',
                'status' => 'success',
            ]);
    }

    public function complete( $id ) {
        try {
            $sessionRequest = SessionRequest::find( $id );

            $sessionRequest->status = 4;
            $sessionRequest->assignee = auth()->user()->id;

            $sessionRequest->save();

            $hookService = new HookService( new EmailService );
            $vdata = Volunteer::where('email', $sessionRequest->email)->first();        
    
            $hookService->trigger('request_career_session_feedback', $sessionRequest->email, $vdata );

            return redirect()
                ->route('session-requests.show', ['session_request' => $id])   
                ->with([
                    'message' => 'Η συνεδρία ολοκληρώθηκε με επιτυχία!',
                    'status' => 'success',
                ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                'status' => 'error',
            ]);
        }
    }

    public function review( Request $request ) {
        $user = User::find($request->id);

        return Inertia::render('SessionRequests/ReviewForm', [
            'userId' => $user->id
        ]);
    }
}
