{{-- ===================================================== --}}
{{-- DASHBOARD HEADER - Baris 1: Logo + Info User         --}}
{{-- Customize: nama user, institusi, avatar di sini      --}}
{{-- ===================================================== --}}



    {{-- Header Row --}}
    <div class="flex items-center justify-between px-6 md:px-10 h-[72px] border-b border-[#e0e0e0]">

        {{-- Logo kiri --}}
        <a href="{{ route('dashboard.index') }}" class="flex items-center shrink-0">
            <div class="h-[48px] lg:h-[73px] w-[81px] lg:w-[124px] relative shrink-0">
                <img alt="Patriot Metric" class="absolute inset-0 max-w-none object-cover lg:object-contain pointer-events-none size-full" src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" />
            </div>
        </a>

        {{-- User Info kanan + Avatar Dropdown --}}
        <div class="flex items-center gap-3 shrink-0" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
            <div class="text-right hidden sm:block">
                {{-- ✏️ Ganti: nama user --}}
                <p class="font-bold text-[#1d293d] text-[14px] leading-[20px]">Euis Nurul Hidayah</p>
                {{-- ✏️ Ganti: nama institusi --}}
                <p class="font-medium text-[#62748e] text-[11px] leading-[16px] uppercase tracking-wide">
                    Universitas Pembangunan Nasional Veteran Jawa Timur
                </p>
            </div>

            {{-- Avatar Dropdown Trigger --}}
            <div class="relative">
                <button @click="userMenuOpen = !userMenuOpen"
                        class="w-[40px] h-[40px] bg-[#1b5e20] rounded-full flex items-center justify-center shrink-0 hover:bg-[#155017] transition-colors ring-2 ring-transparent hover:ring-[#1b5e20]/20 focus:outline-none">
                    <span class="text-white font-bold text-[13px] tracking-wide">UPN</span>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="userMenuOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                     class="absolute right-0 top-full mt-2 w-[200px] bg-white rounded-xl shadow-xl border border-[#f1f5f9] py-1.5 z-[60]"
                     style="display:none;">
                    {{-- User info in dropdown (mobile) --}}
                    <div class="sm:hidden px-4 py-2.5 border-b border-[#f1f5f9] mb-1">
                        <p class="font-bold text-[#1d293d] text-[13px] leading-[18px]">Euis Nurul Hidayah</p>
                        <p class="text-[#62748e] text-[11px] leading-[14px]">UPN Veteran Jawa Timur</p>
                    </div>

                    {{-- Ganti Password Option --}}
                    <button @click="userMenuOpen = false; $dispatch('open-password-modal')"
                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-[13px] font-medium text-[#45556c] hover:text-[#1b5e20] hover:bg-[#f0fdf4] transition-colors">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                        Ganti Password
                    </button>

                    <div class="border-t border-[#f1f5f9] mx-3 my-1"></div>

                    {{-- Keluar Option --}}
                    <a href="{{ url('/') }}"
                       class="w-full flex items-center gap-2.5 px-4 py-2.5 text-[13px] font-medium text-[#e53935] hover:text-[#b71c1c] hover:bg-red-50 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

