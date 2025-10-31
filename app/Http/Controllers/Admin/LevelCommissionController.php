<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelReferralCommission;
use Illuminate\Http\Request;

class LevelCommissionController extends Controller
{
    public function index()
    {
        $commissions = LevelReferralCommission::orderBy('level')->get();
        return view('admin.level-commissions.index', compact('commissions'));
    }

    public function create()
    {
        // Get the next available level
        $maxLevel = LevelReferralCommission::max('level') ?? 0;
        $nextLevel = $maxLevel + 1;
        
        return view('admin.level-commissions.create', compact('nextLevel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer|min:0|unique:level_referral_commissions,level',
            'direct_percentage' => 'required|numeric|min:0|max:100',
            'level_b_percentage' => 'required|numeric|min:0|max:100',
            'level_c_percentage' => 'required|numeric|min:0|max:100',
        ]);

        LevelReferralCommission::create($validated);

        return redirect()->route('admin.level-commissions.index')
            ->with('success', 'Level commission rates created successfully.');
    }

    public function edit($id)
    {
        $commission = LevelReferralCommission::findOrFail($id);
        return view('admin.level-commissions.edit', compact('commission'));
    }

    public function update(Request $request, $id)
    {
        $commission = LevelReferralCommission::findOrFail($id);
        
        $validated = $request->validate([
            'direct_percentage' => 'required|numeric|min:0|max:100',
            'level_b_percentage' => 'required|numeric|min:0|max:100',
            'level_c_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $commission->update($validated);

        return redirect()->route('admin.level-commissions.index')
            ->with('success', 'Level commission rates updated successfully.');
    }

    public function destroy($id)
    {
        $commission = LevelReferralCommission::findOrFail($id);
        
        // Check if this level is being used by any users
        $usersCount = \App\Models\User::where('current_level', $commission->level)->count();
        
        if ($usersCount > 0) {
            return redirect()->route('admin.level-commissions.index')
                ->with('error', 'Cannot delete level commission rates. There are users assigned to this level.');
        }

        $commission->delete();

        return redirect()->route('admin.level-commissions.index')
            ->with('success', 'Level commission rates deleted successfully.');
    }

    public function show($id)
    {
        $commission = LevelReferralCommission::findOrFail($id);
        
        // Get users at this level
        $users = \App\Models\User::where('current_level', $commission->level)
            ->with(['wallet', 'investments'])
            ->paginate(10);

        // Get commission earnings for this level
        $totalCommissionEarnings = \App\Models\Transaction::where('txn_type', 'referral')
            ->whereHas('user', function($query) use ($commission) {
                $query->where('current_level', $commission->level);
            })
            ->sum('amount');

        return view('admin.level-commissions.show', compact('commission', 'users', 'totalCommissionEarnings'));
    }
}