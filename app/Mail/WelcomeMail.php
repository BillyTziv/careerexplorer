<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable {
    use Queueable, SerializesModels;
    public $userEmail, $testResults;

    public function __construct($userEmail, $testResults) {
        $this->userEmail = $userEmail;
        $this->testResults = $testResults;
    }

    public function build() {
        $address = 'hello@futuregeneration.gr';
        $subject = 'Welcome to FutureGeneration';
        $name = 'FutureGeneration';

        return $this->view('emails.welcome')
            ->from($address, $name)
            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with([
                'testResults' => $this->testResults,
                'userEmail' => $this->userEmail
            ]);
    }
}