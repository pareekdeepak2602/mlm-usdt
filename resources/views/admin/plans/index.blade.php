@extends('admin.layouts.app')

@section('title', 'Investment Plans')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Investment Plans</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage investment plans and their configurations.</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-plus mr-2"></i> Create New Plan
        </a>
    </div>
    
    <div class="border-t border-gray-200">
        @if($plans->isEmpty())
        <div class="px-4 py-12 text-center">
            <i class="fas fa-chart-pie text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">No Investment Plans</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first investment plan.</p>
            <div class="mt-6">
                <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Create Plan
                </a>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Investment Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Return</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requirements</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($plans as $plan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($plan->is_popular)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                    Popular
                                </span>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                    <div class="text-sm text-gray-500">Level {{ $plan->level }}</div>
                                    <div class="text-sm text-gray-500">{{ $plan->duration_days }} days</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>Min: {{ number_format($plan->min_investment, 2) }} USDT</div>
                            <div>Max: {{ $plan->max_investment ? number_format($plan->max_investment, 2) . ' USDT' : 'Unlimited' }}</div>
                            <div class="text-gray-500">Asset: {{ number_format($plan->asset_hold, 2) }} USDT</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="text-lg font-semibold text-green-600">{{ $plan->daily_percentage }}%</div>
                            <div class="text-gray-500">daily</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($plan->direct_referrals_required)
                            <div>Direct: {{ $plan->direct_referrals_required }}</div>
                            <div>Indirect: {{ $plan->indirect_referrals_required }}</div>
                            @else
                            <span class="text-gray-400">No requirements</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" action="{{ route('admin.plans.status', $plan->id) }}" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 
                                    @if($plan->status == 'active') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <option value="active" {{ $plan->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $plan->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.plans.edit', $plan->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan? This action cannot be undone.')">
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
@endsection