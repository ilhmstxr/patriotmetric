<nav class="sticky top-0 z-50 bg-[rgba(255,255,255,0.85)] backdrop-blur-md border-b border-[rgba(255,255,255,0.2)] shadow-[0px_4px_30px_0px_rgba(27,94,32,0.05)]" x-data="{ dropdownOpen: false }">
    <div class="flex h-[65px] items-center justify-between px-8 max-w-[1536px] mx-auto">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex gap-[7px] items-center shrink-0">
            <div class="h-[73px] w-[124px] relative shrink-0">
                <img alt="Patriot Metric" class="absolute inset-0 max-w-none object-cover pointer-events-none size-full" src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" />
            </div>
            <div class="flex flex-col h-[32px] items-start px-2">
                <div class="bg-[#cbd5e1] h-[32px] w-px"></div>
            </div>
            <div class="flex gap-[10px] items-center">
                <div class="relative size-[44px] shrink-0">
                    <img alt="UPN Veteran Jatim" class="absolute inset-0 max-w-none object-cover pointer-events-none size-full" src="{{ asset('assets/images/199dc2ebf1e9cecf5218f4b20951209708831231.png') }}" />
                </div>
                <div class="flex flex-col font-['Plus_Jakarta_Sans',sans-serif] font-bold h-[25px] justify-center leading-[12.5px] text-[#64748b] text-[10px] uppercase w-[237px]">
                    <p>Universitas Pembangunan nasional "veteran" jawa timur</p>
                </div>
            </div>
        </a>

        {{-- Navigation --}}
        <div class="flex gap-1 items-center">
            <a href="{{ url('/') }}" class="px-3 py-2 rounded-2xl font-['Plus_Jakarta_Sans',sans-serif] text-[14px] whitespace-nowrap transition-colors {{ request()->is('/') ? 'font-semibold text-[#1b5e20]' : 'font-medium text-[#45556c] hover:text-[#1b5e20]' }}">
                Beranda
            </a>

            {{-- Tentang Kami Dropdown --}}
            <div class="relative" @mouseenter="dropdownOpen = true" @mouseleave="dropdownOpen = false">
                <button class="flex items-center gap-1 px-3 py-2 rounded-2xl font-['Plus_Jakarta_Sans',sans-serif] text-[14px] whitespace-nowrap transition-colors {{ request()->is('profile') || request()->is('visi-misi') || request()->is('tim') ? 'font-semibold text-[#1b5e20]' : 'font-medium text-[#45556c] hover:text-[#1b5e20]' }}">
                    Tentang Kami
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="transition-transform" :class="dropdownOpen ? 'rotate-180' : ''">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.33" opacity="0.5" />
                    </svg>
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
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="transition-transform" :class="dropdownInfoOpen ? 'rotate-180' : ''">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.33" opacity="0.5" />
                    </svg>
                </button>

                <div x-show="dropdownInfoOpen" x-transition class="absolute top-full left-0 pt-1 z-50 w-full" style="display: none;">
                    <div class="bg-white rounded-xl shadow-lg border border-[#f1f5f9] py-2 min-w-[180px]">
                        <a href="{{ url('/penghargaan') }}" @click="dropdownInfoOpen = false" class="block px-4 py-2.5 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] transition-colors {{ request()->is('penghargaan') ? 'font-semibold text-[#1b5e20] bg-[rgba(27,94,32,0.05)]' : 'font-medium text-[#45556c] hover:text-[#1b5e20] hover:bg-[rgba(27,94,32,0.05)]' }}">
                            Penghargaan 2026
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ url('/panduan') }}" class="px-3 py-2 rounded-2xl font-['Plus_Jakarta_Sans',sans-serif] text-[14px] whitespace-nowrap transition-colors {{ request()->is('panduan') ? 'font-semibold text-[#1b5e20]' : 'font-medium text-[#45556c] hover:text-[#1b5e20]' }}">
                Panduan
            </a>
        </div>

        {{-- Auth Buttons --}}
        <div class="flex gap-3 items-center">
            <a href="{{ url('/masuk') }}" class="h-[38px] rounded-[20px] border border-[rgba(27,94,32,0.2)] flex items-center justify-center px-5 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1b5e20] hover:bg-[rgba(27,94,32,0.05)] transition-colors">
                Masuk
            </a>
            <a href="{{ url('/daftar') }}" class="h-[36px] rounded-[20px] bg-[#1b5e20] shadow-[0px_10px_15px_0px_rgba(27,94,32,0.25),0px_4px_6px_0px_rgba(27,94,32,0.25)] flex items-center justify-center px-5 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-white hover:bg-[#174d1a] transition-colors">
                Daftar
            </a>
        </div>
    </div>
</nav>
