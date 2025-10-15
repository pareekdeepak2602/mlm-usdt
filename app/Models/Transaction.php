<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateTxnId()
    {
        do {
            $txnId = 'TXN' . strtoupper(substr(md5(uniqid()), 0, 12));
        } while (self::where('txn_id', $txnId)->exists());
        
        return $txnId;
    }
}