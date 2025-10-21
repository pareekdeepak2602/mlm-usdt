@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Settings</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure platform settings and parameters.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="space-y-8">
                    <!-- Financial Settings -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Financial Settings</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="minimum_withdrawal" class="block text-sm font-medium text-gray-700">Minimum Withdrawal Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="minimum_withdrawal" id="minimum_withdrawal" value="{{ old('minimum_withdrawal', $settings['minimum_withdrawal']->setting_value ?? '') }}" required step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">USDT</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Minimum amount users can withdraw.</p>
                                @error('minimum_withdrawal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="withdrawal_fee_percentage" class="block text-sm font-medium text-gray-700">Withdrawal Fee Percentage</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="withdrawal_fee_percentage" id="withdrawal_fee_percentage" value="{{ old('withdrawal_fee_percentage', $settings['withdrawal_fee_percentage']->setting_value ?? '') }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Percentage fee charged on withdrawals.</p>
                                @error('withdrawal_fee_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="minimum_activation" class="block text-sm font-medium text-gray-700">Minimum Activation Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="minimum_activation" id="minimum_activation" value="{{ old('minimum_activation', $settings['minimum_activation']->setting_value ?? '') }}" required step="0.01" min="0" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">USDT</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Minimum deposit to activate account.</p>
                                @error('minimum_activation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="referral_activation_bonus" class="block text-sm font-medium text-gray-700">Referral Activation Bonus</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="referral_activation_bonus" id="referral_activation_bonus" value="{{ old('referral_activation_bonus', $settings['referral_activation_bonus']->setting_value ?? '') }}" required step="0.01" min="0" max="100" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Bonus percentage for referral activations.</p>
                                @error('referral_activation_bonus')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Settings -->
                    <div class="border-t border-gray-200 pt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">System Settings</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="daily_income_time" class="block text-sm font-medium text-gray-700">Daily Income Calculation Time</label>
                                <input type="time" name="daily_income_time" id="daily_income_time" value="{{ old('daily_income_time', $settings['daily_income_time']->setting_value ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">Time when daily income is calculated (24-hour format).</p>
                                @error('daily_income_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="maintenance_mode" class="block text-sm font-medium text-gray-700">Maintenance Mode</label>
                                <select name="maintenance_mode" id="maintenance_mode" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="false" {{ (old('maintenance_mode', $settings['maintenance_mode']->setting_value ?? '') == 'false') ? 'selected' : '' }}>Disabled</option>
                                    <option value="true" {{ (old('maintenance_mode', $settings['maintenance_mode']->setting_value ?? '') == 'true') ? 'selected' : '' }}>Enabled</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Enable to put the platform under maintenance.</p>
                                @error('maintenance_mode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Danger Zone -->
                    {{-- <div class="border-t border-red-200 pt-8">
                        <h4 class="text-lg font-medium text-red-900 mb-4">Danger Zone</h4>
                        
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-400 text-xl mr-3"></i>
                                <div>
                                    <h5 class="text-sm font-medium text-red-800">Reset All Data</h5>
                                    <p class="text-sm text-red-600 mt-1">This will permanently delete all user data, transactions, and investments. This action cannot be undone.</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="alert('This feature is disabled in the demo.')">
                                    <i class="fas fa-trash mr-2"></i> Reset All Data
                                </button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-end space-x-3">
                <button type="reset" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Reset Changes
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection