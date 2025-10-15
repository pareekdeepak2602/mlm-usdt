<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    protected $fillable = [
        'name',
        'min_investment',
        'max_investment',
        'daily_percentage',
        'duration_days',
        'status',
    ];

    protected $casts = [
        'min_investment' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'daily_percentage' => 'decimal:2',
        'duration_days' => 'integer',
    ];

    public function userInvestments()
    {
        return $this->hasMany(UserInvestment::class);
    }

    public function getDailyIncomeAttribute($amount)
    {
        return $amount * ($this->daily_percentage / 100);
    }
}