@extends('layouts.app')

@section('page-title', 'Make Deposit')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Make Deposit</h1>
        <div>
            <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Wallet
            </a>
            <a href="{{ route('wallet.transactions') }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-history fa-sm text-white-50"></i> Transaction History
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Deposit Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Deposit Funds</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('wallet.process-deposit') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Deposit Amount (USDT)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount') }}" 
                                               step="0.01" 
                                               min="50" 
                                               placeholder="Enter amount"
                                               required>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum deposit: $50.00</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Your Current Balance</label>
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h4 class="text-primary">${{ number_format($balance['total'] ?? 0, 2) }}</h4>
                                            <small class="text-muted">Total Available Balance</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="txn_hash" class="form-label">BSC Transaction Hash (BEP20)</label>
                            <input type="text" 
                                   class="form-control @error('txn_hash') is-invalid @enderror" 
                                   id="txn_hash" 
                                   name="txn_hash" 
                                   value="{{ old('txn_hash') }}" 
                                   placeholder="Enter your BSC transaction hash"
                                   required>
                            @error('txn_hash')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Copy the transaction hash from your wallet after making the BEP20 transfer
                            </small>
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important BEP20 Instructions</h6>
                            <ul class="mb-0">
                                <li>Send <strong>USDT BEP20</strong> only to our wallet address</li>
                                <li>Ensure you are on <strong>Binance Smart Chain (BSC)</strong> network</li>
                                <li>Copy and paste the BSC transaction hash correctly</li>
                                <li>Minimum deposit amount is $50 USDT</li>
                                <li>Keep sufficient BNB for transaction fees</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Submit Deposit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deposit Information -->
        <div class="col-lg-4">
            <!-- BEP20 Wallet Address -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fab fa-bootstrap me-2"></i>Our USDT BEP20 Wallet Address
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-light p-3 rounded border">
                            <code class="text-dark small" id="walletAddress">0x742E4D6c4C8B6C4D8E6F7C5A3B2C1D0E9F8A7B6C</code>
                            <button class="btn btn-sm btn-outline-warning ms-2" onclick="copyWalletAddress()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="network-info">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="badge bg-warning me-2">BEP20</span>
                            <small class="text-muted">Binance Smart Chain</small>
                        </div>
                        <p class="small text-muted text-center mb-0">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Send <strong>USDT BEP20</strong> only to this address
                        </p>
                    </div>
                </div>
            </div>

            <!-- Network Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">BSC Network Details</h6>
                </div>
                <div class="card-body">
                    <div class="network-details">
                        <div class="detail-item mb-2">
                            <strong>Network:</strong> Binance Smart Chain (BSC)
                        </div>
                        <div class="detail-item mb-2">
                            <strong>Token Type:</strong> USDT BEP20
                        </div>
                        <div class="detail-item mb-2">
                            <strong>Chain ID:</strong> 56 (Mainnet)
                        </div>
                        <div class="detail-item">
                            <strong>RPC URL:</strong> https://bsc-dataseed.binance.org/
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deposit Steps -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">How to Deposit (BEP20)</h6>
                </div>
                <div class="card-body">
                    <div class="steps">
                        <div class="step mb-3">
                            <div class="step-number bg-warning">1</div>
                            <div class="step-content">
                                <strong>Switch to BSC Network</strong>
                                <p class="mb-0 small">Ensure your wallet is connected to Binance Smart Chain</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-warning">2</div>
                            <div class="step-content">
                                <strong>Send USDT BEP20</strong>
                                <p class="mb-0 small">Send USDT BEP20 to our wallet address</p>
                            </div>
                        </div>
                        <div class="step mb-3">
                            <div class="step-number bg-warning">3</div>
                            <div class="step-content">
                                <strong>Copy BSC Transaction Hash</strong>
                                <p class="mb-0 small">Copy the transaction hash from BSC Scan</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number bg-warning">4</div>
                            <div class="step-content">
                                <strong>Submit Details</strong>
                                <p class="mb-0 small">Enter amount and transaction hash in the form</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Need Help?</h6>
                </div>
                <div class="card-body text-center">
                    <i class="fab fa-bootstrap fa-2x text-warning mb-2"></i>
                    <i class="fas fa-headset fa-2x text-primary mb-3"></i>
                    <p class="mb-3">If you face any issues with BEP20 deposit, contact our support team.</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-envelope me-2"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.step {
    display: flex;
    align-items: flex-start;
}
.step-number {
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
    flex-shrink: 0;
}
.step-content {
    flex: 1;
}
.network-details {
    font-size: 0.9rem;
}
.detail-item {
    padding: 5px 0;
    border-bottom: 1px solid #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
function copyWalletAddress() {
    const walletAddress = document.getElementById('walletAddress').textContent;
    navigator.clipboard.writeText(walletAddress).then(function() {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('btn-outline-warning');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-warning');
        }, 2000);
    });
}

// Add BSC network to MetaMask
function addBSCNetwork() {
    if (typeof window.ethereum !== 'undefined') {
        window.ethereum.request({
            method: 'wallet_addEthereumChain',
            params: [{
                chainId: '0x38',
                chainName: 'Binance Smart Chain',
                nativeCurrency: {
                    name: 'BNB',
                    symbol: 'BNB',
                    decimals: 18
                },
                rpcUrls: ['https://bsc-dataseed.binance.org/'],
                blockExplorerUrls: ['https://bscscan.com/']
            }]
        }).then(() => {
            alert('BSC Network added successfully!');
        }).catch((error) => {
            console.error('Error adding BSC network:', error);
            alert('Error adding BSC network. Please add it manually.');
        });
    } else {
        alert('Please install MetaMask to use this feature.');
    }
}
</script>
@endpush