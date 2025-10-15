<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'start_date',
        'end_date',
        'daily_income',
        'total_earned',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daily_income' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class);
    }

    public function dailyIncomes()
    {
        return $this->hasMany(DailyIncome::class);
    }

    public function getRemainingDaysAttribute()
    {
        $now = now();
        if ($now->greaterThan($this->end_date)) {
            return 0;
        }
        return $now->diffInDays($this->end_date) + 1;
    }

    public function getIsCompletedAttribute()
    {
        return now()->greaterThan($this->end_date) || $this->status === 'completed';
    }
}