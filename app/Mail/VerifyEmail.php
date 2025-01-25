<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $confirmationCode;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $confirmationCode)
    {
        $this->user = $user;
        $this->confirmationCode = $confirmationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->subject('Email Verification')
        ->view('verifyLoginCode')
        ->with([
            'user' => $this->user,
            'code' => $this->confirmationCode,
        ]);
    }

}