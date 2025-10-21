@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                <i class="fas fa-user-check text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Active Users</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_users'] }}</p>
            </div>
        </div>
    </div>

    <!-- Total Deposits -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                <i class="fas fa-download text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Deposits</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_deposits'], 2) }} USDT</p>
            </div>
        </div>
    </div>

    <!-- Pending Withdrawals -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 text-white mr-4">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Withdrawals</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_withdrawals'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Users</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $user->status == 'active' ? 'green' : 'gray' }}-100 text-{{ $user->status == 'active' ? 'green' : 'gray' }}-800 capitalize">
                        {{ $user->status }}
                    </span>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">View all users</a>
            </div>
        </div>
    </div>

    <!-- Recent Withdrawals -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Withdrawal Requests</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recentWithdrawals as $withdrawal)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $withdrawal->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ number_format($withdrawal->amount, 2) }} USDT</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($withdrawal->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($withdrawal->status == 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif capitalize">
                        {{ $withdrawal->status }}
                    </span>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.withdrawals.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">View all withdrawals</a>
            </div>
        </div>
    </div>
</div>
@endsection