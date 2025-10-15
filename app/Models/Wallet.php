<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'deposit_balance',
        'earning_balance',
        'referral_balance',
        'total_income',
        'total_withdrawn',
    ];

    protected $casts = [
        'deposit_balance' => 'decimal:2',
        'earning_balance' => 'decimal:2',
        'referral_balance' => 'decimal:2',
        'total_income' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvailableBalanceAttribute()
    {
        return $this->deposit_balance + $this->earning_balance + $this->referral_balance;
    }
}