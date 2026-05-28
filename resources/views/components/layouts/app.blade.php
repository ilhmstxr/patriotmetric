@props(['hideNav' => false, 'hideFooter' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriot Metric</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.webp') }}" />

    <!-- SEO Meta Tags -->
    <meta name="description" content="Patriot Metric - UPN Veteran JATIM University Ranking untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi Indonesia.">
    <meta name="keywords" content="patriot metric, university ranking, bela negara, pendidikan tinggi, UPN Veteran Jatim, pemeringkatan universitas">
    <meta name="author" content="UPN Veteran Jawa Timur">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Patriot Metric - University Ranking Bela Negara">
    <meta property="og:description" content="Inisiatif pemeringkatan nasional untuk mengukur nilai-nilai bela negara di perguruan tinggi Indonesia.">
    <meta property="og:image" content="{{ asset('assets/images/Banner Email Patriot Metric.png') }}">
    <meta property="og:site_name" content="Patriot Metric">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Patriot Metric - University Ranking Bela Negara">
    <meta name="twitter:description" content="Inisiatif pemeringkatan nasional untuk mengukur nilai-nilai bela negara di perguruan tinggi Indonesia.">
    <meta name="twitter:image" content="{{ asset('assets/images/Banner Email Patriot Metric.png') }}"
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireStyles
</head>
<body class="antialiased min-h-screen flex flex-col font-['Plus_Jakarta_Sans',sans-serif]"
      x-data="{ showBar: true, lastPos: 0, threshold: 50 }"
      @scroll.window="
          const cur = window.pageYOffset;
          if (cur < 10) { showBar = true; }
          else if (cur < lastPos - threshold) { showBar = true; }
          else if (cur > lastPos + 10) { showBar = false; }
          lastPos = cur;
      "
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

    @livewireScripts
</body>
</html>
