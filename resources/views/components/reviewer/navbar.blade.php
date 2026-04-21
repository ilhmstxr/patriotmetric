{{-- ===================================================== --}}
{{-- NAVBAR REVIEWER - Baris 2: Horizontal Navigation    --}}
{{-- ===================================================== --}}

{{-- DESKTOP NAV --}}
<nav class="hidden md:flex items-center px-6 md:px-10 h-[48px] gap-1">

    {{-- Role Indicator (Optional) --}}
    <div class="flex items-center gap-[8px] mr-2">
        <div class="h-[24px] px-[8px] bg-[#1b5e20] text-white text-[11px] font-bold rounded flex items-center justify-center uppercase tracking-wider">
            Reviewer
        </div>
    </div>

    <div class="w-px h-[20px] bg-[#e0e0e0] mx-1"></div>

    {{-- ===== NAV LINKS ===== --}}

    {{-- Dashboard / Plotting --}}
    @php $isDashboard = request()->routeIs('reviewer.index') || request()->routeIs('reviewer.submitter_detail'); @endphp
    <a href="{{ route('reviewer.index') }}"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative
              {{ $isDashboard ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]' }}">
        <i data-lucide="layout-dashboard" class="w-[15px] h-[15px]"></i>
        Dashboard Utama
        @if($isDashboard)
            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"></span>
        @endif
    </a>

    {{-- Panduan Penilaian --}}
    @php $isPanduan = request()->routeIs('reviewer.panduan'); @endphp
    <a href="{{ route('reviewer.panduan') }}"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative
              {{ $isPanduan ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]' }}">
        <i data-lucide="help-circle" class="w-[15px] h-[15px]"></i>
        Panduan Penilaian
        @if($isPanduan)
            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"></span>
        @endif
    </a>

    {{-- Riwayat Penilaian --}}
    <a href="#"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative text-[#45556c] hover:text-[#1b5e20]">
        <i data-lucide="history" class="w-[15px] h-[15px]"></i>
        Riwayat Penilaian
    </a>

    <div class="flex-1"></div>
</nav>

{{-- MOBILE NAV TOGGLE --}}
<div class="md:hidden flex items-center justify-between px-4 h-[48px] border-t border-[#e0e0e0]">
    <div class="flex items-center gap-[8px]">
        <div class="h-[20px] px-[6px] bg-[#1b5e20] text-white text-[10px] font-bold rounded flex items-center justify-center uppercase">
            REV
        </div>
        <span class="text-[13px] font-semibold text-[#1d293d]">{{ $title ?? 'Reviewer Area' }}</span>
    </div>
    
    <button @click="mobileMenuOpen = !mobileMenuOpen"
            class="p-2 text-[#45556c] hover:text-[#1b5e20] transition-colors">
        <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-5 h-5"></i>
        <i data-lucide="x" x-show="mobileMenuOpen" class="w-5 h-5" style="display:none"></i>
    </button>
</div>

{{-- MOBILE MENU DROPDOWN --}}
<div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-[#e0e0e0] bg-white" style="display:none;">
    <div class="px-4 py-3 space-y-1">
        <a href="{{ route('reviewer.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors
                  {{ $isDashboard ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            Dashboard Utama
        </a>
        <a href="{{ route('reviewer.panduan') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors
                  {{ $isPanduan ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]' }}">
            <i data-lucide="help-circle" class="w-4 h-4"></i>
            Panduan Penilaian
        </a>
        <a href="#"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors text-[#45556c] hover:bg-[#f5f5f5]">
            <i data-lucide="history" class="w-4 h-4"></i>
            Riwayat Penilaian
        </a>
    </div>
</div>
