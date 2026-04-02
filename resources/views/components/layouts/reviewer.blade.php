<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriot Metric Reviewer - {{ $title ?? 'Dashboard' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="antialiased font-['Plus_Jakarta_Sans',sans-serif]" x-data x-init="$nextTick(() => { lucide.createIcons() })">
    <div class="flex h-screen w-full bg-white">
        {{-- Sidebar --}}
        <aside class="w-[280px] bg-white h-full shrink-0 flex flex-col relative shadow-[0_4px_6px_rgba(0,0,0,0.1)] border-r border-[#c89600]">
            <div class="absolute bg-[#c89600] h-full right-0 top-0 w-px z-10"></div>
            
            <div class="h-[80px] flex items-center justify-center shrink-0 border-b border-[#e2e8f0]">
                <img src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" alt="Patriot Metric Logo" class="h-[49px] w-[183px] object-cover object-center" />
            </div>

            <div class="flex flex-col items-center pt-[24px] pb-[32px] shrink-0 border-b border-[#e2e8f0]">
                <div class="w-[72px] h-[72px] bg-[#0ea5e9] rounded-full flex items-center justify-center text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] tracking-[0.6px] leading-[32px] mb-[16px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
                    REV
                </div>
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[#1d293d] text-[16px] leading-[24px] tracking-[0.4px] whitespace-nowrap">
                    Reviewer Patriot
                </p>
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[#62748e] text-[10px] leading-[14px] text-center mt-[4px] uppercase px-[24px]">
                    TIM PENILAI PATRIOT METRIC
                </p>
            </div>

            <div class="flex-1 px-[16px] py-[24px] space-y-[8px]">
                {{-- Dashboard Utama --}}
                <a href="{{ route('reviewer.index') }}" class="flex items-center gap-[12px] h-[44px] px-[16px] rounded-[8px] transition-all duration-200 {{ request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail') ? 'bg-[#1b5e20] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]' : 'bg-transparent hover:bg-slate-50' }}">
                    <div class="relative shrink-0 w-[20px] h-[20px]">
                        <svg class="absolute block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
                            <path d="M16.6667 17.5V15.8333C16.6667 14.9493 16.3155 14.1014 15.6903 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66667C5.78261 12.5 4.93476 12.8512 4.30964 13.4763C3.68452 14.1014 3.33333 14.9493 3.33333 15.8333V17.5" stroke="{{ request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail') ? 'white' : '#90A1B9' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M10 9.16667C11.841 9.16667 13.3333 7.67428 13.3333 5.83333C13.3333 3.99238 11.841 2.5 10 2.5C8.15905 2.5 6.66667 3.99238 6.66667 5.83333C6.66667 7.67428 8.15905 9.16667 10 9.16667Z" stroke="{{ request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail') ? 'white' : '#90A1B9' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                        </svg>
                    </div>
                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] leading-[20px] {{ request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail') ? 'text-white' : 'text-[#45556c]' }}">
                        Dashboard Utama
                    </span>
                </a>

                {{-- Panduan Penilaian --}}
                <a href="{{ route('reviewer.panduan') }}" class="flex items-center gap-[12px] h-[44px] px-[16px] rounded-[8px] transition-all duration-200 {{ request()->routeIs('reviewer.panduan') ? 'bg-[#1b5e20] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]' : 'bg-transparent hover:bg-slate-50' }}">
                    <div class="relative shrink-0 w-[20px] h-[20px]">
                        <svg class="absolute block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
                            <path d="M10 18.3333C14.6024 18.3333 18.3333 14.6024 18.3333 10C18.3333 5.39763 14.6024 1.66667 10 1.66667C5.39763 1.66667 1.66667 5.39763 1.66667 10C1.66667 14.6024 5.39763 18.3333 10 18.3333Z" stroke="{{ request()->routeIs('reviewer.panduan') ? 'white' : '#90A1B9' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M7.58334 7.50001C7.58334 6.85968 8.10201 6.34101 8.74234 6.34101H10.8407C11.5363 6.34101 12.1003 6.90501 12.1003 7.60068C12.1003 8.29634 11.5363 8.86034 10.8407 8.86034H10C9.53966 8.86034 9.16667 9.23334 9.16667 9.69368V10.8333" stroke="{{ request()->routeIs('reviewer.panduan') ? 'white' : '#90A1B9' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M10 14.1667H10.0083" stroke="{{ request()->routeIs('reviewer.panduan') ? 'white' : '#90A1B9' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </div>
                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] leading-[20px] {{ request()->routeIs('reviewer.panduan') ? 'text-white' : 'text-[#45556c]' }}">
                        Panduan Penilaian
                    </span>
                </a>
            </div>

            <div class="p-[16px] mt-auto shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-[12px] h-[44px] px-[16px] w-full rounded-[8px] hover:bg-red-50 transition-colors">
                    <div class="relative shrink-0 w-[20px] h-[20px]">
                        <svg class="absolute block w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 20 20">
                            <path d="M7.5 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V4.16667C2.5 3.72464 2.67559 3.30072 2.98816 2.98816C3.30072 2.67559 3.72464 2.5 4.16667 2.5H7.5" stroke="#ef4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M13.3333 14.1667L17.5 10L13.3333 5.83333" stroke="#ef4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M17.5 10H7.5" stroke="#ef4444" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                        </svg>
                    </div>
                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[#ef4444] text-[14px] leading-[20px]">
                        Keluar
                    </span>
                </a>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-white">
            <div class="flex-1 overflow-auto bg-white relative">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
