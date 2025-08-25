<!DOCTYPE html>
<html lang="en" dir="ltr" data-color-theme="light" >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>One Sri Lanka</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/admin/images/favicon.svg')}}">

   @include('admin.layouts.head-css')

</head>

<body>
    <!-- navbar -->
    @include('admin.layouts.navbar')

    <!-- Page content -->
    <div class="page-content">

        <!-- sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                @yield('content')

                @include('admin.layouts.footer')

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

    <!-- notification -->
    @include('admin.layouts.notification')

    <!-- right-sidebar content -->
    @include('admin.layouts.right-sidebar')

</body>
</html>
