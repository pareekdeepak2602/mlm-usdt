<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $referralLink = $user->referral_link;
        $referrals = $user->referrals()->with('referred')->get();
        
        // Group referrals by level
        $referralsByLevel = [];
        foreach ($referrals as $referral) {
            $level = $referral->level_number;
            if (!isset($referralsByLevel[$level])) {
                $referralsByLevel[$level] = [];
            }
            $referralsByLevel[$level][] = $referral;
        }
       
        return view('referrals.index', compact('referralLink', 'referralsByLevel', 'referrals'));
    }
    
    public function tree()
    {
        $user = Auth::user();
        $referrals = $this->getReferralTree($user->id);
        
        return view('referrals.tree', compact('referrals'));
    }
    
    private function getReferralTree($userId, $level = 0)
    {
        if ($level > 3) {
            return [];
        }
        
        $user = User::find($userId);
        $referrals = $user->referrals()->with('referred')->get();
        
        $tree = [];
        foreach ($referrals as $referral) {
            $tree[] = [
                'user' => $referral->referred,
                'level' => $referral->level_number,
                'children' => $this->getReferralTree($referral->referred_id, $level + 1),
            ];
        }
        
        return $tree;
    }
    
    public function earnings()
    {
        $user = Auth::user();
        $referralTransactions = $user->transactions()
                                     ->where('txn_type', 'referral')
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(20);
        
        return view('referrals.earnings', compact('referralTransactions'));
    }
}