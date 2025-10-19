<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'referral_code',
        'referred_by',
        'name',
        'email',
        'password',
        'phone',
        'usdt_wallet_address',
        'kyc_status',
        'kyc_document',
        'activation_amount',
        'activation_date',
        'status',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activation_amount' => 'decimal:2',
        'activation_date' => 'datetime',
        'last_login' => 'datetime',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function investments()
    {
        return $this->hasMany(UserInvestment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by', 'referral_code');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function dailyIncomes()
    {
        return $this->hasMany(DailyIncome::class);
    }

    public function leadershipBonuses()
    {
        return $this->hasMany(LeadershipBonus::class);
    }

    public function getReferralLinkAttribute()
    {
        return route('register', ['ref' => $this->referral_code]);
    }

    public function getDirectReferralsCountAttribute()
    {
        return $this->referrals()->where('level_number', 1)->count();
    }

    public function getTeamSizeAttribute()
    {
        $directReferrals = $this->referrals()->pluck('referred_id');
        $allReferrals = collect($directReferrals);
        
        foreach ($directReferrals as $referralId) {
            $referralUser = User::find($referralId);
            if ($referralUser) {
                $allReferrals = $allReferrals->merge($referralUser->getTeamSizeAttribute());
            }
        }
        
        return $allReferrals->unique()->count();
    }

    public static function generateReferralCode()
    {
        do {
            $code = 'MLM' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('referral_code', $code)->exists());
        
        return $code;
    }
    public function canInvestInPlan($plan)
{
    // Check if user meets referral requirements
    if ($plan->direct_referrals_required) {
        $directReferrals = $this->referrals()->where('level', 1)->count();
        if ($directReferrals < $plan->direct_referrals_required) {
            return false;
        }
    }
    
    if ($plan->indirect_referrals_required) {
        $indirectReferrals = $this->referrals()->where('level', '>', 1)->count();
        if ($indirectReferrals < $plan->indirect_referrals_required) {
            return false;
        }
    }
    
    // Check if user has sufficient balance for asset hold
    if ($this->wallet_balance < $plan->asset_hold) {
        return false;
    }
    
    return true;
}
// In User model, add these methods:







public function getAvailableBalanceAttribute()
{
    return $this->wallet ? ($this->wallet->deposit_balance + $this->wallet->earning_balance + $this->wallet->referral_balance) : 0;
}
}