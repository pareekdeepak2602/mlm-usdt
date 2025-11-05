<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $depositAmount;
    public $transactionId;
    public $depositDate;
    public $transactionHash;
    public $levelUpgrade;
    public $newLevel;
    public $dailyPercentage;
    public $referralCommission;
    public $walletBalance;
    public $referralBalance;
    public $totalBalance;
    public $dailyEarnings;
    public $dashboardUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $userName,
        $depositAmount,
        $transactionId,
        $depositDate,
        $transactionHash = null,
        $levelUpgrade = false,
        $newLevel = null,
        $dailyPercentage = null,
        $referralCommission = null,
        $walletBalance = 0,
        $referralBalance = 0,
        $totalBalance = 0,
        $dailyEarnings = 0
    ) {
        $this->userName = $userName;
        $this->depositAmount = $depositAmount;
        $this->transactionId = $transactionId;
        $this->depositDate = $depositDate;
        $this->transactionHash = $transactionHash;
        $this->levelUpgrade = $levelUpgrade;
        $this->newLevel = $newLevel;
        $this->dailyPercentage = $dailyPercentage;
        $this->referralCommission = $referralCommission;
        $this->walletBalance = $walletBalance;
        $this->referralBalance = $referralBalance;
        $this->totalBalance = $totalBalance;
        $this->dailyEarnings = $dailyEarnings;
        $this->dashboardUrl = url('/dashboard');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🎉 Deposit Confirmed - Smart Choice')
                    ->view('emails.deposit-confirmation')
                    ->with([
                        'userName' => $this->userName,
                        'depositAmount' => $this->depositAmount,
                        'transactionId' => $this->transactionId,
                        'depositDate' => $this->depositDate,
                        'transactionHash' => $this->transactionHash,
                        'levelUpgrade' => $this->levelUpgrade,
                        'newLevel' => $this->newLevel,
                        'dailyPercentage' => $this->dailyPercentage,
                        'referralCommission' => $this->referralCommission,
                        'walletBalance' => $this->walletBalance,
                        'referralBalance' => $this->referralBalance,
                        'totalBalance' => $this->totalBalance,
                        'dailyEarnings' => $this->dailyEarnings,
                        'dashboardUrl' => $this->dashboardUrl,
                    ]);
    }
}