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
    public $confirmation_code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$confirmation_code)
    {
        $this->user = $user;
        $this->confirmation_code = $confirmation_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->view('verifyLoginCode')
            ->with([
                'user' => $this->user,
                'code' => $this->confirmation_code,
            ]);
    }

}