@extends('admin.layouts.app')

@section('title', 'Level ' . $commission->level . ' Commission Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Level {{ $commission->level }} Commission Details</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete information about this level's commission structure.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.level-commissions.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Levels
                </a>
                <a href="{{ route('admin.level-commissions.edit', $commission->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:p-6">
                <!-- Commission Rates -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Commission Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Commission Rates</h4>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Level Number</span>
                                <span class="text-sm font-semibold text-gray-900">Level {{ $commission->level }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-green-600">Direct Referral (A)</span>
                                <span class="text-lg font-bold text-green-600">{{ $commission->direct_percentage }}%</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-blue-600">Level B</span>
                                <span class="text-lg font-bold text-blue-600">{{ $commission->level_b_percentage }}%</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-purple-600">Level C</span>
                                <span class="text-lg font-bold text-purple-600">{{ $commission->level_c_percentage }}%</span>
                            </div>
                        </div>

                        <!-- Commission Example -->
                        <div class="mt-6">
                            <h5 class="text-md font-medium text-gray-900 mb-3">Commission Example</h5>
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-3">When a user deposits <strong>100 USDT</strong>:</p>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-green-600">Direct Referral (A):</span>
                                        <span class="font-semibold">{{ number_format(100 * $commission->direct_percentage / 100, 2) }} USDT</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Level B:</span>
                                        <span class="font-semibold">{{ number_format(100 * $commission->level_b_percentage / 100, 2) }} USDT</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-purple-600">Level C:</span>
                                        <span class="font-semibold">{{ number_format(100 * $commission->level_c_percentage / 100, 2) }} USDT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Level Statistics -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Level Statistics</h4>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Total Users</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $users->total() }} users</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Total Commission Earned</span>
                                <span class="text-sm font-semibold text-green-600">{{ number_format($totalCommissionEarnings, 2) }} USDT</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Active Users</span>
                                <span class="text-sm font-semibold text-blue-600">{{ $users->where('status', 'active')->count() }} users</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">KYC Approved</span>
                                <span class="text-sm font-semibold text-green-600">{{ $users->where('kyc_status', 'approved')->count() }} users</span>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6">
                            <h5 class="text-md font-medium text-gray-900 mb-3">Quick Actions</h5>
                            <div class="grid grid-cols-1 gap-2">
                                <a href="{{ route('admin.users.index', ['level' => $commission->level]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-users mr-2"></i> View All Users
                                </a>
                                <a href="{{ route('admin.transactions.index', ['type' => 'referral', 'level' => $commission->level]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-exchange-alt mr-2"></i> View Commission Transactions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users at this Level -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Users at Level {{ $commission->level }}</h4>
                    
                    @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KYC</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission Earned</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                @php
                                    $userCommission = \App\Models\Transaction::where('user_id', $user->id)
                                        ->where('txn_type', 'referral')
                                        ->sum('amount');
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($user->status == 'active') bg-green-100 text-green-800
                                            @elseif($user->status == 'inactive') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif capitalize">
                                            {{ $user->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($user->kyc_status == 'approved') bg-green-100 text-green-800
                                            @elseif($user->kyc_status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif capitalize">
                                            {{ $user->kyc_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($userCommission, 2) }} USDT
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500">No users assigned to this level yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection