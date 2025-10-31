<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyIncome extends Model
{
    protected $fillable = [
        'user_id',
        'investment_id',
        'amount',
        'income_date',
    ];
protected $table = 'daily_income';
    protected $casts = [
        'amount' => 'decimal:2',
        'income_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investment()
    {
        return $this->belongsTo(UserInvestment::class);
    }
}