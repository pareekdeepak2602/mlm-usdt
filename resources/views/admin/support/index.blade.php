@extends('admin.layouts.app')

@section('title', 'Support Settings')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Support Settings</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage support contact information and links.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.support.settings.update') }}">
            @csrf
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="space-y-8">
                    <!-- Contact Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Support Email</label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email', $supportSettings['contact']->where('key', 'email')->first()->value ?? '') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">Primary support email address.</p>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Support Phone</label>
                                <input type="text" name="phone" id="phone" 
                                       value="{{ old('phone', $supportSettings['contact']->where('key', 'phone')->first()->value ?? '') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">Support phone number.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="border-t border-gray-200 pt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Social Media & Messaging</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" id="whatsapp_number" 
                                       value="{{ old('whatsapp_number', $supportSettings['contact']->where('key', 'whatsapp_number')->first()->value ?? '') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="1234567890">
                                <p class="mt-1 text-sm text-gray-500">WhatsApp number for support (without country code).</p>
                            </div>
                            
                            <div>
                                <label for="telegram_link" class="block text-sm font-medium text-gray-700">Telegram Group Link</label>
                                <input type="url" name="telegram_link" id="telegram_link" 
                                       value="{{ old('telegram_link', $supportSettings['contact']->where('key', 'telegram_link')->first()->value ?? '') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="https://t.me/yourgroup">
                                <p class="mt-1 text-sm text-gray-500">Telegram group or channel link.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Support Information -->
                    <div class="border-t border-gray-200 pt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Support Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="response_time" class="block text-sm font-medium text-gray-700">Response Time</label>
                                <input type="text" name="response_time" id="response_time" 
                                       value="{{ old('response_time', $supportSettings['contact']->where('key', 'response_time')->first()->value ?? '') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="24-48 hours">
                                <p class="mt-1 text-sm text-gray-500">Expected response time for inquiries.</p>
                            </div>
                            
                            <div>
                                <label for="working_hours" class="block text-sm font-medium text-gray-700">Working Hours</label>
                                <input type="text" name="working_hours" id="working_hours" 
                                       value="{{ old('working_hours', $supportSettings['contact']->where('key', 'working_hours')->first()->value ?? '') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="24/7">
                                <p class="mt-1 text-sm text-gray-500">Support working hours.</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="support_type" class="block text-sm font-medium text-gray-700">Support Type</label>
                                <input type="text" name="support_type" id="support_type" 
                                       value="{{ old('support_type', $supportSettings['contact']->where('key', 'support_type')->first()->value ?? '') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Technical & Account Support">
                                <p class="mt-1 text-sm text-gray-500">Types of support provided.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-end space-x-3">
                <a href="{{ route('admin.support.inquiries') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-list mr-2"></i> View Inquiries
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection