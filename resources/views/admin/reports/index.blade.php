@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                    <p class="text-sm text-gray-500">{{ $activeUsers }} active</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Investments</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalInvestments }}</p>
                    <p class="text-sm text-gray-500">{{ $activeInvestments }} active</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                    <i class="fas fa-download text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Deposits</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalDeposits, 2) }} USDT</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-500 text-white mr-4">
                    <i class="fas fa-upload text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Withdrawals</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalWithdrawals, 2) }} USDT</p>
                    <p class="text-sm text-gray-500">{{ $pendingWithdrawals }} pending</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Report Sections</h3>
        </div>
        <div class="border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                <a href="{{ route('admin.reports.users') }}" class="group block p-6 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-600 text-2xl mr-4"></i>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 group-hover:text-blue-700">User Reports</h4>
                            <p class="text-sm text-gray-600">Detailed user analytics and statistics</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.reports.transactions') }}" class="group block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-exchange-alt text-green-600 text-2xl mr-4"></i>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 group-hover:text-green-700">Transaction Reports</h4>
                            <p class="text-sm text-gray-600">All transaction data and analysis</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.reports.financial') }}" class="group block p-6 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line text-purple-600 text-2xl mr-4"></i>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 group-hover:text-purple-700">Financial Reports</h4>
                            <p class="text-sm text-gray-600">Revenue, income, and financial analytics</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Growth -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">User Growth (Last 7 Days)</h3>
            </div>
            <div class="border-t border-gray-200 p-6">
                @if($userGrowth->isNotEmpty())
                <div class="space-y-3">
                    @foreach($userGrowth as $date => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $date }}</span>
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900 mr-2">{{ $count }} users</span>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($count / max($userGrowth->max(), 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No user growth data available</p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Deposit Trends (Last 7 Days)</h3>
            </div>
            <div class="border-t border-gray-200 p-6">
                @if($depositGrowth->isNotEmpty())
                <div class="space-y-3">
                    @foreach($depositGrowth as $date => $amount)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $date }}</span>
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900 mr-2">{{ number_format($amount, 2) }} USDT</span>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($amount / max($depositGrowth->max(), 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No deposit data available</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection