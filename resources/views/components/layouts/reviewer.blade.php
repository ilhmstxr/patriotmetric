<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriot Metric Reviewer - {{ $title ?? 'Dashboard' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="antialiased font-['Plus_Jakarta_Sans',sans-serif]" 
      x-data="{ sidebarOpen: false, showBar: true, lastPos: 0, threshold: 25 }" 
      x-init="$nextTick(() => { lucide.createIcons() })">
    <div class="flex flex-col md:flex-row h-screen w-full bg-white relative overflow-hidden">
        {{-- Mobile Header with Hamburger --}}
        <div class="md:hidden flex fixed top-0 left-0 items-center justify-between px-[20px] py-[16px] bg-white border-b border-[#e2e8f0] z-30 w-full shrink-0 shadow-sm transition-transform duration-300"
             :class="showBar ? 'translate-y-0' : '-translate-y-full'">
            <img src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" alt="Patriot Metric Logo" class="h-[36px] object-cover" />
            <button @click="sidebarOpen = !sidebarOpen" class="text-[#1d293d] focus:outline-none p-1 border border-gray-200 rounded justify-center items-center flex">
                <i data-lucide="menu" class="w-6 h-6" x-show="!sidebarOpen"></i>
                <i data-lucide="x" class="w-6 h-6" x-show="sidebarOpen" style="display: none;"></i>
            </button>
        </div>

        {{-- Mobile Header Spacer --}}
        <div class="md:hidden h-[69px] w-full shrink-0"></div>

        {{-- Mobile Sidebar Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="md:hidden fixed inset-0 bg-[#1d293d]/50 z-30" style="display: none;" x-transition.opacity></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:relative top-0 left-0 h-full z-40 transition-transform duration-300 transform md:translate-x-0 w-[260px] md:w-[280px] bg-white shrink-0 flex flex-col shadow-[0_4px_6px_rgba(0,0,0,0.1)] border-r border-[#c89600]">
            <div class="absolute bg-[#c89600] h-full right-0 top-0 w-px z-10"></div>
            
            <div class="h-[70px] md:h-[80px] flex items-center justify-center shrink-0 border-b border-[#e2e8f0]">
                <img src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" alt="Patriot Metric Logo" class="h-[40px] md:h-[49px] object-cover object-center" />
            </div>

            <div class="flex flex-col items-center pt-[20px] md:pt-[24px] pb-[24px] md:pb-[32px] shrink-0 border-b border-[#e2e8f0]">
                <div class="w-[60px] md:w-[72px] h-[60px] md:h-[72px] bg-[#0ea5e9] rounded-full flex items-center justify-center text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] md:text-[24px] tracking-[0.6px] leading-[32px] mb-[12px] md:mb-[16px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
                    REV
                </div>
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[#1d293d] text-[15px] md:text-[16px] leading-[24px] tracking-[0.4px] whitespace-nowrap">
                    Reviewer Patriot
                </p>
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[#62748e] text-[10px] leading-[14px] text-center mt-[4px] uppercase px-[24px]">
                    TIM PENILAI PATRIOT METRIC
                </p>
            </div>

            <div class="flex-1 px-[16px] py-[24px] space-y-[8px]">
                {{-- Dashboard Utama --}}
                <a href="{{ route('reviewer.index') }}" class="flex items-center gap-[12px] h-[44px] px-[16px] rounded-[8px] transition-all duration-200 {{ request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail') ? 'bg-[#1b5e20] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]' : 'bg-transparent hover:bg-slate-50' }}">
                    <div class="relative shrink-0 w-[20px] h-[20px] flex items-center justify-center">
                        <i data-lucide="layout-dashboard" class="w-[18px] h-[18px] {{ request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail') ? 'text-white' : 'text-[#90A1B9]' }}"></i>
                    </div>
                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] leading-[20px] {{ request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail') ? 'text-white' : 'text-[#45556c]' }}">
                        Dashboard Utama
                    </span>
                </a>

                {{-- Panduan Penilaian --}}
                <a href="{{ route('reviewer.panduan') }}" class="flex items-center gap-[12px] h-[44px] px-[16px] rounded-[8px] transition-all duration-200 {{ request()->routeIs('reviewer.panduan') ? 'bg-[#1b5e20] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]' : 'bg-transparent hover:bg-slate-50' }}">
                    <div class="relative shrink-0 w-[20px] h-[20px] flex items-center justify-center">
                        <i data-lucide="help-circle" class="w-[18px] h-[18px] {{ request()->routeIs('reviewer.panduan') ? 'text-white' : 'text-[#90A1B9]' }}"></i>
                    </div>
                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] leading-[20px] {{ request()->routeIs('reviewer.panduan') ? 'text-white' : 'text-[#45556c]' }}">
                        Panduan Penilaian
                    </span>
                </a>
            </div>

            <div class="p-[16px] mt-auto shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-[12px] h-[44px] px-[16px] w-full rounded-[8px] hover:bg-red-50 transition-colors">
                    <div class="relative shrink-0 w-[20px] h-[20px] flex items-center justify-center">
                        <i data-lucide="log-out" class="w-[18px] h-[18px] text-[#ef4444]"></i>
                    </div>
                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[#ef4444] text-[14px] leading-[20px]">
                        Keluar
                    </span>
                </a>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-white">
            <div class="flex-1 overflow-auto bg-white relative"
                 @scroll="showBar = ($el.scrollTop < lastPos - threshold || $el.scrollTop < 100); lastPos = $el.scrollTop">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
