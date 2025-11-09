@extends('admin.layouts.app')

@section('title', 'Support Inquiries')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Support Inquiries</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage all user support inquiries.</p>
            </div>
            <a href="{{ route('admin.support.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-cog mr-2"></i> Support Settings
            </a>
        </div>
        
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($inquiries as $inquiry)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">
                                                    {{ substr($inquiry->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $inquiry->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ Str::limit($inquiry->subject, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $inquiry->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $inquiry->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $inquiry->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $inquiry->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewInquiry({{ $inquiry->id }})" 
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button onclick="updateStatusModal({{ $inquiry->id }})" 
                                            class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No support inquiries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($inquiries->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $inquiries->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Inquiry Modal -->
<div id="viewInquiryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="inquirySubject"></h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <strong class="text-gray-700">From:</strong>
                        <span id="inquiryName" class="ml-2"></span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Email:</strong>
                        <span id="inquiryEmail" class="ml-2"></span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Status:</strong>
                        <span id="inquiryStatus" class="ml-2"></span>
                    </div>
                    <div>
                        <strong class="text-gray-700">Date:</strong>
                        <span id="inquiryDate" class="ml-2"></span>
                    </div>
                </div>
                <div class="mb-4">
                    <strong class="text-gray-700 block mb-2">Message:</strong>
                    <div id="inquiryMessage" class="bg-gray-50 p-4 rounded border text-gray-700"></div>
                </div>
                <div id="adminNotesSection" class="mb-4 hidden">
                    <strong class="text-gray-700 block mb-2">Admin Notes:</strong>
                    <div id="inquiryAdminNotes" class="bg-yellow-50 p-4 rounded border text-gray-700"></div>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeViewModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Update Inquiry Status</h3>
                <button onclick="closeUpdateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Add internal notes..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeUpdateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function viewInquiry(inquiryId) {
        fetch(`/admin/support/inquiries/${inquiryId}`)
            .then(response => response.json())
            .then(data => {
                const inquiry = data.inquiry;
                document.getElementById('inquirySubject').textContent = inquiry.subject;
                document.getElementById('inquiryName').textContent = inquiry.name;
                document.getElementById('inquiryEmail').textContent = inquiry.email;
                document.getElementById('inquiryStatus').textContent = inquiry.status.replace('_', ' ');
                document.getElementById('inquiryDate').textContent = new Date(inquiry.created_at).toLocaleDateString();
                document.getElementById('inquiryMessage').textContent = inquiry.message;
                
                if (inquiry.admin_notes) {
                    document.getElementById('inquiryAdminNotes').textContent = inquiry.admin_notes;
                    document.getElementById('adminNotesSection').classList.remove('hidden');
                } else {
                    document.getElementById('adminNotesSection').classList.add('hidden');
                }
                
                document.getElementById('viewInquiryModal').classList.remove('hidden');
            })
            .catch(error => console.error('Error:', error));
    }

    function updateStatusModal(inquiryId) {
        document.getElementById('updateStatusForm').action = `/admin/support/inquiries/${inquiryId}/status`;
        document.getElementById('updateStatusModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewInquiryModal').classList.add('hidden');
    }

    function closeUpdateModal() {
        document.getElementById('updateStatusModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const viewModal = document.getElementById('viewInquiryModal');
        const updateModal = document.getElementById('updateStatusModal');
        
        if (event.target === viewModal) {
            closeViewModal();
        }
        if (event.target === updateModal) {
            closeUpdateModal();
        }
    }
</script>
@endsection