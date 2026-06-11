<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dwijaya Meubel')</title>

    {{-- Bootstrap CSS --}}
    <link href="{{ asset('template_customer/css/bootstrap.min.css') }}" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
    <link rel="icon" type="image/png" href="{{ asset('template_customer/images/erasebg.png') }}?v=1">
	<link rel="shortcut icon" type="image/png" href="{{ asset('template_customer/images/erasebg.png') }}?v=1">

    {{-- CSS lainnya --}}
    <link href="{{ asset('template_customer/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('template_customer/css/style.css') }}" rel="stylesheet">

    @stack('styles')

</head>

<body>

    {{-- NAVBAR --}}
    @include('layouts.navbar')

    {{-- HERO --}}
    @hasSection('hero')
        @yield('hero')
    @endif

    {{-- SLIDER --}}
    @hasSection('slider')
        @yield('slider')
    @endif

    {{-- CONTENT TANPA CONTAINER --}}
    @yield('before_content')

    {{-- CONTENT --}}
    <div class="container mt-4">

        @yield('content')

        @yield('after_content')

    </div>

    {{-- FOOTER --}}
    @include('layouts.footer')

    {{-- Bootstrap JS --}}
    <script src="{{ asset('template_customer/js/bootstrap.bundle.min.js') }}"></script>

    {{-- JS lainnya --}}
    <script src="{{ asset('template_customer/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('template_customer/js/custom.js') }}"></script>

    {{-- Script tambahan dari halaman --}}
    @stack('scripts')

</body>
</html>