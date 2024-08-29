<?php

namespace App\Http\Controllers\EmailTemplate;

use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;

use App\Models\UserManagement\User;
use App\Models\EmailTemplate\EmailTemplate;
use App\Mail\DynamicEmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailService;
use App\Services\HookService;


class EmailTemplateController extends Controller
{

    private function getEmailTemplates() {
        $query = EmailTemplate::query();

        if( request('search') ) {
            $query->where('name', 'LIKE', '%'.request('search').'%');
        }

        return $query->get();
    }

    public function index() {
        return Inertia::render('EmailTemplates/Index', [
            'response' => [],
            'filters' => [
                'search' => request('search') ? request('search') : ''
            ],
            'emailTemplates' => self::getEmailTemplates()
        ]);
    }

    public function create() {
        return Inertia::render('EmailTemplates/CreateEdit');
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255'
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.'
        ];
        
        $validatedData = $request->validate($rules, $messages);

        $emailTemplate = new EmailTemplate();
        $emailTemplate->name = $request->name;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->body = $request->body;
        $emailTemplate->placeholders = json_encode($request->placeholders);
        $emailTemplate->hook_id = $request->hook_id;
        $emailTemplate->save();
        
        return Inertia::render('EmailTemplates/Index', [
            'message' => 'Η πρότυπο email αποθηκεύτηκε με επιτυχία!',
            'status' => 'success',
            'emailTemplates' => self::getEmailTemplates()
        ]);
    }

    public function show( $id ) {
        $query = EmailTemplate::query();

        $emailTemplate = $query->find($id);

        return Inertia::render('EmailTemplates/Show', [
            'emailTemplate' => $emailTemplate
        ]);
    }

    public function edit( $id ) {
        $emailTemplate = EmailTemplate::find( $id );
        $emailTemplate->placeholders = json_decode($emailTemplate->placeholders, true);

        return Inertia::render('EmailTemplates/CreateEdit', [
            'response' => [],
            'emailTemplate' => $emailTemplate
        ]);
    }

    public function update(Request $request) {
        $emailTemplate = EmailTemplate::find( $request->id );

        if( is_null($emailTemplate) ) {
            return redirect()
                ->route( 'users' )
                ->with([
                    'message'=> 'Oups, something went wrong. Email template data was not saved.'
                ]);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255'
        ];
        
        $messages = [
            'required' => 'Το πεδίο είναι υποχρεωτικό.'
        ];
        
        $validatedData = $request->validate($rules, $messages);

        $emailTemplate->name = $request->name;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->body = $request->body;
        $emailTemplate->placeholders = json_encode($request->placeholders);
        $emailTemplate->hook_id = $request->hook_id;
        $emailTemplate->save();
        
        return back()->with([
            'message' => 'Τα στοιχεία ενημερώθηκαν με επιτυχία!',
            'status' => 'success',
        ]);
    }

    public function sendTestEmail( Request $request, EmailTemplate $emailTemplate ) {
        $hookService = new HookService();

        $hookService->trigger($request->email, 'invite_volunteer');

        //$emailService->sendTestEmail($request->email, $emailTemplate);

        //$data = ['message' => 'Αποστολή Προτύπου Email'];
        
        // Mail::to($request->email)
        //     ->send(new DynamicEmailTemplate( $emailTemplate, $data ));
    }
}
