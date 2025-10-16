@extends('layouts.app')

@section('page-title', 'Withdrawals')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Withdrawals</h1>
        <a href="{{ route('withdraw.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> New Withdrawal
        </a>
    </div>

    <!-- Withdrawal Information -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Withdrawal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-primary">Available Balance</h5>
                                <h3>${{ number_format($balance['available_balance'], 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-warning">Minimum Withdrawal</h5>
                                <h3>${{ number_format($minWithdrawal, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-danger">Withdrawal Fee</h5>
                                <h3>{{ $withdrawalFee }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-success">Total Withdrawn</h5>
                                <h3>${{ number_format($balance['total_withdrawn'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal History -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Withdrawal History</h6>
                </div>
                <div class="card-body">
                    @if($withdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Amount</th>
                                        <th>Fee</th>
                                        <th>Net Amount</th>
                                        <th>USDT Address</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $withdrawal)
                                        <tr>
                                            <td>#{{ $withdrawal->id }}</td>
                                            <td>${{ number_format($withdrawal->amount, 2) }}</td>
                                            <td>${{ number_format($withdrawal->fee, 2) }}</td>
                                            <td class="text-success">${{ number_format($withdrawal->net_amount, 2) }}</td>
                                            <td>{{ substr($withdrawal->usdt_address, 0, 10) }}...{{ substr($withdrawal->usdt_address, -10) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $withdrawal->status === 'completed' ? 'success' : ($withdrawal->status === 'pending' ? 'warning' : ($withdrawal->status === 'processing' ? 'info' : 'danger')) }}">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('withdrawals.show', $withdrawal->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-money-bill-wave fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No withdrawal requests yet</p>
                            <a href="{{ route('withdraw.create') }}" class="btn btn-primary">Request Withdrawal</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection