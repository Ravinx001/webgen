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
   
@endsection

@section('scripts')

@endsection