<?php

namespace App\Mail;

use App\Models\WithdrawalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $user;
    public $reason;

    public function __construct(WithdrawalRequest $withdrawal, $reason)
    {
        $this->withdrawal = $withdrawal;
        $this->user = $withdrawal->user;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Withdrawal Request Update - ' . config('app.name'))
                    ->markdown('emails.withdrawals.rejected')
                    ->with([
                        'userName' => $this->user->name,
                        'amount' => $this->withdrawal->net_amount,
                        'transactionId' => $this->withdrawal->id,
                        'rejectionReason' => $this->reason,
                        'requestDate' => $this->withdrawal->created_at->format('F j, Y'),
                    ]);
    }
}