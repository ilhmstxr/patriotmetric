{{-- ===================================================== --}}
{{-- DASHBOARD NAVBAR - Baris 2: Horizontal Navigation    --}}
{{-- Active state: client-side via Alpine (kompatibel     --}}
{{-- dengan wire:navigate + @persist)                     --}}
{{-- ===================================================== --}}

<div x-data="{
    currentPath: window.location.pathname,
    isActive(path) {
        if (path === '/dashboard') return this.currentPath === '/dashboard';
        return this.currentPath === path || this.currentPath.startsWith(path + '/');
    },
    init() {
        document.addEventListener('livewire:navigated', () => {
            this.currentPath = window.location.pathname;
        });
    }
}">

{{-- DESKTOP NAV --}}
<nav class="hidden md:flex items-center px-6 md:px-10 h-[48px] gap-1">

    {{-- Periode Dropdown --}}
    <div class="relative flex items-center" x-data="{ open: false }">
        <button @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-1.5 px-3 h-[48px] text-[13px] font-medium text-[#45556c] hover:text-[#1b5e20] transition-colors">
            <i data-lucide="calendar" class="w-[14px] h-[14px] text-[#62748e]"></i>
            <span>Periode: 2026</span>
            <i data-lucide="chevron-down" class="w-[12px] h-[12px] transition-transform duration-150" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-transition
             class="absolute top-full left-0 mt-1 bg-white border border-[#e0e0e0] rounded-lg shadow-lg py-1 min-w-[140px] z-50"
             style="display:none;">
            <a href="#" class="block px-4 py-2 text-[13px] font-medium text-[#1d293d] hover:bg-[#f5f5f5]">2026</a>
        </div>
    </div>

    <div class="w-px h-[20px] bg-[#e0e0e0] mx-1"></div>

    {{-- Data Profil --}}
    <a href="{{ route('dashboard.index') }}" wire:navigate
       :class="isActive('{{ route('dashboard.index', [], false) }}') ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]'"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative">
        <i data-lucide="user" class="w-[15px] h-[15px]"></i>
        Data Profil
        <span x-show="isActive('{{ route('dashboard.index', [], false) }}')"
              class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"
              style="display:none;"></span>
    </a>

    {{-- Form Rubrik --}}
    <a href="{{ route('dashboard.rubrik') }}" wire:navigate
       :class="isActive('{{ route('dashboard.rubrik', [], false) }}') ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]'"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative">
        <i data-lucide="file-text" class="w-[15px] h-[15px]"></i>
        Form Rubrik
        <span x-show="isActive('{{ route('dashboard.rubrik', [], false) }}')"
              class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"
              style="display:none;"></span>
    </a>

    {{-- Hasil Penilaian --}}
    <a href="{{ route('dashboard.hasil') }}" wire:navigate
       :class="isActive('{{ route('dashboard.hasil', [], false) }}') ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]'"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative">
        <i data-lucide="bar-chart-2" class="w-[15px] h-[15px]"></i>
        Hasil Penilaian
        <span x-show="isActive('{{ route('dashboard.hasil', [], false) }}')"
              class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"
              style="display:none;"></span>
    </a>

    {{-- Panduan Pengguna --}}
    <a href="{{ route('dashboard.panduan') }}" wire:navigate
       :class="isActive('{{ route('dashboard.panduan', [], false) }}') ? 'text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:text-[#1b5e20]'"
       class="flex items-center gap-2 px-4 h-[48px] text-[13px] font-medium transition-colors relative">
        <i data-lucide="help-circle" class="w-[15px] h-[15px]"></i>
        Petunjuk Pengisian
        <span x-show="isActive('{{ route('dashboard.panduan', [], false) }}')"
              class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#1b5e20] rounded-t"
              style="display:none;"></span>
    </a>

    <div class="flex-1"></div>
</nav>

{{-- MOBILE NAV TOGGLE --}}
<div class="md:hidden flex items-center justify-between px-4 h-[48px] border-t border-[#e0e0e0]">
    <span class="text-[13px] font-semibold text-[#1d293d]">Dashboard</span>
    <button @click="mobileMenuOpen = !mobileMenuOpen"
            class="p-2 text-[#45556c] hover:text-[#1b5e20] transition-colors">
        <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-5 h-5"></i>
        <i data-lucide="x" x-show="mobileMenuOpen" class="w-5 h-5" style="display:none"></i>
    </button>
</div>

{{-- MOBILE MENU DROPDOWN --}}
<div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-[#e0e0e0] bg-white" style="display:none;">
    <div class="px-4 py-3 space-y-1">
        <a href="{{ route('dashboard.index') }}" wire:navigate
           :class="isActive('{{ route('dashboard.index', [], false) }}') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]'"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors">
            <i data-lucide="user" class="w-4 h-4"></i>
            Data Profil
        </a>
        <a href="{{ route('dashboard.rubrik') }}" wire:navigate
           :class="isActive('{{ route('dashboard.rubrik', [], false) }}') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]'"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Form Rubrik
        </a>
        <a href="{{ route('dashboard.hasil') }}" wire:navigate
           :class="isActive('{{ route('dashboard.hasil', [], false) }}') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]'"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors">
            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
            Hasil Penilaian
        </a>
        <a href="{{ route('dashboard.panduan') }}" wire:navigate
           :class="isActive('{{ route('dashboard.panduan', [], false) }}') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-[#45556c] hover:bg-[#f5f5f5]'"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors">
            <i data-lucide="help-circle" class="w-4 h-4"></i>
            Petunjuk Pengisian
        </a>
    </div>
</div>

</div>{{-- end x-data --}}
