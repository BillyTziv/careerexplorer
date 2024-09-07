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

class VolunteerController extends Controller {

    public function __construct(HookService $hookService)
    {
        $this->hookService = $hookService;
    }

    private function getVolunteers() {
        $query = Volunteer::query();

        if( request('status') ) {
            $query->where('status', '=', request('status'));
        }

        if( request('role') ) {
            $query->where('role', '=', request('role'));
        }

        if( request('search') ) {
            $query->where(function ($q) {
                $q->where('firstname', 'LIKE', '%'.request('search').'%');
                $q->orWhere('lastname', 'LIKE', '%'.request('search').'%');
                $q->orWhere('email', 'LIKE', '%'.request('search').'%');
                $q->orWhere('phone', 'LIKE', '%'.request('search').'%');
            });
        }
        
        if( request('assigned_recruiter') ) {
            $query->where('assigned_to', '=', request('assigned_recruiter'));
        }

        $query->where('volunteers.deleted', '=', 0);

        $volunteers = $query->leftjoin('volunteer_roles', 'volunteer_roles.id', '=', 'volunteers.role')
            ->select('volunteers.*', DB::raw("volunteer_roles.name as volunteer_role"))
            ->orderByRaw("CASE 
                WHEN volunteers.status = 1 THEN 0
                WHEN volunteers.status = 7 THEN 1
                WHEN volunteers.status = 8 THEN 2
                WHEN volunteers.status = 9 THEN 3
                WHEN volunteers.status = 2 THEN 4
                WHEN volunteers.status = 3 THEN 5
                ELSE 6 END")
            ->orderByRaw("CASE WHEN volunteers.status = 1 THEN volunteers.created_at END DESC")
            ->orderByRaw("CASE WHEN volunteers.status != 1 THEN volunteers.created_at END ASC")
            ->get();

        return $volunteers;
    }

    private function getVolunteerRecruiters() {
        $recruiterRoleId = Role::where('name', 'Recruiter')->first()->id;

         // Get all users with the role 'Recruiter'
        $recruiters = User::whereHas('roles', function ($query) use ($recruiterRoleId) {
            $query->where('role_id', $recruiterRoleId);
        })->select('id', 'firstname', 'lastname')->get();

        return $recruiters;


        // $assignedToIds = Volunteer::where('deleted', false)
        //     ->pluck('assigned_to')
        //     ->filter(function ($value) {
        //         return !is_null($value);
        //     })
        //     ->toArray();

        // return User::whereIn('id', $assignedToIds)
        //     ->select('id', 'firstname', 'lastname')
        //     ->get();
    }

    private function getAssignees() {
        // Get from the VolunteerRoles the one that has the name 'Assignee'
        $role = VolunteerRole::where('name', 'Recruiter')->first();

        if (empty($role)) return [];

        return Volunteer::where('deleted', false)
            ->where('role', $role->id)
            ->get();
    }

    private function getVolunteerRoles() {
        return VolunteerRole::where('deleted', false)->get();
    }

    private function getVolunteerStatuses() {
        return VolunteerStatus::where('deleted', false)->get();
    }

    public function index() {        
        return Inertia::render('Volunteers/Index', [
            'volunteers' => self::getVolunteers(),
            'volunteerRoleDropdownOptions' => self::getVolunteerRoles(),
            'volunteerStatusDropdownOptions'=> self::getVolunteerStatuses(),
            'volunteerAssignedRecruiterDropdownOptions' => self::getVolunteerRecruiters(),
        ]);
    }

    public function getVolunteerApplicationFields() {
        return Inertia::render('Applications/Create', [
            'metaData' => [
                'title' => 'Αίτηση Εθελοντικής Συμμετοχής',
                'subtitle' => 'Συμπλήρωσε την αίτηση για να γίνεις και εσύ εθελοντής στον οργανισμό FutureGeneration.',
                // 'disclaimer' => 'Τα στοιχεία σου θα χρησιμοποιηθούν μόνο για την επικοινωνία μεταξύ εσένα και του οργανισμού μας.',
                'consentMessage' => 'Δηλώνω ότι συναινώ στη συλλογή και επεξεργασία των προσωπικών μου δεδομένων για σκοπούς μελλοντικής επικοινωνίας από την FutureGeneration και τους εξουσιοδοτημένους συνεργάτες της, σύμφωνα με τις διατάξεις του Γενικού Κανονισμού Προστασίας Δεδομένων (GDPR).',
                'formType' => 'volunteer',
            ],
            'submitRoute' => [
                'url' => '/applications/fg/submit',
                'method' => 'post',
            ],
            'formFields' => [
                'personal-information-section' => [
                    'title' => 'Στοιχεία Επικοινωνίας',
                    'type' => 'section',
                ],
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
                ],
                'cv-section' => [
                    'title' => 'Βιογραφικό Σημείωμα (CV)',
                    'type' => 'section',
                ],  
                'cv' => [
                    'label' => 'Βιογραφικό',
                    'type' => 'file',
                    'value' => '',
                    'accept' => 'application/pdf',
                    'required' => true,
                ],
                'volunteering-section' => [
                    'title' => 'Εθελοντική Συμμετοχή',
                    'type' => 'section',
                ],
                'role' => [
                    'label' => 'Σε ποιον εθελοντικό ρόλο ενδιαφέρεσαι να ασχοληθείς;',
                    'type' => 'select',
                    'options' => VolunteerRole::where('deleted', false)->get()->map(function($role) {
                        return [
                            'id' => $role->id,
                            'label' => $role->name,
                        ];
                    }),
                    'placeholder' => 'Επιλογή ρόλου',
                    'value' => null,
                    'required' => true,
                ],
                'interests' => [
                    'label' => 'Τι εμπειρίες ή δεξιότητες έχεις που σε καθιστούν κατάλληλο για αυτόν τον ρόλο;',
                    'type' => 'textarea',
                    'value' => '',
                    'placeholder' => 'Περιέγραψε τυχόν εμπειρίες, δεξιότητες ή προσόντα.',
                    'required' => true,
                ],
                'expectations' => [
                    'label' => 'Τι προσδοκίες έχεις από τον οργανισμό και τη συνεργασία μας;',
                    'type' => 'textarea',
                    'value' => '',
                    'placeholder' => '',
                    'required' => true,
                ],
                'hour_per_week' => [
                    'label' => 'Πόσες ώρες την εβδομάδα μπορείς να αφιερώσεις στον οργανισμό;',
                    'type' => 'number',
                    'value' => '',
                    'placeholder' => 'πχ. 4',
                    'min' => 1,
                    'max' => 168,
                    'required' => true,
                ],
                'previous_volunteering' => [
                    'label' => 'Έχεις ξανασυμμετάσχει σε εθελοντική δράση;',
                    'type' => 'select',
                    'options' => [
                        ['id' => 'yes', 'label' => 'Ναι'],
                        ['id' => 'no', 'label' => 'Όχι'],
                    ],
                    'value' => null,
                    'required' => true,
                ],      
                'volunteering_details' => [
                    'label' => 'Αν ναι, πες μας την εμπειρία σου',
                    'type' => 'textarea',
                    'value' => '',
                    'placeholder' => 'Περιέγραψε τον εθελοντικό ρόλο και τις αρμοδιότητες που είχες..',
                    'required' => false,
                ],        
                'studies-section' => [
                    'title' => 'Σπουδές',
                    'type' => 'section',
                ],            
                'university' => [
                    'label' => 'Σε ποιο εκπαιδευτικό ίδρυμα/πανεπιστήμιο φοιτάς ή φοίτησες;',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'department' => [
                    'label' => 'Σε ποιο τμήμα/σχολή φοιτάς ή φοίτησες;',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'otherstudies' => [
                    'label' => 'Έχεις επιπλέον μεταπτυχιακά, πιστοποιήσεις ή παρακολουθήσει σεμινάρια;',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'social-section' => [
                    'title' => 'Social Media',
                    'type' => 'section',
                ],   
                'linkedin' => [
                    'label' => 'Linkedin',
                    'type' => 'text',
                    'hint' => '',
                    'value' => '',
                    'placeholder' => 'Σύνδεμος προς το Linkedin προφίλ σου..',
                    'required' => false,
                ],
                'facebook' => [
                    'label' => 'Facebook',
                    'type' => 'text',
                    'hint' => '',
                    'value' => '',
                    'placeholder' => 'Σύνδεμος προς το Facebook προφίλ σου..',
                    'required' => false,
                ],
                'instagram' => [
                    'label' => 'Instagram',
                    'type' => 'text',
                    'value' => '',
                    'hint' => '',
                    'placeholder' => 'Σύνδεμος προς το Instagram προφίλ σου..',
                    'required' => false,
                ],
                'tiktok' => [
                    'label' => 'Tiktok',
                    'type' => 'text',
                    'value' => '',
                    'hint' => '',
                    'placeholder' => 'Σύνδεμος προς το Τικτοκ προφίλ σου..',
                    'required' => false,
                ]
            ],
        ]);
    }

    // public function apply(Request $request) {
    //     $rules = [
    //         'firstname' => 'required|string|max:255',
    //         'lastname' => 'required|string|max:255',
    //         'phone' => 'required|string|max:255|unique:volunteers,phone',
    //         'email' => 'required|email|max:255|unique:volunteers,email',
    //         'role' => 'required|integer|exists:volunteer_roles,id'
    //     ];
        
    //     $messages = [
    //         'required' => 'Το πεδίο είναι υποχρεωτικό.',
    //         'email.unique' => 'Το email πρέπει να είναι μοναδικό.',
    //     ];
        
    //     $validatedData = $request->validate($rules, $messages);


    //     // PHONE already exist.
    //     $existingVolunteer = Volunteer::firstWhere('phone', $request->contactInfo['phone']);
    //     if( $existingVolunteer ) {
    //         return back()
    //             ->withErrors([
    //                 'phone' => 'Ο αριθμός τηλεφώνου υπάρχει ήδη.'
    //             ]);
    //     }

    //     $existingVolunteer = Volunteer::firstWhere('email', $request->contactInfo['email']);
    //     if( $existingVolunteer ) {
    //         return back()
    //             ->withErrors([
    //                 'email' => 'Το email υπάρχει ήδη.'
    //             ]);
    //     }

    //     $volunteer = new Volunteer();
    
    //     // Personal Information
    //     $volunteer->firstname = $request->firstname;
    //     $volunteer->lastname = $request->lastname;
    //     $volunteer->cv = $request->cv;
 
    //     // Contact Information
    //     $volunteer->phone = $request->contactInfo['phone'];
    //     $volunteer->email = $request->contactInfo['email'];

    //     // Volunteering
    //     $volunteer->role = $request->volunteering['role'];
       
    //     // Personality
    //     $volunteer->description = "-";
    //     $volunteer->expectations = "-";
    //     $volunteer->interests = "-";
    //     $volunteer->reason = "-";

    //     // Social
    //     $volunteer->facebook = "-";
    //     $volunteer->instagram = "-";
    //     $volunteer->linkedin = "-";

    //     // Studies
    //     $volunteer->department = "-";
    //     $volunteer->otherstudies = "-";
    //     $volunteer->university = "-";
    
    //     // Find the default status
    //     $defaultStatus = VolunteerStatus::where('is_default', true)->first();
    //     $volunteer->status = $defaultStatus->id;
        
    //     $volunteer->deleted = false;
    //     $volunteer->save();

    //     return Inertia::render('Volunteers/ApplicationSuccessTEDX', [
    //         'response' => [],
    //     ]);
    // }

    public function create() {
        return Inertia::render('Volunteers/CreateEdit', [
            'volunteer' => new stdClass(),
            'roles' => self::getVolunteerRoles(),
            'assignees' => self::getAssignees(),
        ]);
    }
    
    private function validateVolunteer( $request ) {
        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:volunteers,phone',
            'email' => 'required|email|max:255|unique:volunteers,email',
            'role' => 'required|integer|exists:volunteer_roles,id',
            'cv' => 'required|string',
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.',
            'email.unique' => 'Το email πρέπει να είναι μοναδικό.',
            'role.exists' => 'Ο ρόλος που επιλέξατε δεν υπάρχει στο σύστημα. Διαλέξτε έναν τυχαία και αναφέρετε το σφάλμα στην συνέντευξη.',
        ];
        
        $validatedData = $request->validate($rules, $messages);

        // PHONE already exist.
        $existingVolunteer = Volunteer::firstWhere('phone', $request->phone);
        if( $existingVolunteer ) {
            return back()
                ->withErrors([
                    'phone' => 'Ο αριθμός τηλεφώνου υπάρχει ήδη.'
                ]);
        }

        $existingVolunteer = Volunteer::firstWhere('email', $request->email);
        if( $existingVolunteer ) {
            return back()
                ->withErrors([
                    'email' => 'Το email υπάρχει ήδη.'
                ]);
        }

        return ['error' => false];
    }

    public function store(Request $request) {        
        $validationResult = $this->validateVolunteer($request);

     
        if ($validationResult['error']) {
            return back()->withErrors($validationResult['message']);
        }

        $volunteer = new Volunteer();
    
        // Personal Information
        $volunteer->firstname = $request->firstname; // REQUIRED
        $volunteer->lastname = $request->lastname; // REQUIRED
        $volunteer->phone = $request->phone; // REQUIRED
        $volunteer->email = $request->email; // REQUIRED
     
        // CV
        $volunteer->cv = $request->cv; // REQUIRED

        // Personality
        $volunteer->description = $request->description ?? null;
        $volunteer->expectations = $request->expectations ?? null;
        $volunteer->interests = $request->interests ?? null;
        $volunteer->reason = $request->reason ?? null;

        // Volunteering
        $volunteer->role = $request->role; // REQUIRED
        $volunteer->hour_per_week = $request->hour_per_week ?? null;
        $volunteer->previous_volunteering = $request->previous_volunteering ?? null;
        $volunteer->volunteering_details = $request->volunteering_details ?? null;

        // Studies
        $volunteer->university = $request->university ?? null;
        $volunteer->department = $request->department ?? null;
        $volunteer->otherstudies = $request->otherstudies ?? null;

        // Social
        $volunteer->facebook = $request->facebook ?? null;
        $volunteer->instagram = $request->instagram ?? null;
        $volunteer->linkedin = $request->linkedin ?? null;
        $volunteer->linkedin = $request->tiktok ?? null;
     
        // Find detault status marked with is_default `true` in the volunteer_statuses table.
        $volunteer->status = VolunteerStatus::where('is_default', true)->first()->id;

        // Additional Information (JSONB COLUMN) -- Under Testing
        $volunteer->additional_info = $request->additional_info ?? null;
     
        $volunteer->start_date = now();
        $volunteer->deleted = false;
        $volunteer->cv_consent = true;
      
        $volunteer->save();
       
        $selectedRoleName = VolunteerRole::where('id', $volunteer->role)->first();

        // DISCORD
        $client = new Client();
        $response = $client->request('POST', 'https://discord.com/api/webhooks/1279501507259273306/2-WdWBvvhws6PKxQMbg2y0CHpHJqtbtaiG3BzqZ3v8Ddzo4D3CR13v2qzYHMDHxYlWbq', [
            'json' => [
                'content' => 'Yuppi! Νεα αίτηση εθελοντή με όνομα ' . $volunteer->firstname . ' ' . $volunteer->lastname .  ' και ρόλο ' . $selectedRoleName->name . '!'
            ]
        ]);

        //$emailService->sendTestEmail($request, $emailTemplate);

        // $data = ['message' => 'Dummy Data'];
        // Mail::to($request->contactInfo['email'])->send(new WelcomeVolunteer( $data ));

        return Inertia::render('Volunteers/Index', [
            'response' => [
                'message' => 'Η αίτησή σας ολοκληρώθηκε με επιτυχία!',
                'status' => 'success',
            ],
            'filters' => [
                'search' => request('search') ? request('search') : '',
                'role' => request('role') ? request('role') : '',
                'status' => request('status') ? request('status') : ''
            ],
            'volunteers' => self::getVolunteers(),
            'volunteerRoleDropdownOptions' => self::getVolunteerRoles()
        ]);
    }

    private function getUserRoles() {
        return Role::where('deleted', false)->select('id', 'name as label')->get();
    }

    public function show( $id ) {
        $query = Volunteer::query();

        $volunteer = $query->leftjoin('volunteer_roles', 'volunteer_roles.id', '=', 'volunteers.role')
            ->select('volunteers.*', DB::raw("JSON_OBJECT('name', volunteer_roles.name) as volunteer_role"),
             DB::raw("JSON_ARRAY(
                 JSON_OBJECT('label', 'linkedin', 'link', linkedin),
                 JSON_OBJECT('label', 'facebook', 'link', facebook),
                 JSON_OBJECT('label', 'instagram', 'link', instagram)
             ) as socialMedia"))
            ->find($id);

            
        if( $volunteer->socialMedia ) $volunteer->socialMedia = json_decode($volunteer->socialMedia, true);

        return Inertia::render('Volunteers/Show', [
            'response' => [],
            'volunteer' => $volunteer,
            'roles' => self::getUserRoles(),
            'assignees' => self::getAssignees(),
            'volunteerStatusDropdownOptions'=> self::getVolunteerStatuses(),
            'volunteerAssignedRecruiterDropdownOptions'=> self::getVolunteerRecruiters()
        ]);
    }

    public function edit( $volunteerId ) {
        $volunteer = Volunteer::find( $volunteerId );

        return Inertia::render('Volunteers/CreateEdit', [
            'response' => [],
            'volunteer' => $volunteer,
            'roles' => self::getVolunteerRoles(),
            'assignees' => self::getAssignees(),
        ]);
    }


    public function updateStatus(Request $request, $volunteer) {
        // Validate that the new status value is a valid status ID.
        $request->validate([
            'newStatusValue' => 'required|integer|exists:volunteer_statuses,id',
        ]);

        // Get the hook_id based on the email id.
        if( $request->sendEmail ) {
            try {
                // If exists, get the email template for the status change email.
                $status = VolunteerStatus::find($request->newStatusValue);
                $emailTemplateId = $status->email_template_id;
                
                $hookId = EmailTemplate::findOrFail($emailTemplateId)->hook_id;
            } catch (ModelNotFoundException $e) {
                // Handle the error here
                // For example, you can return an error response
                return back()->with([
                    'status' => 'error',
                    'message' => 'Δεν υπάρχει πρότυπο email για την συγκεκριμένη κατάσταση.'
                ], 404);
            }
        }

        // Change the status of the volunteer.
        $volunteer = Volunteer::findOrFail($volunteer);

        $volunteer->status = $request->newStatusValue;
        $volunteer->disapproved_reason = $request->statusChangeReason ?? null;
        $volunteer->disapproved_reason = $request->statusChangeReason ?? null;

        $volunteer->save();

        if( !$request->sendEmail ) {
            return back()->with([
                'message' => 'Η κατάσταση του εθελοντή ενημερώθηκε! Δεν έγινε αποστολή email.',
                'status' => 'success',
            ]);
        }

        // Send the email.
        $this->hookService->trigger($hookId, $volunteer->email, $volunteer );

        return back()->with([
            'message' => 'Η κατάσταση του εθελοντή ενημερώθηκε! Έγινε αποστολή email.',
            'status' => 'success',
        ]);
    }

    public function update( Request $request ) {
        // $validationResult = $this->validateVolunteer($request);

        // if ($validationResult['error']) {
        //     return back()->withErrors($validationResult['message']);
        // }

        $volunteer = Volunteer::find( $request->id );

        if( is_null($volunteer) ) {
            return redirect()
                ->route( 'users' )
                ->with([
                    'message'=> 'Oups, something went wrong. Volunteeer data was not saved.'
                ]);
        }

        // Personal Information
        $volunteer->firstname = $request->firstname;
        $volunteer->lastname = $request->lastname;
        $volunteer->cv = $request->cv;
        $volunteer->cv_consent = true;
        $volunteer->city = $request->city ?? null;
        $volunteer->address = $request->address?? null;
        $volunteer->date_of_birth = $request->date_of_birth ?? null;
        $volunteer->gender = $request->gender ?? null;
        $volunteer->age = $request->age ?? null;

        // Contact Information
        $volunteer->phone = $request->phone ?? null;
        $volunteer->email = $request->email ?? null;

        // Volunteering
        $volunteer->role = $request->role;
        $volunteer->start_date = $request->start_date ?? null;
        $volunteer->end_date = $request->end_date ?? null;
        $volunteer->hours_contributed = $request->hours_contributed ?? 0;
        $volunteer->onboarding_completed = $request->onboarding_completed ?? false;
        $volunteer->previous_volunteer_experience = $request->previous_volunteer_experience ?? null;
        $volunteer->disapproved_reason = $request->disapproved_reason ?? null;

        // Professional Work
        $volunteer->current_company = $request->current_company ?? null;
        $volunteer->current_role = $request->current_role ?? null;
        $volunteer->years_experience = $request->years_experience ?? null;
        $volunteer->career_status = $request->career_status ?? null;

        // Personality
        $volunteer->description = $request->description ?? null;
        $volunteer->expectations = $request->expectations ?? null;
        $volunteer->interests = $request->interests ?? null;
        $volunteer->reason = $request->reason ?? null;

        // Social
        $volunteer->facebook = $request->facebook ?? null;
        $volunteer->instagram = $request->instagram ?? null;
        $volunteer->linkedin = $request->linkedin ?? null;

        // Studies
        $volunteer->department = $request->department ?? null;
        $volunteer->otherstudies = $request->otherstudies ?? null;
        $volunteer->university = $request->university ?? null;
    
        // Links
        $volunteer->google_drive = $request->googleDrive ?? null;
        $volunteer->asana = $request->asana ?? null;

        $volunteer->notes = $request->notes ?? '';

        $volunteer->deleted = false;
        $volunteer->save();

        //$emailService->sendTestEmail($request, $emailTemplate);

        return back()->with([
            'message' => 'Τα στοιχεία ενημερώθηκαν με επιτυχία!',
            'status' => 'success',
        ]);
    }
    
    public function assignRecruiter( Request $request, $volunteer ) {

        $volunteer = Volunteer::find( $volunteer );
        $vid = $volunteer->id;

        $volunteer->assigned_to = $request->recruiterId ?? null;
        $volunteer->save();

        $query = Volunteer::query();

        return redirect()->back();
    }

    public function updateNotes( Request $request, $volunteer ) {

        $volunteer = Volunteer::find( $volunteer );
        $vid = $volunteer->id;

        if( is_null($request->notes) ) {
            return redirect()->back()->with([
                'message'=> 'Ουπς, κάτι πήγε στραβά. Οι σημειώσεις δεν αποθηκεύτηκαν.'
            ]);;

            // return redirect()
            //     ->route( 'users' )
            //     ->with([
            //         'message'=> 'Oups, something went wrong. Volunteeer data was not saved.'
            //     ]);
        }

        $volunteer->notes = $request->notes ?? '';
        $volunteer->save();

        $query = Volunteer::query();

        $volunteer = $query->leftjoin('volunteer_roles', 'volunteer_roles.id', '=', 'volunteers.role')
            ->select('volunteers.*', DB::raw("JSON_OBJECT('name', volunteer_roles.name) as volunteer_role"),
             DB::raw("JSON_ARRAY(
                 JSON_OBJECT('label', 'linkedin', 'link', linkedin),
                 JSON_OBJECT('label', 'facebook', 'link', facebook),
                 JSON_OBJECT('label', 'instagram', 'link', instagram)
             ) as socialMedia"))
            ->find($vid);

            
        if( $volunteer->socialMedia ) $volunteer->socialMedia = json_decode($volunteer->socialMedia, true);

        return redirect()->back();
    }

    // public function acceptApplication( Request $request ) {
    //     DB::beginTransaction();

    //     try {
    //         $selectedUserRole = $request->role;

    //         // Update the volunteer application status.
    //         $application = Volunteer::findOrFail($request->id);
    //         $application->update([
    //             'status' => 2,
    //         ]);

    //         // Check if a user with this email already exists to prevent duplication.
    //         $existingUser = User::where('email', $application->email)->first();

    //         if (!$existingUser) {
    //             // Create a new user with the approved volunteer's data.
    //             $user = new User();
    //             $user->firstname = $application->firstname;
    //             $user->lastname = $application->lastname;
    //             $user->phone = $application->phone;
    //             $user->email = $application->email;
             
    //             // Assuming volunteers have a default username or password, you can adjust accordingly
    //             $user->username = $application->email;
    //             $randomPassword = Str::random(16);
    //             $user->password = Hash::make($randomPassword);            
    //             $user->save();
            
    //             // Associate the user with a role.
    //             // Assuming you have a default role ID for volunteers, adjust the value accordingly
    //             DB::insert('insert into role_user (role_id, user_id) values (?, ?)', [$selectedUserRole, $user->id]);

    //             // Send an email to the approved volunteer about their new user account.
    //             $data = [
    //                 'password' => $randomPassword,
    //                 'username' => $application->email,
    //                 'message' => 'Ο λογαριασμός ενεργοποιήθηκε'
    //             ];

    //             // Get the email template ID.
    //             //$emailTemplate = EmailTemplate::where('name', 'volunteer_approved')->first();

    //             //Mail::to($application->email)->send(new CreateUserAccount($data));
    //             //$emailService->sendTestEmail($request, $emailTemplate->id);
    //             // try {
    //             //     $volunteerRole = VolunteerRole::find($selectedUserRole);
    //             //     $application['userrole'] = $volunteerRole->name;
                    
    //             //     $this->hookService->trigger('approve_volunteer', $application->email, $application );
    //             // } catch (\Exception $e) {
    //             //     // Handle exception
    //             //     return response()->json(['error' => $e->getMessage()], 400);
    //             // }


    //             DB::commit();

    //             return back()->with([
    //                 'message' => 'Η κατάσταση ενημερώθηκε με επιτυχία!',
    //                 'status' => 'success',
    //             ]);
    //         }else {
    //             // Create a new user with the approved volunteer's data.
    //             $user = $existingUser;
    //             $randomPassword = Str::random(16);
    //             $user->password = Hash::make($randomPassword);            
    //             $user->save();
            
    //             // Associate the user with a role.
    //             // Assuming you have a default role ID for volunteers, adjust the value accordingly
    //             DB::table('role_user')->where('user_id', $user->id)->delete();
    //             DB::insert('insert into role_user (role_id, user_id) values (?, ?)', [$selectedUserRole, $user->id]);

    //             // Send an email to the approved volunteer about their new user account.
    //             // $data = [
    //             //     'password' => $randomPassword,
    //             //     'username' => $application->email,
    //             //     'message' => 'Ο λογαριασμός ενεργοποιήθηκε'
    //             // ];

    //             //Mail::to($application->email)->send(new CreateUserAccount($data));
    //             // try {
    //             //     $volunteerRole = VolunteerRole::find($selectedUserRole);
    //             //     $application['userrole'] = $volunteerRole->name;
    //             //     $this->hookService->trigger('approve_volunteer', $application->email, $application );
    //             // } catch (\Exception $e) {
    //             //     // Handle exception
    //             //     return response()->json(['error' => $e->getMessage()], 400);
    //             // }

    //             DB::commit();

    //             return back()->with([
    //                 'message' => 'Η κατάσταση ενημερώθηκε με επιτυχία!',
    //                 'status' => 'success',
    //             ]);
    //         }
    //     } catch (\Throwable $ex) {
    //         DB::rollBack();

    //         // You can adjust the error message or logging here accordingly
    //         return back()->with([
    //             'status' => 'error',
    //             'message' => 'Oups, something went wrong while trying to approve the volunteer and create an associated user.'
    //         ]);
    //     }
    // }

    /**
     * Reject a volunteer application
     * 
     * @param Request $request : The request object
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function rejectApplication( Request $request ) {
    //     DB::beginTransaction();

    //     try {
    //         $application = Volunteer::findOrFail($request->id);
    //         $application->update([
    //             'status' => 3,
    //             'disapproved_reason' => $request->reason,
    //         ]);
    
    //         // try {
    //         //     $volunteer = Volunteer::find($application->id);
    //         //     $volunteerRole = VolunteerRole::find($volunteer->role);
    //         //     $application['userrole'] = $volunteerRole->name;
             
    //         //     $this->hookService->trigger('reject_volunteer', $application->email, $application );
    //         // } catch (\Exception $e) {
    //         //     return back()->with([
    //         //         'message' => 'Ουπς, κάτι πήγε στραβά. Το email δεν στάλθηκε.',
    //         //         'status' => 'error',
    //         //     ]);
    //         // }

    //         DB::commit();

    //         return back()->with([
    //             'message' => 'Ο κατάσταση ενημερώθηκε.',
    //             'status' => 'success',
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollback();

    //         // Handle the error, e.g. return an error response
    //         return back()->with([
    //             'status' => 'error',
    //             'message' => 'Oups, something went wrong while trying to approve the volunteer and create an associated user.'
    //         ]);
    //     }
    // }

    /**
     * Send an invitation to a volunteer
     * 
     * @param Request $request : The request object
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendInvitation( Request $request ) {
        try {
            $vdata = Volunteer::find($request->id);
            $this->hookService->trigger('invite_volunteer', $request->email, null );

            return back()->with([
                'message' => 'Η πρόσκληση έχει σταλθεί με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                'status' => 'error',
            ]);
        }
    }

    public function destroy( $id ) {
        try {
            DB::beginTransaction();

            $application = Volunteer::find( $id );
            $application->deleted = 1;
            $application->save();

            DB::commit();

            return redirect()->route( 'volunteers.index' )->with([
                'message' => 'Η διαγραφή ολοκληρώθηκε με επιτυχία!',
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            return redirect()->route( 'volunteers.index' )->with([
                'status' => 'error',
                'message'=> 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.'
            ]);
        }
    }
}