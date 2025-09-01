@extends('user.layouts.app')

@section('title', 'One Sri Lanka Dashboard')

@push('styles')
<style>
.alert-description {
    line-height: 1.4;
    font-size: 0.95rem;
}

.scam-alert-light,
.scam-alert-dark {
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.scam-alert-light:hover,
.scam-alert-dark:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.scam-alert-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #007bff;
}

.scam-alert-dark {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%);
    color: white;
    border-left: 4px solid #dc3545;
}

.scam-alert-dark .text-muted {
    color: #adb5bd !important;
}

.scam-alert-dark .badge {
    opacity: 0.9;
}
</style>
@endpush

@section('page-heading')
<h2>One Place for Every Voice in Sri Lanka</h2>
@endsection

@section('content')
    <div class="d-flex flex-wrap mb-4">
        <a href="{{ route('action') }}" class="btn btn-blue">Make a Complaint</a>
        <button class="btn btn-blue">Give a Sugession</button>
        <button class="btn btn-blue">Leave a Review</button>
    </div>

    <div class="section-title">Special Government Announcements (3)</div>
    <div class="announcement">
        The National Water Supply and Drainage Board wishes to inform residents of Colombo District that there will
        be a temporary water supply interruption on Monday, 11 August 2025, from 8:00 AM to 5:00 PM, due to urgent
        maintenance work on the main pipeline.
    </div>

    <div class="section-title">Links</div>
    <div class="d-flex flex-wrap links mb-4">
        <button class="btn btn-blue me-3 mb-3">Find Business Registrations</button>
        <button class="btn btn-blue mb-3">Check Food Details</button>
    </div>

    @php
        $totalAlerts = $alerts->flatten()->count();
        $categoryStyles = [
            'security' => 'scam-alert-dark',
            'emergency' => 'scam-alert-dark',
            'maintenance' => 'scam-alert-light',
            'system' => 'scam-alert-light',
            'general' => 'scam-alert-light'
        ];
        $categoryIcons = [
            'security' => 'fas fa-shield-alt',
            'emergency' => 'fas fa-exclamation-triangle',
            'maintenance' => 'fas fa-tools',
            'system' => 'fas fa-cog',
            'general' => 'fas fa-info-circle'
        ];
    @endphp

    <div class="section-title">
        Active Alerts ({{ $totalAlerts }})
        @if($totalAlerts > 0)
            <small class="text-muted ms-2">Stay informed with the latest updates</small>
        @endif
    </div>
    @if($totalAlerts > 0)
        <div class="row g-3 mb-4">
            @foreach($alerts as $category => $categoryAlerts)
                @foreach($categoryAlerts as $alert)
                    <div class="col-md-6 {{ $categoryStyles[$category] ?? 'scam-alert-light' }}">
                        <div class="d-flex align-items-start">
                            <div class="me-3 mt-1">
                                <i class="{{ $categoryIcons[$category] ?? 'fas fa-bell' }} fs-5
                                    @if($categoryStyles[$category] === 'scam-alert-dark') text-light @else text-primary @endif">
                                </i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0 fw-bold">{{ $alert['title'] }}</h6>
                                    <span class="badge bg-{{ $alert['priority'] === 'critical' ? 'danger' : ($alert['priority'] === 'high' ? 'warning' : ($alert['priority'] === 'medium' ? 'info' : 'secondary')) }} ms-2">
                                        {{ ucfirst($alert['priority']) }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <span class="badge bg-{{ $alert['category'] === 'emergency' || $alert['category'] === 'security' ? 'danger' : 'primary' }} me-1">
                                        {{ ucfirst($alert['category']) }}
                                    </span>
                                    @if($alert['status'] === 'active')
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle fa-xs me-1"></i>Active
                                        </span>
                                    @endif
                                </div>
                                <div class="alert-description mb-2">
                                    {{ $alert['description'] }}
                                </div>
                                @if(isset($alert['created_at']))
                                    <div class="small {{ $categoryStyles[$category] === 'scam-alert-dark' ? 'text-light-emphasis' : 'text-muted' }}">
                                        <i class="fas fa-clock me-1"></i>
                                        Posted on {{ date('M d, Y \a\t g:i A', strtotime($alert['created_at'])) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Alert Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($alerts as $category => $categoryAlerts)
                        <span class="badge bg-light text-dark border">
                            <i class="{{ $categoryIcons[$category] ?? 'fas fa-bell' }} me-1"></i>
                            {{ ucfirst($category) }}: {{ count($categoryAlerts) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="text-center py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 10px; border: 1px solid rgba(0,0,0,0.1);">
                    <i class="fas fa-shield-alt fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">All Clear!</h5>
                    <p class="text-muted mb-0">There are currently no active alerts. We'll keep you updated with any important information.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
