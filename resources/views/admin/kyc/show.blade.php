@extends('admin.layouts.app')

@section('title', 'Review KYC - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">KYC Verification - {{ $user->name }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Review user identity document and approve or reject.</p>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <!-- User Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Personal Information</h4>
                    <dl class="mt-2 space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Date</dt>
                            <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Account Status</h4>
                    <dl class="mt-2 space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                            <dd class="text-sm">
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
                            <dd class="text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($user->kyc_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($user->kyc_status == 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif capitalize">
                                    {{ $user->kyc_status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Referral Code</dt>
                            <dd class="text-sm text-gray-900">{{ $user->referral_code }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Document -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-500 mb-3">Uploaded Document</h4>
                @if($user->kyc_document)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Identity Document</p>
                            <p class="text-sm text-gray-500">Uploaded on {{ $user->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                        <a href="{{ asset('storage/' . $user->kyc_document) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-eye mr-2"></i>View Document
                        </a>
                    </div>
                </div>
                @else
                <div class="border rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-500">No document uploaded</p>
                </div>
                @endif
            </div>
            
            <!-- Action Buttons -->
            @if($user->kyc_status == 'pending')
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <form method="POST" action="{{ route('admin.kyc.reject', $user->id) }}" class="inline">
                    @csrf
                    <div class="flex items-center space-x-3">
                        <input type="text" name="rejection_reason" placeholder="Rejection reason..." required class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to reject this KYC?')">
                            Reject KYC
                        </button>
                    </div>
                </form>
                
                <form method="POST" action="{{ route('admin.kyc.approve', $user->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('Are you sure you want to approve this KYC?')">
                        Approve KYC
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.kyc.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to KYC List
        </a>
    </div>
</div>
@endsection