@extends('admin.layouts.app')

@section('title', 'Edit Investment Plan - ' . $plan->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Investment Plan</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Update the investment plan details and configuration.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}">
            @csrf
            @method('PUT')
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700">Level Number *</label>
                                <input type="number" name="level" id="level" value="{{ old('level', $plan->level) }}" required min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Plan Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Level 1 - Growth">
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Investment Details -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Investment Details</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="min_investment" class="block text-sm font-medium text-gray-700">Minimum Investment *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="min_investment" id="min_investment" value="{{ old('min_investment', $plan->min_investment) }}" required step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">USDT</span>
                                    </div>
                                </div>
                                @error('min_investment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="max_investment" class="block text-sm font-medium text-gray-700">Maximum Investment</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="max_investment" id="max_investment" value="{{ old('max_investment', $plan->max_investment) }}" step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Leave empty for unlimited">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">USDT</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Leave empty for no maximum limit</p>
                                @error('max_investment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="asset_hold" class="block text-sm font-medium text-gray-700">Asset Hold Required *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="asset_hold" id="asset_hold" value="{{ old('asset_hold', $plan->asset_hold) }}" required step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">USDT</span>
                                    </div>
                                </div>
                                @error('asset_hold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Return Details -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Return Details</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="daily_percentage" class="block text-sm font-medium text-gray-700">Daily Percentage *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="daily_percentage" id="daily_percentage" value="{{ old('daily_percentage', $plan->daily_percentage) }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Daily return percentage</p>
                                @error('daily_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="duration_days" class="block text-sm font-medium text-gray-700">Duration (Days) *</label>
                                <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $plan->duration_days) }}" required min="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="365">
                                <p class="mt-1 text-xs text-gray-500">Investment duration in days</p>
                                @error('duration_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Referral Requirements -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Referral Requirements (Optional)</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="direct_referrals_required" class="block text-sm font-medium text-gray-700">Direct Referrals Required</label>
                                <input type="number" name="direct_referrals_required" id="direct_referrals_required" value="{{ old('direct_referrals_required', $plan->direct_referrals_required) }}" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Leave empty if no requirement">
                                <p class="mt-1 text-xs text-gray-500">Number of direct referrals needed</p>
                                @error('direct_referrals_required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="indirect_referrals_required" class="block text-sm font-medium text-gray-700">Indirect Referrals Required</label>
                                <input type="number" name="indirect_referrals_required" id="indirect_referrals_required" value="{{ old('indirect_referrals_required', $plan->indirect_referrals_required) }}" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Leave empty if no requirement">
                                <p class="mt-1 text-xs text-gray-500">Number of indirect referrals needed</p>
                                @error('indirect_referrals_required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Settings -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Additional Settings</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_popular" class="ml-2 block text-sm text-gray-900">
                                    Mark as Popular Plan
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">This will highlight the plan as popular on the user interface.</p>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="status" id="status" value="active" {{ old('status', $plan->status) == 'active' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="status" class="ml-2 block text-sm text-gray-900">
                                    Plan Active
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">Uncheck to make this plan inactive and unavailable for new investments.</p>
                        </div>
                    </div>

                    <!-- Plan Statistics (Read-only) -->
                    {{-- <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Plan Statistics</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Total Investments</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $plan->investments->count() }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Active Investments</p>
                                <p class="text-2xl font-semibold text-green-600">{{ $plan->investments->where('status', 'active')->count() }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Total Invested</p>
                                <p class="text-2xl font-semibold text-blue-600">{{ number_format($plan->investments->sum('amount'), 2) }} USDT</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-between items-center">
                <div>
                    <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Plans
                    </a>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Update Plan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
    <div class="max-w-sm w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
    <div class="max-w-sm w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="font-medium">Please fix the following errors:</span>
        </div>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<script>
// Auto-hide success/error messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const messages = document.querySelectorAll('.fixed');
        messages.forEach(function(message) {
            message.style.display = 'none';
        });
    }, 5000);
});

// Real-time calculation preview
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('min_investment');
    const percentageInput = document.getElementById('daily_percentage');
    const durationInput = document.getElementById('duration_days');
    
    function calculateReturns() {
        const amount = parseFloat(amountInput.value) || 0;
        const percentage = parseFloat(percentageInput.value) || 0;
        const duration = parseInt(durationInput.value) || 0;
        
        const dailyIncome = (amount * percentage / 100);
        const totalIncome = dailyIncome * duration;
        
        // You can display these calculations in a preview section if needed
        console.log('Daily Income:', dailyIncome.toFixed(2));
        console.log('Total Income:', totalIncome.toFixed(2));
    }
    
    [amountInput, percentageInput, durationInput].forEach(input => {
        input.addEventListener('input', calculateReturns);
    });
});
</script>
@endsection