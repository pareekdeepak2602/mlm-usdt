<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'txn_id',
        'txn_type',
        'amount',
        'usdt_txn_hash',
        'status',
        'details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate unique transaction ID
     */
    public static function generateTxnId()
    {
        do {
            $txnId = 'TXN' . strtoupper(substr(md5(uniqid() . microtime()), 0, 12));
        } while (self::where('txn_id', $txnId)->exists());
        
        return $txnId;
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for deposit transactions
     */
    public function scopeDeposits($query)
    {
        return $query->where('txn_type', 'deposit');
    }

    /**
     * Scope for withdrawal transactions
     */
    public function scopeWithdrawals($query)
    {
        return $query->where('txn_type', 'withdraw');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get transaction type badge class
     */
    public function getTypeBadgeClassAttribute()
    {
        $classes = [
            'deposit' => 'bg-success',
            'withdraw' => 'bg-warning',
            'income' => 'bg-info',
            'referral' => 'bg-primary',
            'level_bonus' => 'bg-purple',
            'leadership_bonus' => 'bg-orange',
            'withdrawal_fee' => 'bg-danger',
            'bonus' => 'bg-pink',
        ];

        return $classes[$this->txn_type] ?? 'bg-secondary';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-warning',
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'cancelled' => 'bg-secondary',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }
}