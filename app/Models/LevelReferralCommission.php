<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelReferralCommission extends Model
{
    use HasFactory;

    protected $table = 'level_referral_commissions';

    protected $fillable = [
        'level',
        'direct_percentage',
        'level_b_percentage', 
        'level_c_percentage'
    ];

    public $timestamps = true;
}