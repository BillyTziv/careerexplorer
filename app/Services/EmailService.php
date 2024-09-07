<?php
// app/Services/EmailService.php

namespace App\Services;

use Illuminate\Http\Request;
use App\Mail\DynamicEmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate\EmailTemplate;

class EmailService
{
    public function sendTestEmail($emailTo, EmailTemplate $emailTemplate, $data)
    {
        return Mail::to($emailTo)
            ->send(
                new DynamicEmailTemplate($emailTemplate, $data)
            );
    }
}
?>