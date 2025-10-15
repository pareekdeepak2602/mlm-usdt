@extends('layouts.app')

@section('page-title', 'Referral Tree')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Referral Tree</h1>
        <div>
            <a href="{{ route('referrals.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-list fa-sm text-white-50"></i> Referral List
            </a>
            <a href="{{ route('referrals.earnings') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-dollar-sign fa-sm text-white-50"></i> Referral Earnings
            </a>
        </div>
    </div>

    <!-- Referral Tree -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Referral Network</h6>
                </div>
                <div class="card-body">
                    @if(count($referrals) > 0)
                        <div class="tree-container">
                            <ul class="tree">
                                <!-- Current User (Root) -->
                                <li>
                                    <div class="tree-node root-node">
                                        <div class="node-content">
                                            <div class="node-avatar">
                                                <i class="fas fa-user fa-2x"></i>
                                            </div>
                                            <div class="node-info">
                                                <strong>{{ Auth::user()->name }}</strong>
                                                <br>
                                                <small>{{ Auth::user()->email }}</small>
                                                <br>
                                                <span class="badge bg-primary">You</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($referrals) > 0)
                                        <ul>
                                            @include('referrals.partials.tree-level', ['referrals' => $referrals, 'level' => 1])
                                        </ul>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-sitemap fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">You don't have any referrals yet</p>
                            <p>Share your referral link to start building your network!</p>
                            <a href="{{ route('referrals.index') }}" class="btn btn-primary">
                                <i class="fas fa-link"></i> Get Referral Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.tree-container {
    overflow-x: auto;
}

.tree {
    margin: 0;
    padding: 0;
    list-style-type: none;
}

.tree ul {
    margin: 0;
    padding: 0;
    list-style-type: none;
    margin-left: 40px;
    position: relative;
}

.tree ul:before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 1px;
    height: 100%;
    background: #ddd;
}

.tree li {
    margin: 10px 0;
    position: relative;
}

.tree li:before {
    content: "";
    position: absolute;
    top: -10px;
    left: -40px;
    width: 40px;
    height: 20px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}

.tree-node {
    background: #fff;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 15px;
    margin: 5px 0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s;
    max-width: 300px;
}

.tree-node:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.root-node {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    border: none;
}

.root-node .node-info strong,
.root-node .node-info small {
    color: white;
}

.node-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.node-avatar {
    flex-shrink: 0;
}

.node-info {
    flex-grow: 1;
}

.node-info strong {
    color: #4e73df;
    display: block;
    font-size: 0.9rem;
}

.node-info small {
    color: #858796;
    font-size: 0.8rem;
}

.level-1 .tree-node { border-left: 4px solid #4e73df; }
.level-2 .tree-node { border-left: 4px solid #1cc88a; }
.level-3 .tree-node { border-left: 4px solid #36b9cc; }

.badge {
    font-size: 0.7rem;
    margin-top: 5px;
}
</style>
@endpush