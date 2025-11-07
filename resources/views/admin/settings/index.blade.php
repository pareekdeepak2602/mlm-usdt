@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Settings</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure platform settings and parameters.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
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
                    
                    <!-- USDT Wallet Settings -->
                    <div class="border-t border-gray-200 pt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">USDT BEP20 Wallet Settings</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="usdt_bep20_wallet" class="block text-sm font-medium text-gray-700">USDT BEP20 Wallet Address</label>
                                <input type="text" name="usdt_bep20_wallet" id="usdt_bep20_wallet" value="{{ old('usdt_bep20_wallet', $settings['usdt_bep20_wallet']->setting_value ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0x...">
                                <p class="mt-1 text-sm text-gray-500">Company USDT BEP20 wallet address for deposits.</p>
                                @error('usdt_bep20_wallet')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="qr_code_image" class="block text-sm font-medium text-gray-700">QR Code Image</label>
                                <input type="file" name="qr_code_image" id="qr_code_image" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">Upload QR code for wallet address (JPEG, PNG, JPG, GIF, max 2MB).</p>
                                @error('qr_code_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <!-- Current QR Code Preview -->
                                @if(isset($settings['qr_code_image']) && $settings['qr_code_image']->setting_value)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-2">Current QR Code:</p>
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ $settings['qr_code_image']->setting_value }}" alt="QR Code" class="w-20 h-20 object-cover border rounded">
                                        <button type="button" onclick="confirmRemoveQrCode()" class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Preview Section -->
                        @if(isset($settings['usdt_bep20_wallet']))
                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h5 class="text-sm font-medium text-yellow-800 mb-3">Wallet Preview:</h5>
                            <div class="bg-white p-4 rounded border">
                                <div class="text-center mb-3">
                                    @if(isset($settings['qr_code_image']) && $settings['qr_code_image']->setting_value)
                                    <div class="qr-code-container mb-3 p-3 rounded border border-gray-300 inline-block">
                                        <img src="{{ $settings['qr_code_image']->setting_value }}" 
                                             alt="USDT BEP20 Wallet QR Code" 
                                             class="w-32 h-32 object-contain">
                                        <p class="small mt-2 mb-0 text-gray-500">Scan to Deposit</p>
                                    </div>
                                    @else
                                    <div class="text-yellow-600 text-sm mb-3">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> No QR code uploaded
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <div class="p-3 rounded border border-gray-300 bg-gray-50 inline-block">
                                        <code class="text-sm text-gray-800" id="walletAddressPreview">
                                            {{ $settings['usdt_bep20_wallet']->setting_value }}
                                        </code>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-yellow-100 text-yellow-800 text-xs">BEP20</span>
                                        <span class="text-xs text-gray-500 ml-1">Binance Smart Chain</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Send <strong>USDT BEP20</strong> only to this address
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
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

<!-- Remove QR Code Confirmation Modal -->
<div id="removeQrCodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Remove QR Code</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to remove the QR code? This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button type="button" onclick="closeRemoveQrCodeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.settings.remove-qr-code') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Remove QR Code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmRemoveQrCode() {
        document.getElementById('removeQrCodeModal').classList.remove('hidden');
    }

    function closeRemoveQrCodeModal() {
        document.getElementById('removeQrCodeModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('removeQrCodeModal');
        if (event.target === modal) {
            closeRemoveQrCodeModal();
        }
    }

    // Preview wallet address changes in real-time
    document.getElementById('usdt_bep20_wallet').addEventListener('input', function(e) {
        document.getElementById('walletAddressPreview').textContent = e.target.value;
    });
</script>
@endsection