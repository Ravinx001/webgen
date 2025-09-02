@extends('admin.layouts.master')
@section('content')
    @component('admin.components.breadcrumb')
    @slot('title') {{ $title }} @endslot
    @slot('subtitle') {{ $subtitle }} @endslot
    @endcomponent

    <!-- Content area -->
    <div class="content">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create Alert</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.alerts.store') }}" method="POST">
                    @csrf

                    <!-- Alert Title -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Alert Title <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" name="title" value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror" placeholder="Enter alert title">
                            @error('title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Alert Description -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Alert Description <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="4" placeholder="Enter alert description">{{ old('description') }}</textarea>
                            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Alert Category -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Alert Category <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select name="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="">Select Category</option>
                                <option value="security" {{ old('category') == 'security' ? 'selected' : '' }}>Security</option>
                                <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="emergency" {{ old('category') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="system" {{ old('category') == 'system' ? 'selected' : '' }}>System</option>
                                <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                            @error('category') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Alert Priority -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Alert Priority <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('priority') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-end">
                        <a href="{{ route('admin.alerts.index') }}" class="btn btn-light">Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">Create Alert</button>
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
