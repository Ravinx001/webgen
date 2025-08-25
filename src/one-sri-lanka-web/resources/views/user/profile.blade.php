@extends('user.layouts.app')

@section('title', 'My Profile - One Sri Lanka')

@push('styles')
<style>
    body {
        background-color: #d9d9e0;
    }
    .navbar-custom {
        background-color: #0000ff;
    }
    .navbar-custom .navbar-brand {
        color: white;
        font-weight: bold;
        font-size: 14px;
    }
    .profile-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: radial-gradient(circle, blue, red);
        cursor: pointer;
    }
    .card-custom {
        border-radius: 10px;
        background-color: white;
        border: none;
    }
    .card-header-custom {
        background-color: #0000ff;
        color: white;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
    }
    .status-badge {
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.8rem;
        font-weight: bold;
        white-space: nowrap;
    }
    .status-pending { background-color: orange; color: white; }
    .status-progress { background-color: #0000ff; color: white; }
    .status-resolved { background-color: green; color: white; }
    .btn-view {
        background-color: #0000ff;
        color: white;
        padding: 4px 8px;
        font-size: 0.75rem;
        border-radius: 5px;
        border: none;
    }
    .modal-header {
        background-color: #0000ff;
        color: white;
        border-bottom: none;
    }
    .modal-footer {
        border-top: none;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row g-4">

        <!-- Profile Card -->
        <div class="col-lg-4 col-md-5">
            <div class="card card-custom">
                <div class="card-header card-header-custom text-center">
                    My Profile
                </div>
                <div class="card-body text-center">
                    <div class="profile-circle mx-auto mb-3" style="width:80px; height:80px;"></div>
                    <h5 class="fw-bold">John Doe</h5>
                    <p class="mb-1">johndoe@email.com</p>
                    <p class="text-muted">Colombo, Sri Lanka</p>
                    <button class="btn w-100" style="background-color:#0000ff; color:white;">Edit Profile</button>
                </div>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="col-lg-8 col-md-7">
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    My Complaints
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Complaint</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Food</td>
                                <td>Spoiled / Rotten Food</td>
                                <td>2025-08-05</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td><button class="btn-view" data-bs-toggle="modal" data-bs-target="#complaintDetailModal">View</button></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Transport</td>
                                <td>Bus Overcrowding</td>
                                <td>2025-08-03</td>
                                <td><span class="status-badge status-progress">In Progress</span></td>
                                <td><button class="btn-view" data-bs-toggle="modal" data-bs-target="#complaintDetailModal">View</button></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Health</td>
                                <td>Hospital Hygiene Issue</td>
                                <td>2025-08-01</td>
                                <td><span class="status-badge status-resolved">Resolved</span></td>
                                <td><button class="btn-view" data-bs-toggle="modal" data-bs-target="#complaintDetailModal">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complaint Detail Modal -->
<div class="modal fade" id="complaintDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Complaint Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Category:</strong> Food</p>
                <p><strong>Complaint:</strong> Spoiled / Rotten Food</p>
                <p><strong>Province:</strong> Western</p>
                <p><strong>District:</strong> Colombo</p>
                <p><strong>City/Town:</strong> Colombo 07</p>
                <p><strong>Store:</strong> SuperMart</p>
                <p><strong>Date & Time:</strong> 2025-08-05 14:30</p>
                <p><strong>Media:</strong></p>
                <div class="d-flex gap-2 mb-2">
                    <img src="https://via.placeholder.com/80" class="img-thumbnail" alt="Media 1">
                    <img src="https://via.placeholder.com/80" class="img-thumbnail" alt="Media 2">
                    <img src="https://via.placeholder.com/80" class="img-thumbnail" alt="Media 3">
                </div>
                <p><strong>Description:</strong></p>
                <p>Purchased food was spoiled and had a foul smell. This happened on the same day of purchase.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
