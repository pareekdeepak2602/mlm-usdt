@extends('admin.layouts.app')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Withdrawal Requests</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage and process user withdrawal requests.</p>
    </div>
    
    <div class="border-t border-gray-200">
        @if($withdrawals->isEmpty())
        <div class="px-4 py-12 text-center">
            <i class="fas fa-money-bill-wave text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">No Withdrawal Requests</h3>
            <p class="mt-1 text-sm text-gray-500">There are no pending withdrawal requests at the moment.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $withdrawal->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $withdrawal->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-semibold">{{ number_format($withdrawal->amount, 2) }} USDT</div>
                            <div class="text-gray-500 text-xs">
                                Fee: {{ number_format($withdrawal->fee, 2) }} USDT
                            </div>
                            <div class="text-green-600 text-xs font-medium">
                                Net: {{ number_format($withdrawal->net_amount, 2) }} USDT
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono text-gray-900 truncate" style="max-width: 200px;" title="{{ $withdrawal->usdt_address }}">
                                {{ $withdrawal->usdt_address }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($withdrawal->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($withdrawal->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($withdrawal->status == 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif capitalize">
                                {{ $withdrawal->status }}
                            </span>
                            @if($withdrawal->processed_at)
                            <div class="text-xs text-gray-500 mt-1">
                                Processed: {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('M d, Y') }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $withdrawal->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Review</a>
                            
                            @if($withdrawal->status == 'pending')
                            <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Are you sure you want to process this withdrawal?')">Approve</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Statistics -->
@php
$pendingCount = $withdrawals->where('status', 'pending')->count();
$totalPendingAmount = $withdrawals->where('status', 'pending')->sum('amount');
@endphp

@if($pendingCount > 0)
<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">
                Pending Withdrawals Alert
            </h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>You have <strong>{{ $pendingCount }}</strong> pending withdrawal requests totaling <strong>{{ number_format($totalPendingAmount, 2) }} USDT</strong>.</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection