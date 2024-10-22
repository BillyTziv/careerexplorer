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
use App\Models\Volunteer\Volunteer;

/* Services */
use App\Services\HookService;
use App\Services\EmailService;
use GuzzleHttp\Client;

class SessionRequestController extends Controller
{
    private function getAvailableSessionRequests( Request $request ) {
        $query = SessionRequest::query();

        if( request('status') ) {
            $query->where('status', '=', request('status'));
        }

        if( request('role') ) {
            $query->where('role', '=', request('role'));
        }

        if( request('search') && request('search') !== -1 ) {   
            $query->where('session_requests.firstname', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.lastname', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.email', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.phone', 'LIKE', '%'.request('search').'%');
        }
        
        $query->leftJoin('users', 'users.id', '=', 'session_requests.assignee')
            ->select(
                'session_requests.*', 
                'users.id as assignee_id', 
                'users.firstname as assignee_firstname', 
                'users.lastname as assignee_lastname'
            )
            ->where('session_requests.status', '=', 1)
            ->orderBy('session_requests.id');

        return $query->get();
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

    public function getMySessionRequests(Request $request)
    {
        $query = SessionRequest::query();

        if( request('search') && request('search') !== -1 ) {   
            $query->where('session_requests.firstname', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.lastname', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.email', 'LIKE', '%'.request('search').'%');
            $query->orWhere('session_requests.phone', 'LIKE', '%'.request('search').'%');
        }

        $query->leftJoin('users', 'users.id', '=', 'session_requests.assignee')
            ->select(
                'session_requests.id', 
                'session_requests.status', 
                'session_requests.firstname',
                'session_requests.lastname',
                'session_requests.email',
                'session_requests.phone',
                'session_requests.created_at', 
                'users.id as assignee_id', 
                'users.firstname as assignee_firstname',
                'users.lastname as assignee_lastname',
            )    
            ->where('session_requests.status', 2)       // Active
            // ->orWhere('session_requests.status', 3)     // Rejected
            // ->orWhere('session_requests.status', 4)     // Completed
            ->where('session_requests.assignee', auth()->id())
            ->orderBy('session_requests.status');

        return Inertia::render('SessionRequests/MySR', [
            'filters' => [
                'search' => request('search') ? request('search') : '',
            ],
            'sessions' => $query->get(),
        ]);
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
            'sessions' => self::getAvailableSessionRequests( $request ),
        ]);
    }

    public function getSessionRequestApplicationFields() {
        return Inertia::render('Applications/Create', [
            'metaData' => [
                'title' => 'Αίτηση Δωρεάν Ραντεβού Επαγγελματικού Προσανατολισμού',
                'subtitle' => 'Συμπληρώστε όλα τα στοιχεία σας για να ολοκληρώσετε την αίτηση.',
                'disclaimer' => 'Ο οργανισμός FutureGeneration, στην προσπάθειά του να ενισχύει συνεχώς τις νέες γενιές με τα εργαλεία και τη γνώση που χρειάζονται για να εξελιχθούν στην καριέρα τους, προσφέρει ένα δωρεάν ραντεβού επαγγελματικού προσανατολισμού. Η διάρκεια του ραντεβού είναι περίπου 30 λεπτά, δεν υπάρχει περιορισμός στην ηλικία και γίνεται ψηφιακά με βάση τη δική σου διαθεσιμότητα. Λόγω μεγάλου όγκου αιτήσεων, θα τηρηθεί σειρά προτεραιότητας. Για να την αποκτήσεις, απλά συμπληρώνεις τα στοιχεία σου και στο email που θα λάβεις, επιλέγεις την ημέρα και την ώρα που θέλεις να γίνει το ψηφιακό ραντεβού επαγγελματικού προσανατολισμού.',
                'consentMessage' => 'Δηλώνω ότι συναινώ στη συλλογή και επεξεργασία των προσωπικών μου δεδομένων για σκοπούς μελλοντικής επικοινωνίας από την FutureGeneration και τους εξουσιοδοτημένους συνεργάτες της, σύμφωνα με τις διατάξεις του Γενικού Κανονισμού Προστασίας Δεδομένων (GDPR).',
                'formType' => 'session-request',
            ],
            'submitRoute' => [
                'url' => '/applications/fg/submit',
                'method' => 'post',
            ],
            'formFields' => [
                'firstname' => [
                    'label' => 'Όνομα',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => 'Όνομα..',
                    'required' => true,
                ],
                'lastname' => [
                    'label' => 'Επώνυμο',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => 'Επώνυμο..',
                    'required' => true,
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => '',
                    'placeholder' => 'Email..',
                    'required' => true,
                ],
                'phone' => [
                    'label' => 'Τηλέφωνο',
                    'type' => 'tel',
                    'value' => '',
                    'placeholder' => 'Τηλέφωνο..',
                    'required' => true,
                ]
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return Inertia::render('SessionRequests/Create', [
        //     'submitRoute' => [
        //         'url' => '/session-requests',
        //         'method' => 'post',
        //     ],
        //     'formFields' => [
        //         'firstname' => [
        //             'label' => 'Όνομα',
        //             'type' => 'text',
        //             'value' => '',
        //             'placeholder' => 'πχ. Γιώργος',
        //             'required' => true,
        //         ],
        //         'lastname' => [
        //             'label' => 'Επώνυμο',
        //             'type' => 'text',
        //             'value' => '',
        //             'placeholder' => 'πχ. Παπαδόπουλος',
        //             'required' => true,
        //         ],
        //         'email' => [
        //             'label' => 'Email',
        //             'type' => 'email',
        //             'value' => '',
        //             'placeholder' => 'πχ. gpap@mail.com',
        //             'required' => true,
        //         ],
        //         'phone' => [
        //             'label' => 'Τηλέφωνο',
        //             'type' => 'tel',
        //             'value' => '',
        //             'placeholder' => 'πχ. 6940********',
        //             'required' => true,
        //         ]
        //     ],
        // ]);
    }

    public function requestCareerSession( Request $request ): RedirectResponse {
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
            $sessionRequest->firstname = $validatedData['firstname'];
            $sessionRequest->lastname = $validatedData['lastname'];
            $sessionRequest->phone = $validatedData['phone'];
            $sessionRequest->email = $validatedData['email'];
            $sessionRequest->status = 1;
           
            $sessionRequest->save();
        
            DB::commit();

            // DISCORD
            $client = new Client();

            $response = $client->request('POST', 'https://discord.com/api/webhooks/1279501507259273306/2-WdWBvvhws6PKxQMbg2y0CHpHJqtbtaiG3BzqZ3v8Ddzo4D3CR13v2qzYHMDHxYlWbq', [
                'json' => [
                    'content' => 'Ο ' . $validatedData['firstname'] . ' ' . $validatedData['lastname'] . ' έκανε αίτηση για συνεδρία επαγγελματικού προσανατολισμού και χρειάζεται την βοήθεια μας! '
                ]
            ]);

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
            $sessionRequest->firstname = $validatedData['firstname'];
            $sessionRequest->lastname = $validatedData['lastname'];
            $sessionRequest->phone = $validatedData['phone'];
            $sessionRequest->email = $validatedData['email'];
            $sessionRequest->status = 1;
           
            $sessionRequest->save();
        
            DB::commit();

            // DISCORD
            $client = new Client();

            $response = $client->request('POST', 'https://discord.com/api/webhooks/1279501507259273306/2-WdWBvvhws6PKxQMbg2y0CHpHJqtbtaiG3BzqZ3v8Ddzo4D3CR13v2qzYHMDHxYlWbq', [
                'json' => [
                    'content' => 'Ο ' . $validatedData['firstname'] . ' ' . $validatedData['lastname'] . ' έκανε αίτηση για συνεδρία επαγγελματικού προσανατολισμού και χρειάζεται την βοήθεια μας! '
                ]
            ]);

            // EMAIL
            $hookService = new HookService( new EmailService );
            $vdata = Volunteer::where('email', $validatedData['email'])->first();        
    
            $hookService->trigger('first_mail_for_career_session', $sessionRequest->email, $vdata );

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

    // public function transferOwnership( Request $request ) {        
    //     if( is_null($request) ) {
    //         return redirect()
    //             ->route('session-requests.show', ['session_request' => $request->id])
    //             ->with([
    //                 'message'=> 'Oups, something went wrong. Please try again.',
    //                 'status' => 'error'
    //             ]);
    //     }

    //     $sessionRequest = SessionRequest::find( $request->id );
    //     $sessionRequest->assignee = $request->ownerid;
    //     $sessionRequest->status = 2;
    //     $sessionRequest->save();

    //     return redirect()
    //         ->route('session-requests.show', ['session_request' => $request->id])
    //         ->with([
    //             'message'=> 'Session request successfully updated!',
    //             'status' => 'success'
    //         ]);
    // }

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
        $careerCoach = auth()->user();

        // Find how many accepted session requests the carer coach has
        $acceptedRequests = SessionRequest::where('assignee', $careerCoach->id)
            ->where('status', 2)
            ->count();
        
        // If accepted request is more than 10, return error
        if( $acceptedRequests >= 200 ) {
            return redirect()
                ->route('session-requests.index', ['session_request' => $id])
                ->with([
                    'message' => 'Ο αριθμός των ραντεβού που έχετε αποδεχτεί έχει φτάσει το όριο των 5. Παρακαλούμε απορρίψτε κάποια από τις υπάρχουσες συνεδρίες σας.',
                    'status' => 'error',
                ]);
        }

        $sessionRequest = SessionRequest::find( $id );
        $sessionRequest->status = 2;
        $sessionRequest->assignee = auth()->user()->id;
        $sessionRequest->save();

        return back()->with([
            'message' => 'Η αίτηση ανατέθηκε με επιτυχία!',
            'status' => 'success',
        ]);
    }

    public function drop( $sessionRequestId ) {
        try {
            $sessionRequest = SessionRequest::find( $sessionRequestId );

            $sessionRequest->status = 3;
            $sessionRequest->assignee = auth()->user()->id;

            $sessionRequest->save();

            // $hookService = new HookService( new EmailService );
            // $vdata = Volunteer::where('email', $sessionRequest->email)->first();        
    
            // $hookService->trigger('request_career_session_feedback', $sessionRequest->email, $vdata );

            return redirect()
                ->route('my-session-requests.index',)   
                ->with([
                    'message' => 'Η συνεδρία απορρίφθηκε με επιτυχία!',
                    'status' => 'success',
                ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                'status' => 'error',
            ]);
        }
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
                ->route('my-session-requests.index',)   
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
