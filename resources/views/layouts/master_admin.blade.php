<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title dinamis -->
    <title>@yield('title', 'Admin | Dwijaya Mebel')</title>

    <!-- CSS -->
    <link href="{{ asset('template_admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('template_admin/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('template_admin/css/style.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('template_customer/images/erasebg.png') }}?v=1">
	<link rel="shortcut icon" type="image/png" href="{{ asset('template_customer/images/erasebg.png') }}?v=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>

<body>

    {{-- NAVBAR --}}
    @include('layouts.navbar_admin')

    {{-- Hero  --}}
    @yield('hero')

    {{-- HALAMAN ISI --}}
    <div class="container mt-4">
        @yield('content')
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('template_admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template_admin/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('template_admin/js/custom.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>

</body>
</html>
