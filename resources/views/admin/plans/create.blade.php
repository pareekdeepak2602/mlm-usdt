@extends('admin.layouts.app')

@section('title', 'Create Investment Plan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Create Investment Plan</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Create a new investment plan for users.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.plans.store') }}">
            @csrf
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700">Level Number</label>
                                <input type="number" name="level" id="level" value="{{ old('level') }}" required min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Plan Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Level 1 - Growth">
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
                                <label for="min_investment" class="block text-sm font-medium text-gray-700">Minimum Investment</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="min_investment" id="min_investment" value="{{ old('min_investment') }}" required step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
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
                                    <input type="number" name="max_investment" id="max_investment" value="{{ old('max_investment') }}" step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Leave empty for unlimited">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">USDT</span>
                                    </div>
                                </div>
                                @error('max_investment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="asset_hold" class="block text-sm font-medium text-gray-700">Asset Hold Required</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="asset_hold" id="asset_hold" value="{{ old('asset_hold', 0) }}" required step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
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
                                <label for="daily_percentage" class="block text-sm font-medium text-gray-700">Daily Percentage</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="daily_percentage" id="daily_percentage" value="{{ old('daily_percentage') }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                @error('daily_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="duration_days" class="block text-sm font-medium text-gray-700">Duration (Days)</label>
                                <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days') }}" required min="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="365">
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
                                <input type="number" name="direct_referrals_required" id="direct_referrals_required" value="{{ old('direct_referrals_required') }}" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Leave empty if no requirement">
                                @error('direct_referrals_required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="indirect_referrals_required" class="block text-sm font-medium text-gray-700">Indirect Referrals Required</label>
                                <input type="number" name="indirect_referrals_required" id="indirect_referrals_required" value="{{ old('indirect_referrals_required') }}" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Leave empty if no requirement">
                                @error('indirect_referrals_required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Settings -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Additional Settings</h4>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_popular" class="ml-2 block text-sm text-gray-900">
                                Mark as Popular Plan
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">This will highlight the plan as popular on the user interface.</p>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-end space-x-3">
                <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Create Plan
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Plans List
        </a>
    </div>
</div>
@endsection