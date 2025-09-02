@extends('admin.layouts.master')
@section('content')
    @component('admin.components.breadcrumb')
    @slot('title') {{ $title }} @endslot
    @slot('subtitle') {{ $subtitle }} @endslot
    @endcomponent

    <!-- Content area -->
    <div class="content">



    </div>
    <!-- /content area -->

@endsection
@section('center-scripts')
    <script src="{{ asset('assets/admin/js/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/vendor/tables/datatables/datatables.min.js')}}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/demo/pages/datatables_api.js')}}"></script>
@endsection