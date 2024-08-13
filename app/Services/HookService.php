<?php
// app/Services/HookService.php

namespace App\Services;

use Illuminate\Http\Request;
use App\Mail\DynamicEmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;
use App\Services\EmailService;

class HookService
{
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Trigger an email template by hook_id
     * 
     * @param hookId        : The hook_id of the email template to be triggered
     * @param emailTo       : The email address to send the email to
     * @param data          : The data to be used to replace the placeholders in the email template
     * @param placeholders  : The placeholders to be replaced in the email template
     * 
     * @return: void
     */
    public function trigger( $hookId, $emailTo, $userData )
    {
        // Get the email template by hook_id
        $emailTemplate = EmailTemplate::where('hook_id', $hookId)->first();

        if ($emailTemplate === null) {
            throw new \Exception('The provided hook_id does not exist.');
        }

        // Get the placeholders from the function argument. Try to build the array dynamically. Return any error message if not.
        // Loop throught the plcaeholders and build an association array like the followng with the correct data.
        $data = [];
        $placeholders = explode(',', json_decode($emailTemplate->placeholders, true));
        if( count($placeholders) > 0 ) {
            foreach ($placeholders as $placeholder) {
                if (isset($userData->$placeholder)) {
                    $data[$placeholder] = $userData->$placeholder;
                }
            }
        }
        
        $this->emailService->sendTestEmail($emailTo, $emailTemplate, $data);
    }
}
?>