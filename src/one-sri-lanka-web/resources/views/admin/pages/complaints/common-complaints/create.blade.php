@extends('admin.layouts.master')

@section('content')
    @component('admin.components.breadcrumb')
    @slot('title') {{ $title ?? 'Create Common Complaint' }} @endslot
    @slot('subtitle') {{ $subtitle ?? 'Add a new common complaint' }} @endslot
    @endcomponent

    <!-- Content area -->
    <div class="content">
 
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon-checkmark-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Create Common Complaint</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.common-complaints.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="complaint_category_id">Complaint Category <span class="text-danger">*</span></label>
                            <select name="complaint_category_id" id="complaint_category_id"
                                class="form-control select2 @error('complaint_category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @if(isset($complaintCategories))
                                    @foreach($complaintCategories as $category)
                                        <option value="{{ $category->id }}" {{ old('complaint_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('complaint_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror" required maxlength="255"
                                placeholder="Enter complaint title" value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description"
                                class="form-control @error('description') is-invalid @enderror" rows="4"
                                placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="form_data">Form Data (JSON)</label>
                            <textarea name="form_data" id="form_data"
                                class="form-control font-monospace @error('form_data') is-invalid @enderror" rows="6"
                                placeholder='{"field1": "value1"}'>{{ old('form_data') }}</textarea>
                            <small class="form-text text-muted">Paste or edit the JSON structure for additional form
                                data.</small>
                            @error('form_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.common-complaints.index') }}" class="btn btn-outline-secondary"><i
                                    class="icon-arrow-left7 mr-1"></i> Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="icon-checkmark3 mr-1"></i>
                                Create</button>
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