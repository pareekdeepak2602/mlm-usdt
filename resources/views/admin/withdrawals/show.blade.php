@extends('admin.layouts.app')

@section('title', 'Withdrawal Details - #' . $withdrawal->id)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Withdrawal Request Details</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete information about this withdrawal request.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.withdrawals.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Withdrawals
                </a>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:p-6">
                <!-- Status Alert -->
                <div class="mb-6">
                    @if($withdrawal->status == 'pending')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Pending Approval</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>This withdrawal request is waiting for your approval. Please review the details and process accordingly.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($withdrawal->status == 'processing')
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-cog text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Processing</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>This withdrawal is currently being processed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($withdrawal->status == 'completed')
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Completed</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>This withdrawal has been successfully processed on {{ $withdrawal->processed_at->format('M d, Y \\a\\t H:i') }}.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-times-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Rejected</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>This withdrawal request was rejected.</p>
                                    @if($withdrawal->admin_note)
                                    <p class="mt-1"><strong>Reason:</strong> {{ $withdrawal->admin_note }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Withdrawal Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Withdrawal Information</h4>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Withdrawal ID</span>
                                <span class="text-sm font-mono text-gray-900">#{{ $withdrawal->id }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Request Date</span>
                                <span class="text-sm text-gray-900">{{ $withdrawal->created_at->format('M d, Y \\a\\t H:i') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($withdrawal->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($withdrawal->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($withdrawal->status == 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif capitalize">
                                    {{ $withdrawal->status }}
                                </span>
                            </div>

                            @if($withdrawal->processed_at)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Processed Date</span>
                                <span class="text-sm text-gray-900">{{ $withdrawal->processed_at->format('M d, Y \\a\\t H:i') }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Amount Details -->
                        <div class="mt-6">
                            <h5 class="text-md font-medium text-gray-900 mb-3">Amount Details</h5>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Requested Amount</span>
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($withdrawal->amount, 2) }} USDT</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Withdrawal Fee ({{ $withdrawal->fee_percentage ?? 10 }}%)</span>
                                    <span class="text-sm text-red-600">-{{ number_format($withdrawal->fee, 2) }} USDT</span>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-500">Net Amount</span>
                                        <span class="text-lg font-bold text-green-600">{{ number_format($withdrawal->net_amount, 2) }} USDT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">User Information</h4>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($withdrawal->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-900">{{ $withdrawal->user->name }}</h5>
                                    <p class="text-sm text-gray-500">{{ $withdrawal->user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 pt-3">
                                <div>
                                    <span class="text-xs font-medium text-gray-500">User ID</span>
                                    <p class="text-sm text-gray-900">{{ $withdrawal->user->id }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Phone</span>
                                    <p class="text-sm text-gray-900">{{ $withdrawal->user->phone ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-500">KYC Status</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                        @if($withdrawal->user->kyc_status == 'approved') bg-green-100 text-green-800
                                        @elseif($withdrawal->user->kyc_status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $withdrawal->user->kyc_status }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Account Status</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                        @if($withdrawal->user->status == 'active') bg-green-100 text-green-800
                                        @elseif($withdrawal->user->status == 'inactive') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $withdrawal->user->status }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="pt-3">
                                <a href="{{ route('admin.users.show', $withdrawal->user_id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-external-link-alt mr-1"></i> View User Profile
                                </a>
                            </div>
                        </div>

                        <!-- Wallet Information -->
                        <div class="mt-6">
                            <h5 class="text-md font-medium text-gray-900 mb-3">Wallet Balance</h5>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-2">
                                @if($withdrawal->user->wallet)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Earning Balance</span>
                                    <span class="text-sm font-semibold {{ $withdrawal->user->wallet->earning_balance >= $withdrawal->amount ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($withdrawal->user->wallet->earning_balance, 2) }} USDT
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Deposit Balance</span>
                                    <span class="text-sm text-gray-900">{{ number_format($withdrawal->user->wallet->deposit_balance, 2) }} USDT</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Total Withdrawn</span>
                                    <span class="text-sm text-gray-900">{{ number_format($withdrawal->user->wallet->total_withdrawn, 2) }} USDT</span>
                                </div>
                                @else
                                <p class="text-sm text-gray-500 text-center">No wallet information available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- USDT Wallet Address -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">USDT Wallet Address</h4>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-800">Recipient Address</p>
                                <p class="text-sm font-mono text-blue-900 break-all">{{ $withdrawal->usdt_address }}</p>
                            </div>
                            <button onclick="copyToClipboard('{{ $withdrawal->usdt_address }}')" class="inline-flex items-center px-3 py-1 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-copy mr-1"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Admin Notes -->
                @if($withdrawal->admin_note)
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Admin Note</h4>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">{{ $withdrawal->admin_note }}</p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                @if($withdrawal->status == 'pending')
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Process Withdrawal</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Approve Form -->
                        <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" class="bg-green-50 border border-green-200 rounded-lg p-4">
                            @csrf
                            <div class="flex items-center mb-3">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                <div>
                                    <h5 class="text-sm font-semibold text-green-800">Approve Withdrawal</h5>
                                    <p class="text-xs text-green-600">Send {{ number_format($withdrawal->net_amount, 2) }} USDT to user's wallet</p>
                                </div>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('Are you sure you want to approve this withdrawal? This action cannot be undone.')">
                                <i class="fas fa-check mr-2"></i> Approve & Process
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" id="rejectForm" class="bg-red-50 border border-red-200 rounded-lg p-4">
                            @csrf
                            <div class="flex items-center mb-3">
                                <i class="fas fa-times-circle text-red-500 text-xl mr-3"></i>
                                <div>
                                    <h5 class="text-sm font-semibold text-red-800">Reject Withdrawal</h5>
                                    <p class="text-xs text-red-600">Reject this withdrawal request</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_note" class="block text-xs font-medium text-red-700 mb-1">Reason for rejection (required)</label>
                                <textarea name="admin_note" id="admin_note" rows="2" class="block w-full border border-red-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Provide a reason for rejection..." required></textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to reject this withdrawal?')">
                                <i class="fas fa-times mr-2"></i> Reject Withdrawal
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
        button.classList.remove('bg-white', 'text-blue-700', 'border-blue-300');
        button.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-100', 'text-green-700', 'border-green-300');
            button.classList.add('bg-white', 'text-blue-700', 'border-blue-300');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy text: ', err);
        alert('Failed to copy text to clipboard');
    });
}

// Form validation for reject form
document.addEventListener('DOMContentLoaded', function() {
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            const adminNote = document.getElementById('admin_note').value.trim();
            if (!adminNote) {
                e.preventDefault();
                alert('Please provide a reason for rejection.');
                document.getElementById('admin_note').focus();
            }
        });
    }
});
</script>

<style>
.break-all {
    word-break: break-all;
}
</style>
@endsection