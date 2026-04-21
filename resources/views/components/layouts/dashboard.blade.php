<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriot Metric - {{ $title ?? 'Dashboard' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased bg-[#f5f5f5]" style="font-family: 'Plus Jakarta Sans', sans-serif;"
      x-data="{ mobileMenuOpen: false, showBar: true, lastPos: 0, threshold: 25 }"
      @scroll.window="showBar = (window.pageYOffset < lastPos - threshold || window.pageYOffset < 150); lastPos = window.pageYOffset"
      x-init="$nextTick(() => { lucide.createIcons() })">

    {{-- ============================================================ --}}
    {{-- HEADER: Bisa diedit di components/dashboard/header.blade.php --}}
    {{-- ============================================================ --}}
    <header class="bg-white fixed top-0 left-0 w-full z-50 transition-transform duration-300"
            :class="showBar ? 'translate-y-0' : '-translate-y-full'"
            style="box-shadow: 0 1px 0 #e0e0e0;">
        <x-dashboard.header />
        {{-- NAVBAR: Bisa diedit di components/dashboard/navbar.blade.php --}}
        <x-dashboard.navbar />
    </header>

    {{-- Spacer to offset fixed header --}}
    <div class="h-[120px]"></div>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT                                                  --}}
    {{-- ============================================================ --}}
    <main class="min-h-[calc(100vh-120px)] bg-[#f5f5f5]">
        {{ $slot }}
    </main>

    {{-- Global Modals --}}
    <x-dashboard.password-modal />

</body>
</html>
