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
        
        $query->where('volunteers.deleted', '=', 0);

        $volunteers = $query->leftjoin('volunteer_roles', 'volunteer_roles.id', '=', 'volunteers.role')
            ->select('volunteers.*', DB::raw("volunteer_roles.name as volunteer_role"))
            ->orderByRaw("CASE 
                WHEN volunteers.status = 1 THEN 0
                WHEN volunteers.status = 2 THEN 1
                WHEN volunteers.status = 3 THEN 2
                ELSE 3 END")
            ->orderByRaw("CASE WHEN volunteers.status = 1 THEN volunteers.created_at END DESC")
            ->orderByRaw("CASE WHEN volunteers.status != 1 THEN volunteers.created_at END ASC")
            ->get();

        return $volunteers;
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
            'volunteerStatusDropdownOptions'=> self::getVolunteerStatuses()
        ]);
    }

    public function createPublicApplication() {
        return Inertia::render('Volunteers/Create', [
            'submitRoute' => [
                'url' => '/volunteers',
                'method' => 'post',
            ],
            'formFields' => [
                'role' => [
                    'label' => 'Θέλω να ασχοληθώ εθελοντικά ως',
                    'type' => 'select',
                    'options' => VolunteerRole::get()->map(function($role) {
                        return [
                            'id' => $role->id,
                            'label' => $role->name,
                        ];
                    }),
                    'placeholder' => 'Επιλογή ρόλου',
                    'value' => null,
                    'required' => true,
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
                'expectations' => [
                    'label' => 'Ποιες είναι οι προσδοκίες σου από τον οργανισμό;',
                    'type' => 'expectations',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'expectations' => [
                    'label' => 'Ποιες είναι οι προσδοκίες σου από τον οργανισμό;',
                    'type' => 'textarea',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'reason' => [
                    'label' => 'Με τι θα ήθελες να ασχοληθείς;',
                    'type' => 'textarea',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'interests' => [
                    'label' => 'Τι ενδιαφέροντά έχεις στην προσωπική σου ζωή;',
                    'type' => 'textarea',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'description' => [
                    'label' => 'Πως θα περιέγραφες τον εαυτό σου σε μια παράγραφο;',
                    'type' => 'textarea',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
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
                    'label' => 'Επιπλέον μεταπτυχιακά, πιστοποιήσεις, σεμινάρια;',
                    'type' => 'text',
                    'value' => '',
                    'placeholder' => '',
                    'required' => false,
                ],
                'linkedin' => [
                    'label' => 'Linkedin Profile URL',
                    'type' => 'text',
                    'hint' => '',
                    'value' => '',
                    'placeholder' => 'https://www.linkedin.com/in/profile-id',
                    'required' => false,
                ],
                'facebook' => [
                    'label' => 'Facebook Profile URL',
                    'type' => 'text',
                    'hint' => '',
                    'value' => '',
                    'placeholder' => 'https://www.facebook.com/profile.php?id=00000000',
                    'required' => false,
                ],
                'instagram' => [
                    'label' => 'Instagram Profile URL',
                    'type' => 'text',
                    'value' => '',
                    'hint' => '',
                    'placeholder' => 'https://www.instagram.com/profile-id/',
                    'required' => false,
                ],
                'cv' => [
                    'label' => 'Βιογραφικό',
                    'type' => 'file',
                    'value' => '',
                    'accept' => 'application/pdf',
                    'required' => true,
                ],
                'hasGivenConsent' => [
                    'label' => 'Συναινώ στη συλλογή του βιογραφικού σημειώματος μου για μελλοντική επικοινωνία απο το FutureGeneration και τους συνεργάτες του.',
                    'type' => 'checkbox',
                    'value' => false,
                    'required' => true,
                ],
            ],
        ]);
    }

    public function apply(Request $request) {
        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:volunteers,phone',
            'email' => 'required|email|max:255|unique:volunteers,email',
            'role' => 'required|integer|exists:volunteer_roles,id'
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.',
            'email.unique' => 'Το email πρέπει να είναι μοναδικό.',
        ];
        
        $validatedData = $request->validate($rules, $messages);


        // PHONE already exist.
        $existingVolunteer = Volunteer::firstWhere('phone', $request->contactInfo['phone']);
        if( $existingVolunteer ) {
            return back()
                ->withErrors([
                    'phone' => 'Ο αριθμός τηλεφώνου υπάρχει ήδη.'
                ]);
        }

        $existingVolunteer = Volunteer::firstWhere('email', $request->contactInfo['email']);
        if( $existingVolunteer ) {
            return back()
                ->withErrors([
                    'email' => 'Το email υπάρχει ήδη.'
                ]);
        }

        $volunteer = new Volunteer();
    
        // Personal Information
        $volunteer->firstname = $request->firstname;
        $volunteer->lastname = $request->lastname;
        $volunteer->cv = $request->cv;
 
        // Contact Information
        $volunteer->phone = $request->contactInfo['phone'];
        $volunteer->email = $request->contactInfo['email'];

        // Volunteering
        $volunteer->role = $request->volunteering['role'];
       
        // Personality
        $volunteer->description = "-";
        $volunteer->expectations = "-";
        $volunteer->interests = "-";
        $volunteer->reason = "-";

        // Social
        $volunteer->facebook = "-";
        $volunteer->instagram = "-";
        $volunteer->linkedin = "-";

        // Studies
        $volunteer->department = "-";
        $volunteer->otherstudies = "-";
        $volunteer->university = "-";
    
        // Find the default status
        $defaultStatus = VolunteerStatus::where('is_default', true)->first();
        $volunteer->status = $defaultStatus->id;
        
        $volunteer->deleted = false;
        $volunteer->save();

        return Inertia::render('Volunteers/ApplicationSuccessTEDX', [
            'response' => [],
        ]);
    }

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
        $volunteer->firstname = $request->firstname;
        $volunteer->lastname = $request->lastname;
        $volunteer->cv = $request->cv;
        $volunteer->cv_consent = $request->hasGivenConsent;

        // Contact Information
        $volunteer->phone = $request->phone;
        $volunteer->email = $request->email;

        // Status
        $defaultStatus = VolunteerStatus::where('is_default', true)->first();
        $volunteer->status = $defaultStatus->id;

        // Role (set the default role if not provided)
        if( $request->role ) {
            $volunteer->role = $request->role;
        }else {
            $defaultRole = VolunteerRole::where('is_default', true)->first();
            $volunteer->role = $defaultRole->id;
        }

        // Personality
        if( $request->description ) $volunteer->description = $request->description;
        if( $request->expectations ) $volunteer->expectations = $request->expectations;
        if( $request->interests ) $volunteer->interests = $request->interests;
        if( $request->reason ) $volunteer->reason = $request->reason;

        // Social
        if( $request->facebook ) $volunteer->facebook = $request->facebook;
        if( $request->instagram ) $volunteer->instagram = $request->instagram;
        if( $request->linkedin ) $volunteer->linkedin = $request->linkedin;

        // Studies
        if( $request->department ) $volunteer->department = $request->department;
        if( $request->otherstudies ) $volunteer->otherstudies = $request->otherstudies;
        if( $request->university ) $volunteer->university = $request->university;

        $volunteer->start_date = now();
        $volunteer->deleted = false;
        $volunteer->save();
        
        $client = new Client();
        $selectedRoleName = VolunteerRole::where('id', $volunteer->role)->first();
        $response = $client->request('POST', 'https://discordapp.com/api/webhooks/1240673991606276136/9N53dEq4rebaC6hBe1880npotoGfEXNxXEngWJs2bdrLr-_SRWliYf0_1y6MWk30GQEe', [
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
            'volunteerStatusDropdownOptions'=> self::getVolunteerStatuses()
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
        $volunteer->cv_consent = $request->hasGivenConsent;
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

        // return Inertia::render('Volunteers/Show', [
        //     'volunteer' => $volunteer,
        //     'roles' => self::getUserRoles(),
        //     'assignees' => self::getAssignees(),
        //     'volunteerStatusDropdownOptions'=> self::getVolunteerStatuses()
        // ]);
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