<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailAddres extends Mailable
{
    use Queueable, SerializesModels;

    public $signedUrl;

    public function __construct($signedUrl)
    {
        $this->signedUrl = $signedUrl;
    }

    public function build()
    {
        return $this->subject('Verify Your Email')->view('verify')->with([
            'url' => $this->signedUrl,
        ]);
    }
}
