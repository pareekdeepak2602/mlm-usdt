@extends('admin.layouts.app')

@section('title', 'Transaction Details - ' . $transaction->txn_id)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Transaction Details</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete information about this transaction.</p>
            </div>
            <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Transactions
            </a>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transaction Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-3">Transaction Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                            <dd class="text-sm text-gray-900 mt-1 font-mono">{{ $transaction->txn_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($transaction->txn_type == 'deposit') bg-green-100 text-green-800
                                    @elseif($transaction->txn_type == 'withdraw') bg-red-100 text-red-800
                                    @elseif($transaction->txn_type == 'income') bg-blue-100 text-blue-800
                                    @elseif($transaction->txn_type == 'referral') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif capitalize">
                                    {{ $transaction->txn_type }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="text-sm text-gray-900 mt-1 font-semibold">
                                {{ number_format($transaction->amount, 2) }} USDT
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($transaction->status == 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status == 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif capitalize">
                                    {{ $transaction->status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $transaction->created_at->setTimeZone('Asia/Kolkata')->format('M d, Y \\a\\t H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $transaction->updated_at->setTimeZone('Asia/Kolkata')->format('M d, Y \\a\\t H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
                
                <!-- User Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-3">User Information</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User Name</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <a href="{{ route('admin.users.show', $transaction->user_id) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $transaction->user->name }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $transaction->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $transaction->user->phone ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Referral Code</dt>
                            <dd class="text-sm text-gray-900 mt-1 font-mono">{{ $transaction->user->referral_code }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Additional Details -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-500 mb-3">Additional Details</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($transaction->details)
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $transaction->details }}</p>
                    @else
                    <p class="text-sm text-gray-500 italic">No additional details provided.</p>
                    @endif
                </div>
            </div>
            
            <!-- USDT Transaction Hash -->
            @if($transaction->usdt_txn_hash)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-500 mb-3">USDT Transaction Hash</h4>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm font-mono text-blue-700 break-all">{{ $transaction->usdt_txn_hash }}</p>
                    <p class="text-xs text-blue-600 mt-1">Blockchain transaction hash for verification</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection