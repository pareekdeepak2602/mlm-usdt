<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Reset Your Password - Smart Choice MLM Platform')
                    ->view('emails.reset-password')
                    ->with([
                        'token' => $this->token,
                        'email' => $this->email,
                        'resetUrl' => url('/reset-password/' . $this->token)
                    ]);
    }
}