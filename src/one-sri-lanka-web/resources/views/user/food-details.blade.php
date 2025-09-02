@extends('user.layouts.app')

@section('title', 'Check Food Details - One Sri Lanka')

@push('styles')
<style>
.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.feature-box {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.feature-box:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.search-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 10px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
}

.btn-white {
    background-color: white;
    color: #28a745;
    border: 1px solid white;
    font-weight: 500;
}

.btn-white:hover {
    background-color: #f8f9fa;
    color: #20c997;
    border-color: #f8f9fa;
}

.safety-badge {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border: 1px solid #b8dacd;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}
</style>
@endpush

@section('page-heading')
<h2>Check Food Details</h2>
@endsection

@section('content')
    <div class="d-flex flex-wrap mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-3">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Main Information Card -->
    <div class="info-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-success mb-3">
                    <i class="fas fa-utensils me-2"></i>
                    Food Safety & Information Portal
                </h3>
                <p class="lead mb-3">
                    Access comprehensive food safety information, check product details, and verify food establishment licenses in Sri Lanka.
                </p>
                <p class="text-muted">
                    This service provides access to official food safety data from the Food Control Administration Unit and other relevant authorities to ensure public health and safety.
                </p>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-shield-alt fa-5x text-success opacity-75"></i>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <h4 class="mb-3">
            <i class="fas fa-search me-2"></i>
            Search Food Information
        </h4>
        <p class="mb-4">Enter product name, batch number, establishment name, or license number to check food safety details.</p>

        <div class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Enter product name, batch number, or establishment name...">
                    <button class="btn btn-white btn-lg" type="button">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <small class="text-light">
                <i class="fas fa-info-circle me-1"></i>
                Search by product name, batch number, manufacturer, or food establishment license
            </small>
        </div>
    </div>

    <!-- Food Safety Badge -->
    <div class="safety-badge">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <i class="fas fa-award fa-3x text-success"></i>
            </div>
            <div class="col-md-10">
                <h5 class="text-success mb-2">Official Food Safety Database</h5>
                <p class="mb-0 text-muted">
                    Connected to the Ministry of Health and Food Control Administration Unit for real-time, accurate information.
                </p>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-barcode fa-3x text-primary"></i>
                </div>
                <h5 class="text-center mb-3">Product Verification</h5>
                <p class="text-muted text-center">
                    Verify product authenticity, check expiry dates, and validate batch information.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-store fa-3x text-success"></i>
                </div>
                <h5 class="text-center mb-3">Establishment Licenses</h5>
                <p class="text-muted text-center">
                    Check food establishment licenses and hygiene ratings for restaurants and food vendors.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <h5 class="text-center mb-3">Safety Alerts</h5>
                <p class="text-muted text-center">
                    Access current food safety alerts, recalls, and health warnings.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-microscope fa-3x text-info"></i>
                </div>
                <h5 class="text-center mb-3">Lab Test Results</h5>
                <p class="text-muted text-center">
                    View laboratory test results and quality analysis reports for food products.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-leaf fa-3x text-success"></i>
                </div>
                <h5 class="text-center mb-3">Organic Certification</h5>
                <p class="text-muted text-center">
                    Verify organic certification and sustainable farming credentials.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-globe fa-3x text-primary"></i>
                </div>
                <h5 class="text-center mb-3">Import/Export Data</h5>
                <p class="text-muted text-center">
                    Check import permits and export certifications for food products.
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-title">Quick Actions</div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-camera fa-2x text-primary mb-3"></i>
                    <h6>Scan Barcode</h6>
                    <p class="small text-muted">Use your camera to scan product barcodes</p>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-camera me-1"></i>Scan Now
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-bell fa-2x text-warning mb-3"></i>
                    <h6>Report Issue</h6>
                    <p class="small text-muted">Report food safety concerns or issues</p>
                    <button class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-exclamation me-1"></i>Report
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-star fa-2x text-success mb-3"></i>
                    <h6>Rate Establishment</h6>
                    <p class="small text-muted">Share your experience with food establishments</p>
                    <button class="btn btn-outline-success btn-sm">
                        <i class="fas fa-star me-1"></i>Rate
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-success border-0" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                <h5 class="alert-heading">
                    <i class="fas fa-shield-alt me-2"></i>
                    Food Safety Information
                </h5>
                <ul class="mb-0">
                    <li>Information sourced from official Ministry of Health databases</li>
                    <li>Real-time updates on food safety alerts and recalls</li>
                    <li>Establishment ratings based on official health inspections</li>
                    <li>Report food safety concerns directly to relevant authorities</li>
                    <li>Access to laboratory test results and quality certifications</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="row">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="text-danger mb-2">
                                <i class="fas fa-phone me-2"></i>
                                Emergency Food Safety Hotline
                            </h5>
                            <p class="mb-0">
                                For immediate food safety concerns or emergencies, contact: <strong>1920</strong>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <button class="btn btn-danger">
                                <i class="fas fa-phone me-2"></i>Call 1920
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Services -->
    <div class="section-title mt-4">Related Services</div>
    <div class="d-flex flex-wrap mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-3 mb-3">
            <i class="fas fa-home me-2"></i>Dashboard
        </a>
        <a href="{{ route('business.registrations') }}" class="btn btn-outline-secondary me-3 mb-3">
            <i class="fas fa-building me-2"></i>Find Business Registrations
        </a>
        <button class="btn btn-outline-info mb-3">
            <i class="fas fa-question-circle me-2"></i>Help & Support
        </button>
    </div>
@endsection
