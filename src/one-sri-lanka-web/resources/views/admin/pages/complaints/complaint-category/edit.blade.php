@extends('admin.layouts.master')

@section('content')
    @component('admin.components.breadcrumb')
        @slot('title') Edit Complaint Category @endslot
        @slot('subtitle') {{ $subtitle ?? 'Modify category details and settings' }} @endslot
    @endcomponent

    <!-- Content area -->
    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Complaint Category</h5>
                <small class="text-muted">
                    <i class="icon-user"></i> Ravinx-SLIIT | 
                    <i class="icon-calendar"></i> 2025-08-31 19:04:41
                </small>
            </div>
            
            <div class="card-body">
                @php
                    $categoryId = is_array($complaintCategory) 
                        ? $complaintCategory['category']['id'] 
                        : $complaintCategory->id;
                    
                    $categoryName = is_array($complaintCategory) 
                        ? $complaintCategory['category']['name'] 
                        : $complaintCategory->name;
                    
                    $categorySlug = is_array($complaintCategory) 
                        ? ($complaintCategory['category']['slug'] ?? '') 
                        : ($complaintCategory->slug ?? '');
                    
                    $categoryType = is_array($complaintCategory) 
                        ? $complaintCategory['category']['category_type'] 
                        : $complaintCategory->category_type;
                    
                    $categoryDescription = is_array($complaintCategory) 
                        ? ($complaintCategory['category']['description'] ?? '') 
                        : ($complaintCategory->description ?? '');
                    
                    $categoryStatus = is_array($complaintCategory) 
                        ? $complaintCategory['category']['status'] 
                        : $complaintCategory->status;
                    
                    $categoryParentId = is_array($complaintCategory) 
                        ? ($complaintCategory['category']['parent_id'] ?? null) 
                        : ($complaintCategory->parent_id ?? null);
                @endphp

                <form action="{{ route('admin.complaint-category.update', $categoryId) }}" method="POST" id="editCategoryForm">
                    @csrf

                    <!-- Category Name -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $categoryName) }}"
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Enter category name"
                                   maxlength="255"
                                   required>
                            @error('name') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @else
                                <div class="form-text">Maximum 255 characters allowed</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Slug -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Slug</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" 
                                       name="slug" 
                                       value="{{ old('slug', $categorySlug) }}"
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       placeholder="category-slug"
                                       pattern="[a-z0-9-]*"
                                       maxlength="255">
                                              </div>
                            @error('slug') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @else
                                <div class="form-text">
                                    Leave empty to auto-generate. Only lowercase letters, numbers, and hyphens allowed.
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Category Type -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">
                            Category Type <span class="text-danger">*</span>
                        </label>
                        <div class="col-lg-9">
                            <select name="category_type" 
                                    class="form-select @error('category_type') is-invalid @enderror" 
                                    required>
                                <option value="">Select Category Type</option>
                                <option value="main" {{ old('category_type', $categoryType) === 'main' ? 'selected' : '' }}>
                                    Main Category
                                </option>
                                <option value="sub" {{ old('category_type', $categoryType) === 'sub' ? 'selected' : '' }}>
                                    Sub Category
                                </option>
                                <option value="civil_issue" {{ old('category_type', $categoryType) === 'civil_issue' ? 'selected' : '' }}>
                                    Civil Issue
                                </option>
                                <option value="public_service" {{ old('category_type', $categoryType) === 'public_service' ? 'selected' : '' }}>
                                    Public Service
                                </option>
                                <option value="infrastructure" {{ old('category_type', $categoryType) === 'infrastructure' ? 'selected' : '' }}>
                                    Infrastructure
                                </option>
                            </select>
                            @error('category_type') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    <!-- Parent Category -->
                    <div class="row mb-3" id="parentCategoryRow">
                        <label class="col-form-label col-lg-3">Parent Category</label>
                        <div class="col-lg-9">
                            <select name="parent_id" 
                                    class="form-select @error('parent_id') is-invalid @enderror" 
                                    id="parentCategorySelect">
                                <option value="">Select Parent Category</option>
                                
                                @if(isset($complaintCategories))
                                    @foreach($complaintCategories as $category)
                                        @php
                                            $loopCategoryId = is_array($category) 
                                                ? $category['id'] 
                                                : $category->id;
                                            
                                            $loopCategoryName = is_array($category) 
                                                ? $category['name'] 
                                                : $category->name;
                                            
                                            $loopCategoryType = is_array($category) 
                                                ? ($category['category_type'] ?? '') 
                                                : ($category->category_type ?? '');
                                        @endphp
                                        
                                        @if($loopCategoryId != $categoryId)
                                            <option value="{{ $loopCategoryId }}" 
                                                    {{ old('parent_id', $categoryParentId) == $loopCategoryId ? 'selected' : '' }}
                                                    data-type="{{ $loopCategoryType }}">
                                                {{ $loopCategoryName }} 
                                                @if($loopCategoryType)
                                                    <small>({{ ucwords(str_replace('_', ' ', $loopCategoryType)) }})</small>
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            
                            @error('parent_id') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @else
                                <div class="form-text">Only applicable for sub-categories</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-3">
                        <label class="col-form-label col-lg-3">Description</label>
                        <div class="col-lg-9">
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="4" 
                                      placeholder="Enter detailed description of the category"
                                      maxlength="1000">{{ old('description', $categoryDescription) }}</textarea>
                            
                            @error('description') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @else
                                <div class="form-text">
                                    <span id="descriptionCount">{{ strlen($categoryDescription) }}</span>/1000 characters
                                </div>
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
                                <option value="active" {{ old('status', $categoryStatus) === 'active' ? 'selected' : '' }}>
                                    <i class="icon-checkmark-circle2"></i> Active
                                </option>
                                <option value="inactive" {{ old('status', $categoryStatus) === 'inactive' ? 'selected' : '' }}>
                                    <i class="icon-blocked"></i> Inactive
                                </option>
                            </select>
                            @error('status') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                    </div>

                    <!-- Category Info -->
                    @if(!is_array($complaintCategory))
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Category Info</label>
                            <div class="col-lg-9">
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        <div class="row text-sm">
                                            <div class="col-md-6">
                                                <small class="text-muted">Created:</small><br>
                                                <span class="fw-semibold">
                                                    {{ $complaintCategory->created_at->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Last Updated:</small><br>
                                                <span class="fw-semibold">
                                                    {{ $complaintCategory->updated_at->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($complaintCategory->parent)
                                            <div class="mt-2">
                                                <small class="text-muted">Current Parent:</small><br>
                                                <span class="badge bg-info">{{ $complaintCategory->parent->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="text-end">
                        <a href="{{ route('admin.complaint-category.index') }}" 
                           class="btn btn-light me-2">
                            <i class="icon-arrow-left8"></i> Cancel
                        </a>
                        <button type="submit" 
                                class="btn btn-primary" 
                                id="submitBtn">
                            <i class="icon-checkmark"></i> Update Category
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