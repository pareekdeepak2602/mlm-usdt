@extends('admin.layouts.app')

@section('title', 'Transaction Management')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">All Transactions</h3>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex space-x-2">
                <select name="type" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">All Types</option>
                    <option value="deposit" {{ $filters['type'] == 'deposit' ? 'selected' : '' }}>Deposit</option>
                    <option value="withdraw" {{ $filters['type'] == 'withdraw' ? 'selected' : '' }}>Withdraw</option>
                    <option value="income" {{ $filters['type'] == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="referral" {{ $filters['type'] == 'referral' ? 'selected' : '' }}>Referral</option>
                </select>
                <select name="status" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ $filters['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $filters['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ $filters['status'] == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="border-gray-300 rounded-md shadow-sm">
                <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="border-gray-300 rounded-md shadow-sm">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Filter</button>
            </form>
        </div>
    </div>
    
    <div class="border-t border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->txn_id }}</div>
                            <div class="text-sm text-gray-500 truncate" style="max-width: 200px;">{{ $transaction->details }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
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
                            {{ $transaction->created_at->setTimeZone('Asia/Kolkata')->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection