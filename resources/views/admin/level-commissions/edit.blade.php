@extends('admin.layouts.app')

@section('title', 'Edit Level Commission - Level ' . $commission->level)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Level Commission Rates</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Update commission percentages for Level {{ $commission->level }}.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.level-commissions.update', $commission->id) }}">
            @csrf
            @method('PUT')
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Level Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Level Information</h4>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Level Number</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-2 border border-gray-300 bg-gray-50 text-gray-500 rounded-md shadow-sm sm:text-sm">
                                        Level {{ $commission->level }}
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Level number cannot be changed.</p>
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
                                    <input type="number" name="direct_percentage" id="direct_percentage" value="{{ old('direct_percentage', $commission->direct_percentage) }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                    <input type="number" name="level_b_percentage" id="level_b_percentage" value="{{ old('level_b_percentage', $commission->level_b_percentage) }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                    <input type="number" name="level_c_percentage" id="level_c_percentage" value="{{ old('level_c_percentage', $commission->level_c_percentage) }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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

                    <!-- Statistics -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Level Statistics</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $usersCount = \App\Models\User::where('current_level', $commission->level)->count() }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Total Commission Earned</p>
                                <p class="text-2xl font-semibold text-green-600">
                                    {{ number_format(\App\Models\Transaction::where('txn_type', 'referral')->whereHas('user', function($q) use ($commission) { $q->where('current_level', $commission->level); })->sum('amount'), 2) }} USDT
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500">Active Investments</p>
                                <p class="text-2xl font-semibold text-blue-600">
                                    {{ \App\Models\UserInvestment::whereHas('user', function($q) use ($commission) { $q->where('current_level', $commission->level); })->where('status', 'active')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-between items-center">
                <div>
                    <a href="{{ route('admin.level-commissions.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Levels
                    </a>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.level-commissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Update Commission Rates
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection