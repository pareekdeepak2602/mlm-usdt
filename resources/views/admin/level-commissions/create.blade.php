@extends('admin.layouts.app')

@section('title', 'Create Level Commission Rates')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Create Level Commission Rates</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Set commission percentages for a new user level.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.level-commissions.store') }}">
            @csrf
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Level Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Level Information</h4>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700">Level Number *</label>
                                <input type="number" name="level" id="level" value="{{ old('level', $nextLevel) }}" required min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Unique level number. Next available level is {{ $nextLevel }}.</p>
                                @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Commission Rates -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Commission Rates (%)</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="direct_percentage" class="block text-sm font-medium text-gray-700">Direct Referral (A) *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="direct_percentage" id="direct_percentage" value="{{ old('direct_percentage') }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Commission from direct referrals</p>
                                @error('direct_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="level_b_percentage" class="block text-sm font-medium text-gray-700">Level B *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="level_b_percentage" id="level_b_percentage" value="{{ old('level_b_percentage') }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Commission from second-level referrals</p>
                                @error('level_b_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="level_c_percentage" class="block text-sm font-medium text-gray-700">Level C *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="level_c_percentage" id="level_c_percentage" value="{{ old('level_c_percentage') }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Commission from third-level referrals</p>
                                @error('level_c_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Commission Example -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Commission Example</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-3">When a user deposits <strong>100 USDT</strong>:</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-green-600">Direct Referral (A):</span>
                                    <span id="directExample">0.00 USDT</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-600">Level B:</span>
                                    <span id="levelBExample">0.00 USDT</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-purple-600">Level C:</span>
                                    <span id="levelCExample">0.00 USDT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-end space-x-3">
                <a href="{{ route('admin.level-commissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Create Commission Rates
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const directInput = document.getElementById('direct_percentage');
    const levelBInput = document.getElementById('level_b_percentage');
    const levelCInput = document.getElementById('level_c_percentage');
    
    function updateCommissionExamples() {
        const depositAmount = 100; // Example deposit amount
        const directPercent = parseFloat(directInput.value) || 0;
        const levelBPercent = parseFloat(levelBInput.value) || 0;
        const levelCPercent = parseFloat(levelCInput.value) || 0;
        
        document.getElementById('directExample').textContent = (depositAmount * directPercent / 100).toFixed(2) + ' USDT';
        document.getElementById('levelBExample').textContent = (depositAmount * levelBPercent / 100).toFixed(2) + ' USDT';
        document.getElementById('levelCExample').textContent = (depositAmount * levelCPercent / 100).toFixed(2) + ' USDT';
    }
    
    [directInput, levelBInput, levelCInput].forEach(input => {
        input.addEventListener('input', updateCommissionExamples);
    });
    
    // Initial calculation
    updateCommissionExamples();
});
</script>
@endsection