@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- User Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">User Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Personal details and account information.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="{{ $user->status == 'active' ? 'suspended' : 'active' }}">
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white {{ $user->status == 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas {{ $user->status == 'active' ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                        {{ $user->status == 'active' ? 'Suspend' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-3">Personal Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->phone ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">USDT Wallet</dt>
                            <dd class="text-sm text-gray-900 mt-1 font-mono">{{ $user->usdt_wallet_address ?? 'Not set' }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-3">Account Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Referral Code</dt>
                            <dd class="text-sm text-gray-900 mt-1 font-mono">{{ $user->referral_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Referred By</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                @if($user->referred_by)
                                {{ $user->referred_by }}
                                @else
                                <span class="text-gray-400">Direct registration</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($user->status == 'active') bg-green-100 text-green-800
                                    @elseif($user->status == 'inactive') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif capitalize">
                                    {{ $user->status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">KYC Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($user->kyc_status == 'approved') bg-green-100 text-green-800
                                    @elseif($user->kyc_status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif capitalize">
                                    {{ $user->kyc_status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Date</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->created_at->format('M d, Y \\a\\t H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                @if($user->last_login)
                                {{ \Carbon\Carbon::parse($user->last_login)->format('M d, Y \\a\\t H:i') }}
                                @else
                                <span class="text-gray-400">Never logged in</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Wallet Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Current balances and financial overview.</p>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            @if($user->wallet)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-blue-600">Deposit Balance</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($user->wallet->deposit_balance, 2) }}</p>
                        <p class="text-xs text-gray-500">USDT</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-green-600">Earning Balance</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($user->wallet->earning_balance, 2) }}</p>
                        <p class="text-xs text-gray-500">USDT</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-purple-600">Referral Balance</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($user->wallet->referral_balance, 2) }}</p>
                        <p class="text-xs text-gray-500">USDT</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="bg-orange-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-orange-600">Total Withdrawn</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($user->wallet->total_withdrawn, 2) }}</p>
                        <p class="text-xs text-gray-500">USDT</p>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-wallet text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500">No wallet information available</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Transactions</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest transaction history.</p>
            </div>
            <a href="{{ route('admin.users.transactions', $user->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View All Transactions
            </a>
        </div>
        
        <div class="border-t border-gray-200">
            @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaction->txn_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($transaction->txn_type == 'deposit') bg-green-100 text-green-800
                                    @elseif($transaction->txn_type == 'withdraw') bg-red-100 text-red-800
                                    @elseif($transaction->txn_type == 'income') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif capitalize">
                                    {{ $transaction->txn_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($transaction->amount, 2) }} USDT
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($transaction->status == 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status == 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif capitalize">
                                    {{ $transaction->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-4 py-12 text-center">
                <i class="fas fa-exchange-alt text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500">No transactions found</p>
            </div>
            @endif
            
            @if($transactions->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-900">
        <i class="fas fa-arrow-left mr-2"></i> Back to Users List
    </a>
</div>
@endsection