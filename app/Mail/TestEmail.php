<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $userEmail, $testResults;

    public function __construct($userEmail, $testResults) {
        $this->userEmail = $userEmail;
        $this->testResults = $testResults;
    }

    public function build() {
        $address = 'hello@futuregeneration.gr';
        $subject = 'Αποτελέσματα του Τεστ Καριέρας';
        $name = 'FutureGeneration';

        return $this->view('emails.careertestresults')
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
