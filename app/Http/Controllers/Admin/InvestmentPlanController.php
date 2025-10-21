<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;

class InvestmentPlanController extends Controller
{
    public function index()
    {
        $plans = InvestmentPlan::latest()->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer',
            'name' => 'required|string|max:100',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'asset_hold' => 'required|numeric|min:0',
            'direct_referrals_required' => 'nullable|integer',
            'indirect_referrals_required' => 'nullable|integer',
            'daily_percentage' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_popular' => 'boolean',
        ]);

        InvestmentPlan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Investment plan created successfully.');
    }

    public function edit($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        
        $validated = $request->validate([
            'level' => 'required|integer',
            'name' => 'required|string|max:100',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'asset_hold' => 'required|numeric|min:0',
            'direct_referrals_required' => 'nullable|integer',
            'indirect_referrals_required' => 'nullable|integer',
            'daily_percentage' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_popular' => 'boolean',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Investment plan updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $plan->update(['status' => $request->status]);

        return back()->with('success', 'Plan status updated successfully.');
    }

    public function destroy($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        
        // Check if plan has active investments
        if ($plan->investments()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete plan with active investments.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Investment plan deleted successfully.');
    }
}