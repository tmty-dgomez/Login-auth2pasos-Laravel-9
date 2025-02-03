<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $signedRoute;
    public $confirmationCode;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($signedRoute, $confirmationCode, $user)
    {
        $this->signedRoute = $signedRoute; 
        $this->user = $user;
        $this->confirmationCode = $confirmationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->subject('Code Verification')
        ->view('verifyLoginCode')
        ->with([
            'name'=>$this->user,
            'url' => $this->signedRoute,
            'code' => $this->confirmationCode,
        ]);
    }

}