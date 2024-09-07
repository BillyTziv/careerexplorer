<?php

namespace App\Http\Controllers\Application;

use stdClass;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SessionRequest\SessionRequestController;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Models\Application\Application;
use App\Http\Controllers\Volunteer\VolunteerController;
use App\Services\HookService;
use App\Services\EmailService;
use App\Models\Volunteer\VolunteerRole;
use App\Models\Volunteer\Volunteer;

class ApplicationController extends Controller
{
    private function getApplications() {
        $query = Application::query();

        $applications = $query ->paginate(10);

        return $applications;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Applications/Index', [
            'response' => [],
            'filters' => [
                'search' => request('search') ? request('search') : '',
            ],
            'applications' => self::getApplications(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return Inertia::render('Applications/Create', [
        //     'metaData' => [
        //         'title' => 'Αίτηση Εθελοντικής Συμμετοχής',
        //         'subtitle' => 'Συμπληρώστε την αίτηση για να γίνετε εθελοντής στον οργανισμό μας.',
        //         'disclaimer' => 'Συμπληρώστε την αίτηση για να γίνετε εθελοντής στον οργανισμό μας.',
        //         'consentMessage' => 'Δηλώνω ότι συναινώ στη συλλογή και επεξεργασία των προσωπικών μου δεδομένων για σκοπούς μελλοντικής επικοινωνίας από την FutureGeneration και τους εξουσιοδοτημένους συνεργάτες της, σύμφωνα με τις διατάξεις του Γενικού Κανονισμού Προστασίας Δεδομένων (GDPR).'
        //     ],
        //     'submitRoute' => [
        //         'url' => '/applications/submit',
        //         'method' => 'post',
        //     ],
        //     'formFields' => [
        //         'role' => [
        //             'label' => 'Θέλω να ασχοληθώ εθελοντικά ως',
        //             'type' => 'select',
        //             'options' => VolunteerRole::where('deleted', false)->get()->map(function($role) {
        //                 return [
        //                     'id' => $role->id,
        //                     'label' => $role->name,
        //                 ];
        //             }),
        //             'multiple' => false,
        //             'value' => '',
        //             'required' => true,
        //         ],
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
        //             'placeholder' => 'πχ. gpap@gmail.com',
        //             'required' => true,
        //         ],
        //         'phone' => [
        //             'label' => 'Τηλέφωνο',
        //             'type' => 'tel',
        //             'value' => '',
        //             'placeholder' => 'πχ. 69**********',
        //             'required' => true,
        //         ],
        //         'cv' => [
        //             'label' => 'Βιογραφικό',
        //             'type' => 'file',
        //             'value' => '',
        //             'accept' => 'application/pdf',
        //             'required' => true,
        //         ],
        //         'expectations' => [
        //             'label' => 'Ποιες είναι οι προσδοκίες σου από τον οργανισμό;',
        //             'type' => 'textarea',
        //             'value' => '',
        //             'placeholder' => '',
        //             'required' => false,
        //         ],
        //         'reason' => [
        //             'label' => 'Με τι θα ήθελες να ασχοληθείς;',
        //             'type' => 'textarea',
        //             'value' => '',
        //             'placeholder' => '',
        //             'required' => false,
        //         ],
        //         'interests' => [
        //             'label' => 'Τι ενδιαφέροντά έχεις στην προσωπική σου ζωή;',
        //             'type' => 'textarea',
        //             'value' => '',
        //             'placeholder' => '',
        //             'required' => false,
        //         ],
        //         'description' => [
        //             'label' => 'Πως θα περιέγραφες τον εαυτό σου σε μια παράγραφο;',
        //             'type' => 'textarea',
        //             'value' => '',
        //             'placeholder' => '',
        //             'required' => false,
        //         ],
        //         'university' => [
        //             'label' => 'Σε ποιο εκπαιδευτικό ίδρυμα/πανεπιστήμιο φοιτάς ή φοίτησες;',
        //             'type' => 'text',
        //             'value' => '',
        //             'placeholder' => '',
        //             'required' => false,
        //         ],
        //         'department' => [
        //             'label' => 'Σε ποιο τμήμα/σχολή φοιτάς ή φοίτησες;',
        //             'type' => 'text',
        //             'value' => '',
        //             'placeholder' => '',
        //             'required' => false,
        //         ],
        //         'otherstudies' => [
        //             'label' => 'Επιπλέον μεταπτυχιακά, πιστοποιήσεις, σεμινάρια;',
        //             'type' => 'text',
        //             'value' => '',
        //             'placeholder' => '',
        //             'required' => false,
        //         ],
        //         'linkedin' => [
        //             'label' => 'Linkedin Profile',
        //             'type' => 'text',
        //             'hint' => '',
        //             'value' => '',
        //             'placeholder' => 'https://www.linkedin.com/in/profile-id',
        //             'required' => false,
        //         ],
        //         'facebook' => [
        //             'label' => 'Facebook Profile',
        //             'type' => 'text',
        //             'hint' => '',
        //             'value' => '',
        //             'placeholder' => 'https://www.facebook.com/profile.php?id=00000000',
        //             'required' => false,
        //         ],
        //         'instagram' => [
        //             'label' => 'Instagram Profile',
        //             'type' => 'text',
        //             'value' => '',
        //             'hint' => '',
        //             'placeholder' => 'https://www.instagram.com/profile-id/',
        //             'required' => false,
        //         ]
        //     ],
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Store the application.
        $application = new Application;
        $application->submission = $request['form'];
        // TODO -- Add the organization id .
        $application->save();

        // Transfer the application submission request to the appropriate controller.
        switch ($request['formType']) {
            case 'session-request':
                // --------------------------------------------------------------------
                // VALIDATION
                // --------------------------------------------------------------------
                $rules = [
                    'form.firstname.value' => 'required|string|max:255',
                    'form.lastname.value' => 'required|string|max:255',
                    'form.phone.value' => 'required|string|max:255|unique:volunteers,phone',
                    'form.email.value' => 'required|email|max:255|unique:volunteers,email',
                    // 'hasGivenConsent.value' => 'required|accepted',
                ];
                
                $messages = [
                    'required' => 'Το πεδίο είναι υποχρεωτικό.',
                    // 'email.unique' => 'Το email πρέπει να είναι μοναδικό.',
                    // 'phone.unique' => 'Ο αριθμός τηλεφώνου πρέπει να είναι μοναδικός.',
                    'hasGivenConsent.value.required' => 'Η συγκατάθεση είναι υποχρεωτική.',
                    'hasGivenConsent.value.accepted' => 'Πρέπει να αποδεχτείτε τη συγκατάθεση.',
                ];
                
                $validatedData = $request->validate($rules, $messages);

                // Check the phone if already exists.
                // $existingVolunteer = Volunteer::firstWhere('phone', $request->phone);
                // if( $existingVolunteer ) {
                //     return back()
                //         ->withErrors([
                //             'phone' => 'Ο αριθμός τηλεφώνου υπάρχει ήδη.'
                //         ]);
                // }

                // Check if the email already exists.
                // $existingSessionRequest = Volunteer::firstWhere('email', $request->email);
                // if( $existingVolunteer ) {
                //     return back()
                //         ->withErrors([
                //             'email' => 'Το email υπάρχει ήδη.'
                //         ]);
                // }

                // --------------------------------------------------------------------
                // MAP APPLICATION TO SESSION REQUEST
                // --------------------------------------------------------------------
                $sessionData = [
                    'firstname' => $request['form']['firstname']['value'] ?? 'undefined firstname',
                    'lastname' => $request['form']['lastname']['value'] ?? 'undefined lastname',
                    'phone' => $request['form']['phone']['value'] ?? 'undefined phone',
                    'email' => $request['form']['email']['value'] ?? 'undefined email',
                ];

                $sessionRequestRequest = new Request( (array) $sessionData );     

                try {
                    $sessionRequestController = new SessionRequestController();
                    $sessionRequestController->store($sessionRequestRequest);
    
                    return Inertia::render('Applications/ApplicationSuccess', [
                        'status' => 'success',
                        'message' => 'Η αίτησή σας ολοκληρώθηκε.'
                    ]);
                } catch (\Exception $e) {
                    return Inertia::render('Error', [
                        'status' => 'error',
                        'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                        'error' => $e->getMessage()
                    ]);
                }
            case 'volunteer':
                // --------------------------------------------------------------------
                // VALIDATION
                // --------------------------------------------------------------------
                $rules = [
                    'form.firstname.value' => 'required|string|max:255',
                    'form.lastname.value' => 'required|string|max:255',
                    'form.phone.value' => 'required|string|max:255|unique:volunteers,phone',
                    'form.email.value' => 'required|email|max:255|unique:volunteers,email',
                    'form.role.value' => 'required|integer|exists:volunteer_roles,id',
                    'form.cv.value'  => 'required|string',
                    // 'form.instagram.value' => ['nullable', 'regex:/instagram/'],
                    // 'form.facebook.value' => ['nullable', 'regex:/facebook/'],
                    // 'form.linkedin.value' => ['nullable', 'regex:/linkedin/'],
                ];
                
                $messages = [
                    'required' => 'Το πεδίο είναι υποχρεωτικό.',
                    'email.unique' => 'Το email πρέπει να είναι μοναδικό.',
                    'phone.unique' => 'Ο αριθμός τηλεφώνου πρέπει να είναι μοναδικός.',
                    'hasGivenConsent.value.required' => 'Η συγκατάθεση είναι υποχρεωτική.',
                    'hasGivenConsent.value.accepted' => 'Πρέπει να αποδεχτείτε τη συγκατάθεση.',
                ];
                
                $validatedData = $request->validate($rules, $messages);

            
                // Check the phone if already exists.
                $existingVolunteer = Volunteer::firstWhere('phone', $request['form']['phone']);
                if( $existingVolunteer ) {
                    return back()
                        ->withErrors([
                            'phone' => 'Ο αριθμός τηλεφώνου υπάρχει ήδη.'
                        ]);
                }

                // Check if the email already exists.
                $existingVolunteer = Volunteer::firstWhere('email', $request['form']['email']);
                if( $existingVolunteer ) {
                    return back()
                        ->withErrors([
                            'email' => 'Το email υπάρχει ήδη.'
                        ]);
                }

                // --------------------------------------------------------------------
                // MAP APPLICATION TO VOLUNTEER
                // --------------------------------------------------------------------
                $volunteer = new \stdClass();

                $volunteer->firstname = $request['form']['firstname']['value'] ?? 'undefined firstname';
                $volunteer->lastname = $request['form']['lastname']['value'] ?? 'undefined lastname';
                $volunteer->phone = $request['form']['phone']['value'] ?? 'undefined phone';
                $volunteer->email = $request['form']['email']['value'] ?? 'undefined email';
                
                // Volunteering
                $volunteer->role = $request['form']['role']['value'] ?? 1;
                $volunteer->interests = $request['form']['interests']['value'] ?? 1;
                $volunteer->expectations = $request['form']['expectations']['value'] ?? 1;
                $volunteer->hour_per_week = $request['form']['hour_per_week']['value'] ?? 1;
                $volunteer->previous_volunteering = $request['form']['previous_volunteering']['value'] ?? 1;
                $volunteer->volunteering_details = $request['form']['volunteering_details']['value'] ?? 1;

                // Studies
                $volunteer->university = $request['form']['university']['value'] ?? 1;
                $volunteer->department = $request['form']['department']['value'] ?? 1;
                $volunteer->otherstudies = $request['form']['otherstudies']['value'] ?? 1;

                // Social Media
                $volunteer->facebook = $request['form']['facebook']['value'] ?? '';
                $volunteer->instagram = $request['form']['instagram']['value'] ?? '';
                $volunteer->tiktok = $request['form']['tiktok']['value'] ?? '';
                $volunteer->linkedin = $request['form']['linkedin']['value'] ?? '';

                // CV
                $volunteer->cv = $request['form']['cv']['value'] ?? '';
              
                // Storing the data as a structured array under relevant sections
                $volunteer->additional_info = [
                    'volunteering' => [
                        'role' => $request['form']['role']['value'] ?? 1,
                        'interests' => $request['form']['interests']['value'] ?? 1,
                        'expectations' => $request['form']['expectations']['value'] ?? 1,
                        'hour_per_week' => $request['form']['hour_per_week']['value'] ?? 1,
                        'previous_volunteering' => $request['form']['previous_volunteering']['value'] ?? 1,
                        'volunteering_details' => $request['form']['volunteering_details']['value'] ?? 1,
                    ],
                    'studies' => [
                        'university' => $request['form']['university']['value'] ?? 1,
                        'department' => $request['form']['department']['value'] ?? 1,
                        'other_studies' => $request['form']['other_studies']['value'] ?? 1,
                    ],
                    'social_media' => [
                        'facebook' => $request['form']['facebook']['value'] ?? '',
                        'instagram' => $request['form']['instagram']['value'] ?? '',
                        'tiktok' => $request['form']['tiktok']['value'] ?? '',
                        'linkedin' => $request['form']['linkedin']['value'] ?? '',
                    ],
                ];
               
                // Convert object to JSON before saving to the database
                $volunteer->additional_info = json_encode($volunteer->additional_info);
              
                $volunteerRequest = new Request( (array) $volunteer );     

                try {
                    $volunteerController = new VolunteerController( new HookService( new EmailService ) );
                    $volunteerController->store($volunteerRequest);

                    return Inertia::render('Applications/ApplicationSuccess', [
                        'status' => 'success',
                        'message' => 'Η αίτησή σας ολοκληρώθηκε.'
                    ]);
                } catch (\Exception $e) {
                    dd( $e->getMessage() );
                     
                    return Inertia::render('Error', [
                        'status' => 'error',
                        'message' => 'Ουπς, κάτι πήγε στραβά. Παρακαλούμε ξαναπροσπαθήστε.',
                        'error' => $e->getMessage()
                    ]);
                }
            default:
                return Inertia::render('Error', [
                    'status' => 'error',
                    'message' => 'Invalid form type'
                ]);
        }

        return back()->with([
            'message' => 'Η αίτηση ολοκληρώθηκε με επιτυχία!',
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = Application::query();

        $application = $query->find($id);

        return Inertia::render('Applications/Show', [
            'response' => [],
            'application' => $application
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
