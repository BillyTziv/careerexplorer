<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicEmailTemplate extends Mailable {
    use Queueable, SerializesModels;

    public function __construct($emailTemplate, $data) {
        $this->emailTemplate = $emailTemplate;
        $this->data = $data;
    }

    public function build() {
        $address = 'noreply@careerexplorer.gr';
        $content = $this->emailTemplate->body;

        // Replace placeholders with actual data
        foreach ($this->data as $key => $value) {
            $content = str_replace("{" . $key . "}", $value, $content);
        }
    
        return $this->subject($this->emailTemplate->subject)
            ->view('emails.dynamicEmailTemplate')
            ->with([
                'content' => $content,
                'subject' => $this->emailTemplate->subject
            ]);
    }
}