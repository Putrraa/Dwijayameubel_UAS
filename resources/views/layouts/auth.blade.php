<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | Dwijaya Meubel</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    {{-- Bootstrap Icons (Opsional tapi berguna untuk form) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Bootstrap Brain (Komponen UI) --}}
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-9/assets/css/registration-9.css">

    {{-- CSS Kustom jika ada --}}
    <style>
        /* Contoh: Menyesuaikan warna dengan tema hijau Dwijaya Mebel */
        .btn-primary {
            background-color: #3d5a4a !important;
            border-color: #3d5a4a !important;
        }
        .text-primary {
            color: #3d5a4a !important;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-light">

    {{-- Bagian Utama --}}
    <main>
        @yield('content')
    </main>

    {{-- Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>