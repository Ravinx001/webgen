@extends('user.layouts.app')

@section('title', 'One Sri Lanka Complaints')

@section('content')
<div class="container mt-4">
    <h5 class="fw-bold">Complaints</h5>
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100" data-bs-toggle="modal" data-bs-target="#foodComplaintModal">Food</button>
        </div>
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100">Transport</button>
        </div>
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100">Education</button>
        </div>
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100">Health</button>
        </div>
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100">Media</button>
        </div>
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100">Companies</button>
        </div>
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100">Agriculture</button>
        </div>
        <div class="col-md-3 col-sm-6">
            <button class="btn btn-custom w-100">Other</button>
        </div>
    </div>
</div>

<div class="modal fade" id="foodComplaintModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <button class="back-btn" data-bs-dismiss="modal">&lt;</button>
                <h5 class="ms-3 fw-bold">Food Complaint</h5>
            </div>
            <div class="modal-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="opt1">
                    <label class="form-check-label" for="opt1">Spoiled / Rotten Food</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="opt2">
                    <label class="form-check-label" for="opt2">Foreign Objects in Food</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="opt3">
                    <label class="form-check-label" for="opt3">Poor Hygiene & Cleanliness</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="opt4">
                    <label class="form-check-label" for="opt4">Undercooked / Overcooked Food</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="opt5">
                    <label class="form-check-label" for="opt5">Other</label>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-next" data-bs-target="#foodDetailModal" data-bs-toggle="modal">Next</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="foodDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <button class="back-btn" data-bs-target="#foodComplaintModal" data-bs-toggle="modal">&lt;</button>
                <h5 class="ms-3 fw-bold">Food Complaint</h5>
            </div>
            <div class="modal-body">
                <h6>Spoiled / Rotten Food</h6>
                <div class="row g-2 mb-3">
                    <div class="col">
                        <select class="form-select">
                            <option>Select Province</option>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select">
                            <option>Select District</option>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-select">
                            <option>Select City/Town</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <select class="form-select">
                        <option>Select Store/Market/Canteen/Grocery</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="datetime-local" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Photo / Video</label>
                    <div class="d-flex gap-2">
                        <input type="file" class="form-control" accept="image/*,video/*">
                        <input type="file" class="form-control" accept="image/*,video/*">
                        <input type="file" class="form-control" accept="image/*,video/*">
                    </div>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" rows="3" placeholder="Description"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-submit">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

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
    .btn-custom {
        background-color: #0000ff;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        padding: 10px 20px;
        width: 150px;
    }
    .btn-custom:hover {
        background-color: #0000cc;
    }
    .modal-content {
        border-radius: 10px;
    }
    .modal-header {
        border-bottom: none;
        align-items: center;
    }
    .back-btn {
        background-color: #0000ff;
        color: white;
        font-size: 20px;
        border-radius: 10px;
        width: 40px;
        height: 40px;
        text-align: center;
        line-height: 20px;
        border: none;
    }
    .form-check-input {
        border: 2px solid #0000ff;
    }
    .form-control, .form-select {
        border: 2px solid #0000ff;
    }
    .btn-cancel {
        background-color: #a3a3ff;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        padding: 10px 20px;
    }
    .btn-next, .btn-submit {
        background-color: #0000ff;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        padding: 10px 20px;
    }
</style>
@endpush
