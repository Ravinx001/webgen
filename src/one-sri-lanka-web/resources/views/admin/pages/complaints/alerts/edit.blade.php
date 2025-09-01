@extends('admin.layouts.master')

@section('content')
    @component('admin.components.breadcrumb')
        @slot('title') Edit Alert @endslot
        @slot('subtitle') {{ $subtitle ?? 'Modify alert details and settings' }} @endslot
    @endcomponent

    <!-- Content area -->
    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Alert</h5>
                <small class="text-muted">
                    <i class="icon-user"></i> Ravinx-SLIIT |
                    <i class="icon-calendar"></i> 2025-08-31 19:04:41
                </small>
            </div>

            <div class="card-body">
                @php
                    $alertId = is_array($alert)
                        ? $alert['id']
                        : $alert->id;

                    $alertTitle = is_array($alert)
                        ? $alert['title']
                        : $alert->title;

                    $alertDescription = is_array($alert)
                        ? ($alert['description'] ?? '')
                        : ($alert->description ?? '');

                    $alertCategory = is_array($alert)
                        ? $alert['category']
                        : $alert->category;

                    $alertPriority = is_array($alert)
                        ? $alert['priority']
                        : $alert->priority;

                    $alertStatus = is_array($alert)
                        ? ($alert['status'] ?? 'active')
                        : ($alert->status ?? 'active');
                @endphp

                <form action="{{ route('admin.alerts.update', $alertId) }}" method="POST" id="editAlertForm">
                    @csrf
                    @method('PUT')

                    <!-- Alert Title -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">
                            Alert Title <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <input type="text"
                                   name="title"
                                   value="{{ old('title', $alertTitle) }}"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Enter alert title"
                                   maxlength="255"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="form-text">Maximum 255 characters allowed</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alert Description -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">
                            Alert Description <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Enter alert description"
                                      maxlength="1000"
                                      required>{{ old('description', $alertDescription) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="form-text">
                                    <span id="descriptionCount">{{ strlen($alertDescription) }}</span>/1000 characters
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alert Category -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">
                            Alert Category <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <select name="category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    required>
                                <option value="">Select Category</option>
                                <option value="security" {{ old('category', $alertCategory) === 'security' ? 'selected' : '' }}>
                                    Security
                                </option>
                                <option value="maintenance" {{ old('category', $alertCategory) === 'maintenance' ? 'selected' : '' }}>
                                    Maintenance
                                </option>
                                <option value="emergency" {{ old('category', $alertCategory) === 'emergency' ? 'selected' : '' }}>
                                    Emergency
                                </option>
                                <option value="system" {{ old('category', $alertCategory) === 'system' ? 'selected' : '' }}>
                                    System
                                </option>
                                <option value="general" {{ old('category', $alertCategory) === 'general' ? 'selected' : '' }}>
                                    General
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alert Priority -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">
                            Alert Priority <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <select name="priority"
                                    class="form-select @error('priority') is-invalid @enderror"
                                    required>
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority', $alertPriority) === 'low' ? 'selected' : '' }}>
                                    Low
                                </option>
                                <option value="medium" {{ old('priority', $alertPriority) === 'medium' ? 'selected' : '' }}>
                                    Medium
                                </option>
                                <option value="high" {{ old('priority', $alertPriority) === 'high' ? 'selected' : '' }}>
                                    High
                                </option>
                                <option value="critical" {{ old('priority', $alertPriority) === 'critical' ? 'selected' : '' }}>
                                    Critical
                                </option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">
                            Status <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="active" {{ old('status', $alertStatus) === 'active' ? 'selected' : '' }}>
                                    <i class="icon-checkmark-circle2"></i> Active
                                </option>
                                <option value="inactive" {{ old('status', $alertStatus) === 'inactive' ? 'selected' : '' }}>
                                    <i class="icon-blocked"></i> Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alert Info -->
                    @if(!is_array($alert))
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Alert Info</label>
                            <div class="col-lg-9">
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        <div class="row text-sm">
                                            <div class="col-md-6">
                                                <small class="text-muted">Created:</small><br>
                                                <span class="fw-semibold">
                                                    {{ $alert->created_at->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Last Updated:</small><br>
                                                <span class="fw-semibold">
                                                    {{ $alert->updated_at->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <small class="text-muted">Current Priority:</small><br>
                                            <span class="badge bg-{{ $alertPriority === 'critical' ? 'danger' : ($alertPriority === 'high' ? 'warning' : ($alertPriority === 'medium' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst($alertPriority) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="text-end">
                        <a href="{{ route('admin.alerts.index') }}"
                           class="btn btn-light me-2">
                            <i class="icon-arrow-left8"></i> Cancel
                        </a>
                        <button type="submit"
                                class="btn btn-primary"
                                id="submitBtn">
                            <i class="icon-checkmark"></i> Update Alert
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- /content area -->
@endsection

@section('center-scripts')

@endsection

@section('scripts')

@endsection
