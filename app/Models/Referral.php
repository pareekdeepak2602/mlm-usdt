<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'level_number',
        'bonus_amount',
        'status',
    ];

    protected $casts = [
        'bonus_amount' => 'decimal:2',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function level()
    {
        return $this->belongsTo(ReferralLevel::class, 'level_number');
    }
}