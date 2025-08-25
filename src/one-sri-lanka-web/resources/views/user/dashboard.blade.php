@extends('user.layouts.app')

@section('title', 'One Sri Lanka Dashboard')

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

    <div class="section-title">Scam Alerts (5)</div>
    <div class="row g-3">
        <div class="col-md-6 scam-alert-light">
            Demanding immediate payments via mobile money or bank transfer
        </div>
        <div class="col-md-6 scam-alert-light">
            Asking for personal details such as NIC numbers, bank account information, or passwords
        </div>
        <div class="col-md-6 scam-alert-dark">
            Threatening legal action if payments are not made
        </div>
    </div>
@endsection
