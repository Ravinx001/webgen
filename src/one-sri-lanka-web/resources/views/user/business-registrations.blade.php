@extends('user.layouts.app')

@section('title', 'Find Business Registrations - One Sri Lanka')

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
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 10px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
}

.btn-white {
    background-color: white;
    color: #007bff;
    border: 1px solid white;
    font-weight: 500;
}

.btn-white:hover {
    background-color: #f8f9fa;
    color: #0056b3;
    border-color: #f8f9fa;
}
</style>
@endpush

@section('page-heading')
<h2>Find Business Registrations</h2>
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
                <h3 class="text-primary mb-3">
                    <i class="fas fa-building me-2"></i>
                    Business Registration Lookup
                </h3>
                <p class="lead mb-3">
                    Search and verify business registrations in Sri Lanka. Access official records from the Registrar of Companies and other relevant authorities.
                </p>
                <p class="text-muted">
                    This service provides access to comprehensive business registration data including company details, registration status, directors information, and compliance records.
                </p>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-search-dollar fa-5x text-primary opacity-75"></i>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <h4 class="mb-3">
            <i class="fas fa-search me-2"></i>
            Search Business Records
        </h4>
        <p class="mb-4">Enter business registration number, company name, or director name to find relevant records.</p>
        
        <div class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Enter business name, registration number, or director name...">
                    <button class="btn btn-white btn-lg" type="button">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <small class="text-light">
                <i class="fas fa-info-circle me-1"></i>
                You can search by company name, business registration number (BR), or director names
            </small>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-certificate fa-3x text-primary"></i>
                </div>
                <h5 class="text-center mb-3">Company Verification</h5>
                <p class="text-muted text-center">
                    Verify the authenticity and current status of registered companies in Sri Lanka.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-users fa-3x text-success"></i>
                </div>
                <h5 class="text-center mb-3">Director Information</h5>
                <p class="text-muted text-center">
                    Access information about company directors and key personnel.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-file-alt fa-3x text-info"></i>
                </div>
                <h5 class="text-center mb-3">Compliance Records</h5>
                <p class="text-muted text-center">
                    Check compliance status and filing history of registered businesses.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-map-marker-alt fa-3x text-warning"></i>
                </div>
                <h5 class="text-center mb-3">Address Verification</h5>
                <p class="text-muted text-center">
                    Verify registered addresses and contact information of businesses.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-chart-line fa-3x text-danger"></i>
                </div>
                <h5 class="text-center mb-3">Financial Status</h5>
                <p class="text-muted text-center">
                    Access basic financial filing information and capital details.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-box">
                <div class="text-center mb-3">
                    <i class="fas fa-download fa-3x text-secondary"></i>
                </div>
                <h5 class="text-center mb-3">Official Documents</h5>
                <p class="text-muted text-center">
                    Download official certificates and registration documents.
                </p>
            </div>
        </div>
    </div>

    <!-- Important Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info border-0" style="background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);">
                <h5 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>
                    Important Information
                </h5>
                <ul class="mb-0">
                    <li>This service is connected to official government databases</li>
                    <li>Information is updated regularly from the Registrar of Companies</li>
                    <li>Some premium features may require authentication</li>
                    <li>Data is provided for informational purposes and legal verification</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="section-title">Related Services</div>
    <div class="d-flex flex-wrap mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-3 mb-3">
            <i class="fas fa-home me-2"></i>Dashboard
        </a>
        <a href="{{ route('food.details') }}" class="btn btn-outline-secondary me-3 mb-3">
            <i class="fas fa-utensils me-2"></i>Check Food Details
        </a>
        <button class="btn btn-outline-info mb-3">
            <i class="fas fa-phone me-2"></i>Contact Support
        </button>
    </div>
@endsection
