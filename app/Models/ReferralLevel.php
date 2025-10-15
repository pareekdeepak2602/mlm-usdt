<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralLevel extends Model
{
    protected $fillable = [
        'level_number',
        'level_name',
        'percentage',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
    ];

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'level_number');
    }
}