@extends('admin.layouts.app')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="space-y-6">
    <!-- Blockchain Status Alert -->
    @if(!$blockchainAvailable)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Blockchain Service Unavailable</h3>
                <p class="text-sm text-red-700 mt-1">
                    The blockchain withdrawal service is currently unavailable. Withdrawals cannot be processed until the service is restored.
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">Blockchain Service Online</h3>
                <p class="text-sm text-green-700 mt-1">
                    The blockchain withdrawal service is available and ready to process withdrawals.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalPending }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($totalPendingAmount, 2) }} USDT</p>
                    <p class="text-xs text-gray-400">Net Amount to Send</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                    <i class="fas fa-cog text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Processing</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalProcessing }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($totalProcessingAmount, 2) }} USDT</p>
                    <p class="text-xs text-gray-400">Net Amount to Send</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCompleted }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-500 text-white mr-4">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalRejected }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-500 text-white mr-4">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Failed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalFailed }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Bulk Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Withdrawal Requests</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage and process user withdrawal requests.</p>
                </div>
                
                <!-- Bulk Actions -->
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="flex flex-wrap gap-2">
                        <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="pending" {{ $filters['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $filters['status'] == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $filters['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ $filters['status'] == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="failed" {{ $filters['status'] == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        
                        <input type="date" name="date_from" value="{{ $filters['date_from'] }}" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="From Date">
                        
                        <input type="date" name="date_to" value="{{ $filters['date_to'] }}" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="To Date">
                        
                        <input type="text" name="user_id" value="{{ $filters['user_id'] }}" 
                               class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="User ID">
                        
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter
                        </button>
                        
                        <a href="{{ route('admin.withdrawals.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            @if($withdrawals->isEmpty())
            <div class="px-4 py-12 text-center">
                <i class="fas fa-money-bill-wave text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No Withdrawal Requests</h3>
                <p class="mt-1 text-sm text-gray-500">There are no withdrawal requests matching your criteria.</p>
            </div>
            @else
            <!-- Bulk Action Form -->
            <form method="POST" action="{{ route('admin.withdrawals.bulk-action') }}" id="bulkActionForm" class="hidden">
                @csrf
                <input type="hidden" name="action" id="bulkAction">
                <input type="hidden" name="admin_note" id="bulkAdminNote">
                <div id="selectedWithdrawals"></div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ !$blockchainAvailable ? 'disabled' : '' }}>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">USDT Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($withdrawals as $withdrawal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($withdrawal->status == 'pending' && $blockchainAvailable)
                                <input type="checkbox" name="withdrawal_ids[]" value="{{ $withdrawal->id }}" class="withdrawal-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                @elseif($withdrawal->status == 'pending')
                                <input type="checkbox" disabled title="Blockchain service unavailable" class="rounded border-gray-300 text-gray-400">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $withdrawal->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $withdrawal->user->email }}</div>
                                        <div class="text-xs text-gray-400">ID: {{ $withdrawal->user->id }} | Level: {{ $withdrawal->user->current_level }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-semibold">{{ number_format($withdrawal->amount, 2) }} USDT</div>
                                <div class="text-gray-500 text-xs">
                                    Fee: {{ number_format($withdrawal->fee, 2) }} USDT
                                </div>
                                <div class="text-green-600 text-xs font-medium">
                                    Net to Send: {{ number_format($withdrawal->net_amount, 2) }} USDT
                                </div>
                                @if($withdrawal->tx_hash)
                                <div class="text-blue-600 text-xs mt-1">
                                    <i class="fas fa-link mr-1"></i>
                                    <a href="https://bscscan.com/tx/{{ $withdrawal->tx_hash }}" target="_blank" class="hover:underline">
                                        TX: {{ \Str::limit($withdrawal->tx_hash, 10) }}
                                    </a>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-900 truncate" style="max-width: 200px;" title="{{ $withdrawal->usdt_address }}">
                                    {{ $withdrawal->usdt_address }}
                                </div>
                                <button onclick="copyToClipboard('{{ $withdrawal->usdt_address }}')" class="text-xs text-blue-600 hover:text-blue-900 mt-1">
                                    <i class="fas fa-copy mr-1"></i> Copy
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($withdrawal->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($withdrawal->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($withdrawal->status == 'completed') bg-green-100 text-green-800
                                    @elseif($withdrawal->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($withdrawal->status == 'failed') bg-orange-100 text-orange-800
                                    @endif capitalize">
                                    @if($withdrawal->status == 'processing')
                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                    @endif
                                    {{ $withdrawal->status }}
                                </span>
                                @if($withdrawal->processed_at)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $withdrawal->processed_at->format('M d, Y H:i') }}
                                </div>
                                @endif
                                @if($withdrawal->status == 'failed' && $withdrawal->admin_note)
                                <div class="text-xs text-red-500 mt-1" title="{{ $withdrawal->admin_note }}">
                                    {{ \Str::limit($withdrawal->admin_note, 50) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $withdrawal->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Review</a>
                                
                                @if($withdrawal->status == 'pending')
                                    @if($blockchainAvailable)
                                    <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2" onclick="return confirm('Approve this withdrawal? You need to send {{ number_format($withdrawal->net_amount, 2) }} USDT to user\\'s wallet.')">Approve</button>
                                    </form>
                                    <button type="button" onclick="showRejectModal({{ $withdrawal->id }})" class="text-red-600 hover:text-red-900">Reject</button>
                                    @else
                                    <span class="text-gray-400 text-xs" title="Blockchain service unavailable">Approve</span>
                                    <span class="text-gray-400 text-xs ml-2" title="Blockchain service unavailable">Reject</span>
                                    @endif
                                @elseif($withdrawal->status == 'failed')
                                    @if($blockchainAvailable)
                                    <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2" onclick="return confirm('Retry this failed withdrawal? You need to send {{ number_format($withdrawal->net_amount, 2) }} USDT to user\\'s wallet.')">Retry</button>
                                    </form>
                                    @else
                                    <span class="text-gray-400 text-xs" title="Blockchain service unavailable">Retry</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                <div class="text-sm text-gray-700">
                    Showing {{ $withdrawals->firstItem() }} to {{ $withdrawals->lastItem() }} of {{ $withdrawals->total() }} results
                </div>
                <div>
                    {{ $withdrawals->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Bulk Actions Panel (Visible when selections made) -->
    <div id="bulkActionsPanel" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-sm font-medium text-yellow-800">
                    <span id="selectedCount">0</span> withdrawal(s) selected
                </h4>
                <p class="text-sm text-yellow-700 mt-1">
                    Total amount to deduct from users: <strong id="totalAmount">0.00</strong> USDT
                </p>
                <p class="text-sm text-green-700">
                    Total amount to send to users: <strong id="totalNetAmount">0.00</strong> USDT
                </p>
            </div>
            <div class="flex space-x-2">
                <button type="button" onclick="bulkApprove()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 {{ !$blockchainAvailable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$blockchainAvailable ? 'disabled' : '' }}>
                    <i class="fas fa-check mr-2"></i> Approve Selected
                </button>
                <button type="button" onclick="showBulkRejectModal()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i> Reject Selected
                </button>
                <button type="button" onclick="clearSelection()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i> Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal for Single Withdrawal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Withdrawal</h3>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="mb-4">
                    <label for="reject_note" class="block text-sm font-medium text-gray-700">Reason for rejection *</label>
                    <textarea name="admin_note" id="reject_note" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        Reject Withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Reject Modal -->
<div id="bulkRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Selected Withdrawals</h3>
            <form>
                <div class="mb-4">
                    <label for="bulk_reject_note" class="block text-sm font-medium text-gray-700">Reason for rejection *</label>
                    <textarea name="admin_note" id="bulk_reject_note" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBulkRejectModal()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" onclick="bulkReject()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        Reject Selected
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentWithdrawalId = null;
let selectedWithdrawals = new Set();
let withdrawalAmounts = {};
let withdrawalNetAmounts = {};

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Address copied to clipboard!');
    });
}

// Selection management
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.withdrawal-checkbox');
    
    // Initialize withdrawal amounts
    checkboxes.forEach(checkbox => {
        const withdrawalId = checkbox.value;
        const row = checkbox.closest('tr');
        const amountText = row.querySelector('.font-semibold').textContent;
        const netAmountText = row.querySelector('.text-green-600').textContent;
        
        const amount = parseFloat(amountText.replace(' USDT', '').replace(',', ''));
        const netAmount = parseFloat(netAmountText.replace('Net to Send: ', '').replace(' USDT', '').replace(',', ''));
        
        withdrawalAmounts[withdrawalId] = amount;
        withdrawalNetAmounts[withdrawalId] = netAmount;
    });
    
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = this.checked;
                if (this.checked) {
                    selectedWithdrawals.add(checkbox.value);
                } else {
                    selectedWithdrawals.delete(checkbox.value);
                }
            }
        });
        updateBulkActionsPanel();
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                selectedWithdrawals.add(this.value);
            } else {
                selectedWithdrawals.delete(this.value);
                selectAll.checked = false;
            }
            updateBulkActionsPanel();
        });
    });
});

function updateBulkActionsPanel() {
    const panel = document.getElementById('bulkActionsPanel');
    const selectedCount = document.getElementById('selectedCount');
    const totalAmount = document.getElementById('totalAmount');
    const totalNetAmount = document.getElementById('totalNetAmount');
    
    if (selectedWithdrawals.size > 0) {
        let total = 0;
        let totalNet = 0;
        
        selectedWithdrawals.forEach(id => {
            total += withdrawalAmounts[id] || 0;
            totalNet += withdrawalNetAmounts[id] || 0;
        });
        
        selectedCount.textContent = selectedWithdrawals.size;
        totalAmount.textContent = total.toFixed(2);
        totalNetAmount.textContent = totalNet.toFixed(2);
        panel.classList.remove('hidden');
    } else {
        panel.classList.add('hidden');
    }
}

function clearSelection() {
    selectedWithdrawals.clear();
    document.querySelectorAll('.withdrawal-checkbox').forEach(cb => {
        if (!cb.disabled) cb.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActionsPanel();
}

// Modal functions
function showRejectModal(withdrawalId) {
    currentWithdrawalId = withdrawalId;
    const form = document.getElementById('rejectForm');
    form.action = `{{ url('admin/withdrawals') }}/${withdrawalId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    currentWithdrawalId = null;
}

function showBulkRejectModal() {
    document.getElementById('bulkRejectModal').classList.remove('hidden');
}

function closeBulkRejectModal() {
    document.getElementById('bulkRejectModal').classList.add('hidden');
}

// Bulk actions
function bulkApprove() {
    if (selectedWithdrawals.size === 0) return;
    
    const totalAmount = Array.from(selectedWithdrawals).reduce((sum, id) => sum + (withdrawalAmounts[id] || 0), 0);
    const totalNetAmount = Array.from(selectedWithdrawals).reduce((sum, id) => sum + (withdrawalNetAmounts[id] || 0), 0);
    
    if (confirm(`Approve ${selectedWithdrawals.size} withdrawal(s)?\n\nTotal amount to deduct from users: ${totalAmount.toFixed(2)} USDT\nTotal amount to send to users: ${totalNetAmount.toFixed(2)} USDT`)) {
        document.getElementById('bulkAction').value = 'approve';
        document.getElementById('bulkAdminNote').value = 'Bulk approved - ' + new Date().toLocaleString();
        updateSelectedWithdrawalsInput();
        document.getElementById('bulkActionForm').submit();
    }
}

function bulkReject() {
    const note = document.getElementById('bulk_reject_note').value.trim();
    if (!note) {
        alert('Please provide a reason for rejection.');
        return;
    }
    
    document.getElementById('bulkAction').value = 'reject';
    document.getElementById('bulkAdminNote').value = note;
    updateSelectedWithdrawalsInput();
    document.getElementById('bulkActionForm').submit();
}

function updateSelectedWithdrawalsInput() {
    const container = document.getElementById('selectedWithdrawals');
    container.innerHTML = '';
    selectedWithdrawals.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'withdrawals[]';
        input.value = id;
        container.appendChild(input);
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    const rejectModal = document.getElementById('rejectModal');
    const bulkRejectModal = document.getElementById('bulkRejectModal');
    
    if (event.target === rejectModal) {
        closeRejectModal();
    }
    if (event.target === bulkRejectModal) {
        closeBulkRejectModal();
    }
}

// Auto-refresh blockchain status every 30 seconds
setInterval(function() {
    fetch('{{ route("admin.withdrawals.blockchain-status") }}')
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                document.querySelector('[class*="bg-red-50"]')?.classList.add('hidden');
                document.querySelector('[class*="bg-green-50"]')?.classList.remove('hidden');
            } else {
                document.querySelector('[class*="bg-green-50"]')?.classList.add('hidden');
                document.querySelector('[class*="bg-red-50"]')?.classList.remove('hidden');
            }
        })
        .catch(error => console.error('Failed to check blockchain status:', error));
}, 30000);
</script>
@endsection