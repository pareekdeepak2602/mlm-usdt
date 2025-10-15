@extends('layouts.app')

@section('page-title', 'Deposit Funds')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Deposit Funds</h1>
        <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Wallet
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Deposit USDT (BEP20)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5>Deposit Information</h5>
                                <p>Send USDT to the address below and submit the form with your transaction details.</p>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Minimum deposit amount: <strong>$50</strong>
                                </div>
                                
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Our USDT Address (BEP20)</h6>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="0x1234567890abcdef1234567890abcdef12345678" readonly id="usdtAddress">
                                            <button class="btn btn-outline-secondary" type="button" id="copyAddressBtn">Copy</button>
                                        </div>
                                        
                                        <div class="text-center">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=0x1234567890abcdef1234567890abcdef12345678" alt="USDT Address QR Code" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5>Submit Deposit</h5>
                                
                                <form action="{{ route('wallet.deposit.process') }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount (USDT)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="amount" name="amount" min="50" step="0.01" required>
                                        </div>
                                        @error('amount')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="txn_hash" class="form-label">Transaction Hash (TXID)</label>
                                        <input type="text" class="form-control" id="txn_hash" name="txn_hash" required>
                                        @error('txn_hash')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">You can find this in your wallet transaction history</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="from_address" class="form-label">From Address (Optional)</label>
                                        <input type="text" class="form-control" id="from_address" name="from_address">
                                        <div class="form-text">Your wallet address from which you sent the funds</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes (Optional)</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Submit Deposit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('copyAddressBtn').addEventListener('click', function() {
        var copyText = document.getElementById('usdtAddress');
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        var originalText = this.innerHTML;
        this.innerHTML = 'Copied!';
        var button = this;
        
        setTimeout(function() {
            button.innerHTML = originalText;
        }, 2000);
    });
</script>
@endpush