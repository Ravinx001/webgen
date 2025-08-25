<!-- Global stylesheets -->
<link href="{{ asset('assets/admin/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/admin/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/admin/css/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
<!-- /global stylesheets -->

@yield('css')

<!-- Core JS files -->
<script src="{{ asset('assets/admin/demo/demo_configurator.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- /core JS files -->

@yield('center-scripts')

<!-- Theme JS files -->
<script src="{{ asset('assets/admin/js/app.js') }}"></script>
<!-- /theme JS files -->

@yield('scripts')
