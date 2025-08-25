<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'One Sri Lanka')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />


    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />

    @stack('styles')
</head>

<body>
    
    @unless(request()->routeIs('login'))
        @include('user.partials.navbar')
    @endunless

    <main class="container mt-4">

        @yield('page-heading')

        @yield('content')
    </main>


    @include('user.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>