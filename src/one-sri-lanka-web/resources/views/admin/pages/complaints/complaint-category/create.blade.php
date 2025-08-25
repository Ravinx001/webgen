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
                <h5 class="mb-0">Create Complaint Category</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.complaint-category.store') }}" method="POST">
                    @csrf

                    <!-- Category Name -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Category Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Enter category name">
                            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Parent Category -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Parent Category</label>
                        <div class="col-lg-9">
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">Select Parent</option>
                                @foreach($complaintCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Category Type -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Category Type</label>
                        <div class="col-lg-9">
                            <input type="text" name="category_type" value="{{ old('category_type') }}"
                                class="form-control @error('category_type') is-invalid @enderror"
                                placeholder="Enter category type">
                            @error('category_type') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Description</label>
                        <div class="col-lg-9">
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="3" placeholder="Enter description">{{ old('description') }}</textarea>
                            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Status</label>
                        <div class="col-lg-9">
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-end">
                        <a href="{{ route('admin.complaint-category.index') }}" class="btn btn-light">Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">Save Category</button>
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