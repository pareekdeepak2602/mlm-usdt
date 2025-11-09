@extends('admin.layouts.app')

@section('title', 'Support Settings')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Support Settings Form -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Support & Contact Settings</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure support channels and contact information.</p>
                </div>
                
                <form method="POST" action="{{ route('admin.support.settings.update') }}">
                    @csrf
                    
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                        <div class="space-y-6">
                            <!-- Contact Information -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-4">Contact Information</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="support_email" class="block text-sm font-medium text-gray-700">Support Email</label>
                                        <input type="email" name="support_email" id="support_email" 
                                               value="{{ old('support_email', $settings['support_email']->value ?? '') }}" 
                                               required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="support_phone" class="block text-sm font-medium text-gray-700">Support Phone</label>
                                        <input type="text" name="support_phone" id="support_phone" 
                                               value="{{ old('support_phone', $settings['support_phone']->value ?? '') }}" 
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- WhatsApp Settings -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">WhatsApp Support</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                                        <input type="text" name="whatsapp_number" id="whatsapp_number" 
                                               value="{{ old('whatsapp_number', $settings['whatsapp_number']->value ?? '') }}" 
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="+1234567890">
                                    </div>
                                    
                                    <div>
                                        <label for="whatsapp_group_link" class="block text-sm font-medium text-gray-700">WhatsApp Group Link</label>
                                        <input type="url" name="whatsapp_group_link" id="whatsapp_group_link" 
                                               value="{{ old('whatsapp_group_link', $settings['whatsapp_group_link']->value ?? '') }}" 
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="https://chat.whatsapp.com/...">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Telegram Settings -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Telegram Support</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="telegram_group_link" class="block text-sm font-medium text-gray-700">Telegram Group Link</label>
                                        <input type="url" name="telegram_group_link" id="telegram_group_link" 
                                               value="{{ old('telegram_group_link', $settings['telegram_group_link']->value ?? '') }}" 
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="https://t.me/...">
                                    </div>
                                    
                                    <div>
                                        <label for="telegram_channel_link" class="block text-sm font-medium text-gray-700">Telegram Channel Link</label>
                                        <input type="url" name="telegram_channel_link" id="telegram_channel_link" 
                                               value="{{ old('telegram_channel_link', $settings['telegram_channel_link']->value ?? '') }}" 
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="https://t.me/...">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Support Details -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Support Details</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="support_working_hours" class="block text-sm font-medium text-gray-700">Working Hours</label>
                                        <input type="text" name="support_working_hours" id="support_working_hours" 
                                               value="{{ old('support_working_hours', $settings['support_working_hours']->value ?? '') }}" 
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="Mon-Fri 9:00 AM - 6:00 PM">
                                    </div>
                                    
                                    <div>
                                        <label for="response_time" class="block text-sm font-medium text-gray-700">Expected Response Time</label>
                                        <input type="text" name="response_time" id="response_time" 
                                               value="{{ old('response_time', $settings['response_time']->value ?? '') }}" 
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="Within 24 hours">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Recent Inquiries Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Support Inquiries</h3>
                </div>
                
                <div class="border-t border-gray-200">
                    <div class="flow-root">
                        <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @forelse($inquiries as $inquiry)
                            <li class="px-4 py-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $inquiry->subject }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            By: {{ $inquiry->user->name }}
                                        </p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $inquiry->getStatusColor() }}-100 text-{{ $inquiry->getStatusColor() }}-800">
                                                {{ ucfirst($inquiry->status) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $inquiry->getPriorityColor() }}-100 text-{{ $inquiry->getPriorityColor() }}-800">
                                                {{ ucfirst($inquiry->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ route('admin.support.inquiries.show', $inquiry->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="px-4 py-8 text-center">
                                <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500 text-sm">No support inquiries yet</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    
                    @if($inquiries->hasPages())
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                        <div class="flex justify-between text-sm">
                            <a href="{{ route('admin.support.inquiries') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                View All Inquiries
                            </a>
                            <span class="text-gray-500">
                                Showing {{ $inquiries->firstItem() }}-{{ $inquiries->lastItem() }} of {{ $inquiries->total() }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection