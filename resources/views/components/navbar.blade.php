<nav class="z-50 bg-[rgba(255,255,255,0.85)] backdrop-blur-md border-b border-[rgba(255,255,255,0.2)] shadow-[0px_4px_30px_0px_rgba(27,94,32,0.05)]" x-data="{ mobileMenuOpen: false }">
    <div class="flex h-[65px] items-center justify-between px-4 lg:px-8 max-w-[1536px] mx-auto">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex gap-[7px] items-center shrink-0">
            <div class="h-[48px] lg:h-[73px] w-[81px] lg:w-[124px] relative shrink-0">
                <img alt="Patriot Metric" class="absolute inset-0 max-w-none object-cover lg:object-contain pointer-events-none size-full" src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" />
            </div>
            <div class="hidden lg:flex flex-col h-[32px] items-start px-2">
                <div class="bg-[#cbd5e1] h-[32px] w-px"></div>
            </div>
            <div class="hidden lg:flex gap-[10px] items-center">
                <div class="relative size-[44px] shrink-0">
                    <img alt="UPN Veteran Jatim" class="absolute inset-0 max-w-none object-cover pointer-events-none size-full" src="{{ asset('assets/images/199dc2ebf1e9cecf5218f4b20951209708831231.png') }}" />
                </div>
                <div class="flex flex-col font-['Plus_Jakarta_Sans',sans-serif] font-bold h-[25px] justify-center leading-[12.5px] text-[#64748b] text-[10px] uppercase w-[237px]">
                    <p>Universitas Pembangunan nasional "veteran" jawa timur</p>
                </div>
            </div>
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden lg:flex gap-1 items-center">
            <a href="{{ url('/') }}" class="px-3 py-2 rounded-2xl font-['Plus_Jakarta_Sans',sans-serif] text-[14px] whitespace-nowrap transition-colors {{ request()->is('/') ? 'font-semibold text-[#1b5e20]' : 'font-medium text-[#45556c] hover:text-[#1b5e20]' }}">
                Beranda
            </a>

            {{-- Tentang Kami Dropdown --}}
            <div class="relative" @mouseenter="dropdownOpen = true" @mouseleave="dropdownOpen = false" x-data="{ dropdownOpen: false }">
                <button class="flex items-center gap-1 px-3 py-2 rounded-2xl font-['Plus_Jakarta_Sans',sans-serif] text-[14px] whitespace-nowrap transition-colors {{ request()->is('profile') || request()->is('visi-misi') || request()->is('tim') ? 'font-semibold text-[#1b5e20]' : 'font-medium text-[#45556c] hover:text-[#1b5e20]' }}">
                    Tentang Kami
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform opacity-50" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="dropdownOpen" x-transition class="absolute top-full left-0 pt-1 z-50 w-full" style="display: none;">
                    <div class="bg-white rounded-xl shadow-lg border border-[#f1f5f9] py-2 min-w-[180px]">
                        <a href="{{ url('/profile') }}" @click="dropdownOpen = false" class="block px-4 py-2.5 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] transition-colors {{ request()->is('profile') ? 'font-semibold text-[#1b5e20] bg-[rgba(27,94,32,0.05)]' : 'font-medium text-[#45556c] hover:text-[#1b5e20] hover:bg-[rgba(27,94,32,0.05)]' }}">
                            Profile
                        </a>
                        <a href="{{ url('/visi-misi') }}" @click="dropdownOpen = false" class="block px-4 py-2.5 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] transition-colors {{ request()->is('visi-misi') ? 'font-semibold text-[#1b5e20] bg-[rgba(27,94,32,0.05)]' : 'font-medium text-[#45556c] hover:text-[#1b5e20] hover:bg-[rgba(27,94,32,0.05)]' }}">
                            Visi & Misi
                        </a>
                        <a href="{{ url('/tim') }}" @click="dropdownOpen = false" class="block px-4 py-2.5 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] transition-colors {{ request()->is('tim') ? 'font-semibold text-[#1b5e20] bg-[rgba(27,94,32,0.05)]' : 'font-medium text-[#45556c] hover:text-[#1b5e20] hover:bg-[rgba(27,94,32,0.05)]' }}">
                            Tim
                        </a>
                    </div>
                </div>
            </div>

            {{-- Informasi Dropdown --}}
            <div class="relative" @mouseenter="dropdownInfoOpen = true" @mouseleave="dropdownInfoOpen = false" x-data="{ dropdownInfoOpen: false }">
                <button class="flex items-center gap-1 px-3 py-2 rounded-2xl font-['Plus_Jakarta_Sans',sans-serif] text-[14px] whitespace-nowrap transition-colors {{ request()->is('penghargaan') ? 'font-semibold text-[#1b5e20]' : 'font-medium text-[#45556c] hover:text-[#1b5e20]' }}">
                    Informasi
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform opacity-50" :class="dropdownInfoOpen ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="dropdownInfoOpen" x-transition class="absolute top-full left-0 pt-1 z-50 w-full" style="display: none;">
                    <div class="bg-white rounded-xl shadow-lg border border-[#f1f5f9] py-2 min-w-[180px]">
                        <a href="{{ url('/penghargaan') }}" @click="dropdownInfoOpen = false" class="block px-4 py-2.5 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] transition-colors {{ request()->is('penghargaan') ? 'font-semibold text-[#1b5e20] bg-[rgba(27,94,32,0.05)]' : 'font-medium text-[#45556c] hover:text-[#1b5e20] hover:bg-[rgba(27,94,32,0.05)]' }}">
                            Penghargaan 2025
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ url('/panduan') }}" class="px-3 py-2 rounded-2xl font-['Plus_Jakarta_Sans',sans-serif] text-[14px] whitespace-nowrap transition-colors {{ request()->is('panduan') ? 'font-semibold text-[#1b5e20]' : 'font-medium text-[#45556c] hover:text-[#1b5e20]' }}">
                Panduan
            </a>
        </div>

        {{-- Desktop Auth Buttons --}}
        <div class="hidden lg:flex gap-3 items-center">
            <a href="{{ url('/masuk') }}" class="h-[38px] rounded-[20px] border border-[rgba(27,94,32,0.2)] flex items-center justify-center px-5 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1b5e20] hover:bg-[rgba(27,94,32,0.05)] transition-colors">
                Masuk
            </a>
            <a href="{{ url('/daftar') }}" class="h-[36px] rounded-[20px] bg-[#1b5e20] shadow-[0px_10px_15px_0px_rgba(27,94,32,0.25),0px_4px_6px_0px_rgba(27,94,32,0.25)] flex items-center justify-center px-5 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-white hover:bg-[#174d1a] transition-colors">
                Daftar
            </a>
        </div>

        {{-- Mobile Hamburger --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-[#45556c] hover:text-[#1b5e20] focus:outline-none">
            <i x-show="!mobileMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
            <i x-show="mobileMenuOpen" data-lucide="x" class="w-6 h-6" style="display: none;"></i>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition class="lg:hidden absolute top-[65px] left-0 w-full bg-white border-b border-[#e2e8f0] shadow-lg py-4 px-4 flex flex-col gap-4" style="display: none;">
        <div class="flex flex-col gap-2">
            <a href="{{ url('/') }}" class="font-['Plus_Jakarta_Sans',sans-serif] px-2 py-2 text-[14px] font-medium text-[#45556c] hover:text-[#1b5e20]">Beranda</a>
            
            <div x-data="{ mobileDropdown: false }" class="px-2">
                <button @click="mobileDropdown = !mobileDropdown" class="w-full flex justify-between items-center font-['Plus_Jakarta_Sans',sans-serif] text-[14px] py-2 font-medium text-[#45556c]">
                    Tentang Kami
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform opacity-50" :class="mobileDropdown ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="mobileDropdown" class="flex flex-col gap-2 pl-4 mt-2" style="display: none;">
                    <a href="{{ url('/profile') }}" class="font-['Plus_Jakarta_Sans',sans-serif] py-1 text-[14px] font-medium text-[#45556c] hover:text-[#1b5e20]">Profile</a>
                    <a href="{{ url('/visi-misi') }}" class="font-['Plus_Jakarta_Sans',sans-serif] py-1 text-[14px] font-medium text-[#45556c] hover:text-[#1b5e20]">Visi & Misi</a>
                    <a href="{{ url('/tim') }}" class="font-['Plus_Jakarta_Sans',sans-serif] py-1 text-[14px] font-medium text-[#45556c] hover:text-[#1b5e20]">Tim</a>
                </div>
            </div>

            <div x-data="{ mobileInfo: false }" class="px-2">
                <button @click="mobileInfo = !mobileInfo" class="w-full flex justify-between items-center font-['Plus_Jakarta_Sans',sans-serif] text-[14px] py-2 font-medium text-[#45556c]">
                    Informasi
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform opacity-50" :class="mobileInfo ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="mobileInfo" class="flex flex-col gap-2 pl-4 mt-2" style="display: none;">
                    <a href="{{ url('/penghargaan') }}" class="font-['Plus_Jakarta_Sans',sans-serif] py-1 text-[14px] font-medium text-[#45556c] hover:text-[#1b5e20]">Penghargaan 2026</a>
                </div>
            </div>

            <a href="{{ url('/panduan') }}" class="font-['Plus_Jakarta_Sans',sans-serif] px-2 py-2 text-[14px] font-medium text-[#45556c] hover:text-[#1b5e20]">Panduan</a>
        </div>
        
        <div class="flex flex-col gap-3 pt-4 px-2 border-t border-[#f1f5f9]">
            <a href="{{ url('/masuk') }}" class="w-full h-[40px] rounded-[20px] border border-[rgba(27,94,32,0.2)] flex items-center justify-center font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1b5e20]">Masuk</a>
            <a href="{{ url('/daftar') }}" class="w-full h-[40px] rounded-[20px] bg-[#1b5e20] flex items-center justify-center font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-white">Daftar</a>
        </div>
    </div>
</nav>
