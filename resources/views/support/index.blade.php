@extends('layouts.app_new')

@section('page-title', 'Support Center')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Support</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('support.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label" style="color: var(--text-primary);">Your Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ Auth::user()->name }}" required 
                                           style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label" style="color: var(--text-primary);">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ Auth::user()->email }}" required
                                           style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="subject" class="form-label" style="color: var(--text-primary);">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required
                                   style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="message" class="form-label" style="color: var(--text-primary);">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required 
                                      style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                                      placeholder="Please describe your issue in detail..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Quick Support Links -->
            <div class="card shadow mb-4" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Support</h6>
                </div>
                <div class="card-body">
                    @php
                        $supportSettings = $supportSettings['contact'] ?? collect();
                    @endphp
                    
                    @if($supportSettings->isNotEmpty())
                        <div class="support-links">
                            @if($supportSettings->where('key', 'whatsapp_number')->first()?->value)
                                <a href="https://wa.me/{{ $supportSettings->where('key', 'whatsapp_number')->first()->value }}" 
                                   target="_blank" class="btn btn-success w-100 mb-3">
                                    <i class="fab fa-whatsapp me-2"></i>WhatsApp Support
                                </a>
                            @endif
                            
                            @if($supportSettings->where('key', 'telegram_link')->first()?->value)
                                <a href="{{ $supportSettings->where('key', 'telegram_link')->first()->value }}" 
                                   target="_blank" class="btn btn-primary w-100 mb-3">
                                    <i class="fab fa-telegram me-2"></i>Telegram Group
                                </a>
                            @endif
                            
                            @if($supportSettings->where('key', 'email')->first()?->value)
                                <a href="mailto:{{ $supportSettings->where('key', 'email')->first()->value }}" 
                                   class="btn btn-info w-100 mb-3">
                                    <i class="fas fa-envelope me-2"></i>Email Support
                                </a>
                            @endif
                            
                            @if($supportSettings->where('key', 'phone')->first()?->value)
                                <a href="tel:{{ $supportSettings->where('key', 'phone')->first()->value }}" 
                                   class="btn btn-secondary w-100 mb-3">
                                    <i class="fas fa-phone me-2"></i>Call Support
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-center text-muted">Support links will be available soon.</p>
                    @endif
                </div>
            </div>
            
            <!-- Support Information -->
            <div class="card shadow" style="background: var(--card-bg); border-color: var(--card-border);">
                <div class="card-header py-3" style="background: var(--bg-secondary); border-color: var(--border-color);">
                    <h6 class="m-0 font-weight-bold text-primary">Support Information</h6>
                </div>
                <div class="card-body">
                    <div class="support-info">
                        @if($supportSettings->isNotEmpty())
                            <div class="mb-3">
                                <strong style="color: var(--text-primary);">Response Time:</strong>
                                <span style="color: var(--text-secondary);">
                                    {{ $supportSettings->where('key', 'response_time')->first()?->value ?? '24-48 hours' }}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <strong style="color: var(--text-primary);">Working Hours:</strong>
                                <span style="color: var(--text-secondary);">
                                    {{ $supportSettings->where('key', 'working_hours')->first()?->value ?? '24/7' }}
                                </span>
                            </div>
                            
                            <div>
                                <strong style="color: var(--text-primary);">Support Type:</strong>
                                <span style="color: var(--text-secondary);">
                                    {{ $supportSettings->where('key', 'support_type')->first()?->value ?? 'Technical & Account Support' }}
                                </span>
                            </div>
                        @else
                            <p class="text-center text-muted">Support information will be available soon.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection