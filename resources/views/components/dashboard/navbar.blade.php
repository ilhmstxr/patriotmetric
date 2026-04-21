{{-- ===================================================== --}}
{{-- DASHBOARD NAVBAR - Baris 2: Horizontal Navigation    --}}
{{-- Tambah/hapus link nav di sini                        --}}
{{-- ===================================================== --}}

{{-- DESKTOP NAV --}}
<nav class="hidden md:flex items-center px-6 md:px-10 h-[48px] gap-1">

    {{-- Periode Dropdown --}}
    <div class="relative flex items-center" x-data="{ open: false }">
        <button @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-1.5 px-3 h-[48px] text-[13px] font-medium text-[#45556c] hover:text-[#1b5e20] transition-colors">
            <i data-lucide="calendar" class="w-[14px] h-[14px] text-[#62748e]"></i>
            <span>Periode: 2024</span>
            <i data-lucide="chevron-down" class="w-[12px] h-[12px] transition-transform duration-150" :class="open ? 'rotate-180' : ''"></i>
        </button>
        {{-- ✏️ Tambah/hapus opsi periode di sini --}}
        <div x-show="open" x-transition
             class="absolute top-full left-0 mt-1 bg-white border border-[#e0e0e0] rounded-lg shadow-lg py-1 min-w-[140px] z-50"
             style="display:none;">
            <a href="#" class="block px-4 py-2 text-[13px] font-medium text-[#1d293d] hover:bg-[#f5f5f5]">2024</a>
            <a href="#" class="block px-4 py-2 text-[13px] font-medium text-[#1d293d] hover:bg-[#f5f5f5]">2023</a>
            <a href="#" class="block px-4 py-2 text-[13px] font-medium text-[#1d293d] hover:bg-[#f5f5f5]">2022</a>
        </div>
    </div>

    <div class="w-px h-[20px] bg-[#e0e0e0] mx-1"></div>

    {{-- ===== NAV LINKS ===== --}}
    {{-- ✏️ Tambah nav link baru dengan blok <a> yang sama di sini --}}

    {{-- Data Profil --}}
    @php $isDataProfil = request()->routeIs('dashboard.index'); @endphp
    <a href="{{ route('dashboard.index') }}"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative
              {{ $isDataProfil ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]' }}">
        <i data-lucide="user" class="w-[15px] h-[15px]"></i>
        Data Profil
        @if($isDataProfil)
            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"></span>
        @endif
    </a>

    {{-- Form Rubrik --}}
    @php $isRubrik = request()->routeIs('dashboard.rubrik'); @endphp
    <a href="{{ route('dashboard.rubrik') }}"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative
              {{ $isRubrik ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]' }}">
        <i data-lucide="file-text" class="w-[15px] h-[15px]"></i>
        Form Rubrik
        @if($isRubrik)
            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"></span>
        @endif
    </a>

    {{-- Hasil Penilaian --}}
    @php $isHasil = request()->routeIs('dashboard.hasil'); @endphp
    <a href="{{ route('dashboard.hasil') }}"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative
              {{ $isHasil ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]' }}">
        <i data-lucide="bar-chart-2" class="w-[15px] h-[15px]"></i>
        Hasil Penilaian
        @if($isHasil)
            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"></span>
        @endif
    </a>

    {{-- Panduan Pengguna --}}
    @php $isPanduan = request()->routeIs('dashboard.panduan'); @endphp
    <a href="{{ route('dashboard.panduan') }}"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative
              {{ $isPanduan ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]' }}">
        <i data-lucide="help-circle" class="w-[15px] h-[15px]"></i>
        Panduan Pengguna
        @if($isPanduan)
            <span class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"></span>
        @endif
    </a>

    <div class="flex-1"></div>


</nav>

{{-- MOBILE NAV TOGGLE --}}
<div class="md:hidden flex items-center justify-between px-4 h-[48px] border-t border-[#e0e0e0]">
    <span class="text-[13px] font-semibold text-[#1d293d]">{{ $title ?? 'Dashboard' }}</span>
    <button @click="mobileMenuOpen = !mobileMenuOpen"
            class="p-2 text-[#45556c] hover:text-[#1b5e20] transition-colors">
        <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-5 h-5"></i>
        <i data-lucide="x" x-show="mobileMenuOpen" class="w-5 h-5" style="display:none"></i>
    </button>
</div>

{{-- MOBILE MENU DROPDOWN --}}
<div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-[#e0e0e0] bg-white" style="display:none;">
    <div class="px-4 py-3 space-y-1">
        <a href="{{ route('dashboard.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors
                  {{ request()->routeIs('dashboard.index') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]' }}">
            <i data-lucide="user" class="w-4 h-4"></i>
            Data Profil
        </a>
        <a href="{{ route('dashboard.rubrik') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors
                  {{ request()->routeIs('dashboard.rubrik') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]' }}">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Form Rubrik
        </a>
        <a href="{{ route('dashboard.hasil') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors
                  {{ request()->routeIs('dashboard.hasil') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]' }}">
            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
            Hasil Penilaian
        </a>
        <a href="{{ route('dashboard.panduan') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors
                  {{ request()->routeIs('dashboard.panduan') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]' }}">
            <i data-lucide="help-circle" class="w-4 h-4"></i>
            Panduan Pengguna
        </a>

    </div>
</div>
