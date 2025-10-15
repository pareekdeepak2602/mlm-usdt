@extends('layouts.app')

@section('page-title', 'Create Investment')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Investment - {{ $plan->name }}</h1>
        <a href="{{ route('investments.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Investments
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Investment Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $plan->name }} Plan</h5>
                        <p>
                            <strong>Min Investment:</strong> ${{ number_format($plan->min_investment, 2) }}<br>
                            @if($plan->max_investment)
                                <strong>Max Investment:</strong> ${{ number_format($plan->max_investment, 2) }}<br>
                            @endif
                            <strong>Daily Return:</strong> {{ $plan->daily_percentage }}%<br>
                            <strong>Duration:</strong> {{ $plan->duration_days }} days
                        </p>
                    </div>

                    <div class="mb-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Your available balance: <strong>${{ number_format($balance['available_balance'], 2) }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('investments.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Investment Amount (USDT)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       min="{{ $plan->min_investment }}" 
                                       @if($plan->max_investment) max="{{ $plan->max_investment }}" @endif
                                       step="0.01" required>
                            </div>
                            @error('amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Minimum: ${{ number_format($plan->min_investment, 2) }}
                                @if($plan->max_investment)
                                    | Maximum: ${{ number_format($plan->max_investment, 2) }}
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimated Daily Income</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="dailyIncome" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimated Total Return</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="totalReturn" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profit</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="profit" readonly>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create Investment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        const dailyPercentage = {{ $plan->daily_percentage }};
        const duration = {{ $plan->duration_days }};
        
        const dailyIncome = amount * (dailyPercentage / 100);
        const totalReturn = amount + (dailyIncome * duration);
        const profit = totalReturn - amount;
        
        document.getElementById('dailyIncome').value = dailyIncome.toFixed(2);
        document.getElementById('totalReturn').value = totalReturn.toFixed(2);
        document.getElementById('profit').value = profit.toFixed(2);
    });
</script>
@endpush