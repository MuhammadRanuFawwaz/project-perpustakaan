<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perustakaan SMKN 1 Tarumajaya') }}</title>
    <link rel="icon"
        type="image/png"
        href="{{ asset('images/Smkn1Tarumajaya.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="/css/app.css?v=99">
    <link rel="stylesheet" href="/css/layout.css?v=99">
    <link rel="stylesheet" href="/css/sidebar.css?v=99">
    <link rel="stylesheet" href="/css/topbar.css?v=99">
    <link rel="stylesheet" href="/css/dashboard.css?v=99">
    <link rel="stylesheet" href="/css/pengunjung.css?v=99">
    <link rel="stylesheet" href="/css/buku.css?v=99">
    <link rel="stylesheet" href="/css/peminjaman.css?v=99">
    <link rel="stylesheet" href="/css/profile-modal.css?v=99">

</head>

<body class="font-sans antialiased bg-gray-100">

    <main>
        {{ $slot }}
    </main>

</body>

</html>