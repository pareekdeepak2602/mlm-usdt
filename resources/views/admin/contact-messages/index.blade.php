@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Contact Messages</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">User inquiries and support requests.</p>
        </div>
        @if($unreadCount > 0)
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
            {{ $unreadCount }} unread
        </span>
        @endif
    </div>
    
    <div class="border-t border-gray-200">
        @if($messages->isEmpty())
        <div class="px-4 py-12 text-center">
            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">No Messages</h3>
            <p class="mt-1 text-sm text-gray-500">No contact messages have been received yet.</p>
        </div>
        @else
        <div class="overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach($messages as $message)
                <li class="px-6 py-4 hover:bg-gray-50 {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if(!$message->is_read)
                                <span class="inline-block h-3 w-3 rounded-full bg-blue-600"></span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $message->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $message->email }}</p>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ Str::limit($message->message, 100) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right text-sm text-gray-500">
                                <p>{{ $message->created_at->format('M d, Y') }}</p>
                                <p>{{ $message->created_at->format('H:i') }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$message->is_read)
                                <form method="POST" action="{{ route('admin.contact-messages.mark-read', $message->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.contact-messages.destroy', $message->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection