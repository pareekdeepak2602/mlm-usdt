<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to Smart Choice - Your Journey to Financial Freedom Begins!')
            ->view('emails.welcome')
            ->with([
                'userName' => $this->user->name,
                'referralLink' => $this->user->referral_link,
                'referralCode' => $this->user->referral_code,
            ]);
    }
}