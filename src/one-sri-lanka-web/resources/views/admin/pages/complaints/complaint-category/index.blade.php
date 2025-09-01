@extends('admin.layouts.master')
@section('content')
    @component('admin.components.breadcrumb')
    @slot('title') {{ $title }} @endslot
    @slot('subtitle') {{ $subtitle }} @endslot
    @endcomponent

    <!-- Content area -->
    <div class="content">

        <div class="card">
            <table class="table datatable-column-search-inputs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category Type</th>
                        <th>Parent Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category Type</th>
                        <th>Parent Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
     
                </tbody>
            </table>
        </div>
    </div>
    <!-- /content area -->

@endsection
@section('center-scripts')
    <script src="{{asset('assets/admin/js/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendor/tables/datatables/datatables.min.js')}}"></script>
@endsection

@section('scripts')
    <script>
        window.complaintCategoryUrl = '{{ route("admin.complaint-category.get") }}';
        window.complaintEditUrl = '{{ route("admin.complaint-category.edit", ":id") }}';
    </script>
    <script src="{{asset('assets/admin/pages/complaint_category.js')}}"></script>
@endsection