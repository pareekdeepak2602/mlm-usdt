@extends('layouts.app')

@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
        <div>
            @if($notifications->where('is_read', false)->count() > 0)
                <a href="{{ route('notifications.mark-all-read') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm me-2">
                    <i class="fas fa-check-double fa-sm text-white-50"></i> Mark All as Read
                </a>
            @endif
            <a href="{{ route('dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Notifications Summary -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Notifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $notifications->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unread Notifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $notifications->where('is_read', false)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Read Notifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $notifications->where('is_read', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $notifications->where('created_at', '>=', now()->startOfMonth())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">All Notifications</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filter' => '']) }}">All Notifications</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filter' => 'unread']) }}">Unread Only</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filter' => 'read']) }}">Read Only</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'bg-light' }} mb-2 border rounded">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1 me-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 {{ $notification->is_read ? 'text-gray-700' : 'text-primary' }}">
                                                    {{ $notification->title }}
                                                </h6>
                                                @if(!$notification->is_read)
                                                    <span class="badge bg-warning ms-2">New</span>
                                                @endif
                                                <span class="badge bg-{{ $notification->type === 'success' ? 'success' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'error' ? 'danger' : 'info')) }} ms-2">
                                                    {{ ucfirst($notification->type) }}
                                                </span>
                                            </div>
                                            <p class="mb-1 text-gray-600">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                                • {{ $notification->created_at->format('M d, Y \a\t h:i A') }}
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if(!$notification->is_read)
                                                <a href="{{ route('notifications.mark-read', $notification->id) }}" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   title="Mark as Read">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @else
                                                <span class="text-success" title="Read">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
                            </div>
                          <div>
    {{ $notifications->links('pagination::bootstrap-4') }}
</div>

                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-muted">No Notifications</h5>
                            <p class="text-muted">You don't have any notifications at the moment.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Types Info -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notification Types</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-info-circle fa-2x text-info"></i>
                                    </div>
                                    <h6 class="text-info">Information</h6>
                                    <p class="small text-muted">General updates and system information</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                    <h6 class="text-success">Success</h6>
                                    <p class="small text-muted">Successful transactions and approvals</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-warning h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="text-warning">Warning</h6>
                                    <p class="small text-muted">Important alerts and reminders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-danger h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="text-danger">Error</h6>
                                    <p class="small text-muted">Critical issues and failures</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.list-group-item {
    border: 1px solid #e3e6f0 !important;
    border-radius: 8px !important;
    transition: all 0.3s;
}

.list-group-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}

.bg-light {
    background-color: #f8f9fc !important;
    border-left: 4px solid #4e73df !important;
}

.badge {
    font-size: 0.7rem;
}
</style>
@endpush