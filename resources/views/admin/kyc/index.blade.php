@extends('admin.layouts.app')

@section('title', 'KYC Verification')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">KYC Verification Requests</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Review and verify user identity documents.</p>
    </div>
    
    <div class="border-t border-gray-200">
        @if($users->isEmpty())
        <div class="px-4 py-12 text-center">
            <i class="fas fa-check-circle text-green-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">No Pending KYC Requests</h3>
            <p class="mt-1 text-sm text-gray-500">All KYC requests have been processed.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->updated_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($user->kyc_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($user->kyc_status == 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif capitalize">
                                {{ $user->kyc_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.kyc.show', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection