@props(['hideNav' => false, 'hideFooter' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriot Metric</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex flex-col font-['Plus_Jakarta_Sans',sans-serif]">
    @if(!$hideNav)
        <x-navbar />
    @endif
    
    <main class="flex-1">
        {{ $slot }}
    </main>

    @if(!$hideFooter)
        <x-footer />
    @endif
</body>
</html>
