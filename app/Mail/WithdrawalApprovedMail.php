<?php

namespace App\Mail;

use App\Models\WithdrawalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $user;

    public function __construct(WithdrawalRequest $withdrawal)
    {
        $this->withdrawal = $withdrawal;
        $this->user = $withdrawal->user;
    }

    public function build()
    {
        return $this->subject('Withdrawal Request Approved - ' . config('app.name'))
                    ->markdown('emails.withdrawals.approved')
                    ->with([
                        'userName' => $this->user->name,
                        'amount' => $this->withdrawal->net_amount,
                        'transactionId' => $this->withdrawal->id,
                        'processedDate' => $this->withdrawal->processed_at->format('F j, Y \a\t g:i A'),
                        'usdtAddress' => $this->withdrawal->usdt_address,
                    ]);
    }
}