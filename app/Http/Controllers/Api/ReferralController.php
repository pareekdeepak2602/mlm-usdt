<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function info(Request $request)
    {
        $user = $request->user();
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
        
        return response()->json([
            'success' => true,
            'data' => [
                'referral_link' => $referralLink,
                'referrals_by_level' => $referralsByLevel,
                'direct_referrals_count' => $user->direct_referrals_count,
                'team_size' => $user->team_size
            ]
        ]);
    }
    
    public function tree(Request $request)
    {
        $user = $request->user();
        $referrals = $this->getReferralTree($user->id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'referrals' => $referrals
            ]
        ]);
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
    
    public function earnings(Request $request)
    {
        $referralTransactions = $request->user()
                                       ->transactions()
                                       ->where('txn_type', 'referral')
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => [
                'referral_transactions' => $referralTransactions
            ]
        ]);
    }
}