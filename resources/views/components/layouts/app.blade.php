@props(['hideNav' => false, 'hideFooter' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriot Metric</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="antialiased min-h-screen flex flex-col font-['Plus_Jakarta_Sans',sans-serif]"
      x-data="{ showBar: true, lastPos: 0, threshold: 25 }"
      @scroll.window="showBar = (window.pageYOffset < lastPos - threshold || window.pageYOffset < 100); lastPos = window.pageYOffset"
      x-init="$nextTick(() => { lucide.createIcons() })">
    @if(!$hideNav)
        <header class="fixed top-0 left-0 w-full z-50 transition-transform duration-300"
                :class="showBar ? 'translate-y-0' : '-translate-y-full'">
            <x-navbar />
        </header>
        <div class="h-[65px]"></div>
    @endif
    
    <main class="flex-1">
        {{ $slot }}
    </main>

    @if(!$hideFooter)
        <x-footer />
    @endif
</body>
</html>
