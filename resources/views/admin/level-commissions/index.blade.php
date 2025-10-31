@extends('admin.layouts.app')

@section('title', 'Level Commission Management')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Level Commission Rates</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage commission percentages for different user levels.</p>
        </div>
        <a href="{{ route('admin.level-commissions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-plus mr-2"></i> Add New Level
        </a>
    </div>
    
    <div class="border-t border-gray-200">
        @if($commissions->isEmpty())
        <div class="px-4 py-12 text-center">
            <i class="fas fa-sitemap text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">No Level Commissions</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first level commission rates.</p>
            <div class="mt-6">
                <a href="{{ route('admin.level-commissions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Create Level Commission
                </a>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direct (A)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level B</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level C</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Earnings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($commissions as $commission)
                    @php
                        $usersCount = \App\Models\User::where('current_level', $commission->level)->count();
                        $totalEarnings = \App\Models\Transaction::where('txn_type', 'referral')
                            ->whereHas('user', function($query) use ($commission) {
                                $query->where('current_level', $commission->level);
                            })
                            ->sum('amount');
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Level {{ $commission->level }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $commission->direct_percentage }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $commission->level_b_percentage }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $commission->level_c_percentage }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $usersCount }} users
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($totalEarnings, 2) }} USDT
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.level-commissions.show', $commission->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <a href="{{ route('admin.level-commissions.edit', $commission->id) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.level-commissions.destroy', $commission->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete level {{ $commission->level }} commission rates?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<!-- Commission Structure Explanation -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Commission Structure</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Direct (A):</strong> Commission from direct referrals</li>
                    <li><strong>Level B:</strong> Commission from second-level referrals (referrals of your referrals)</li>
                    <li><strong>Level C:</strong> Commission from third-level referrals</li>
                </ul>
                <p class="mt-2">These percentages are applied to deposit amounts when users invest in the platform.</p>
            </div>
        </div>
    </div>
</div>
@endsection