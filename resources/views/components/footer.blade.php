<footer class="bg-[#f8fafc] border-t border-[#e2e8f0] overflow-hidden relative w-full">
    <div class="max-w-[1536px] mx-auto px-8 pt-16 pb-0 flex flex-col gap-6">
        {{-- Main Content --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Logo --}}
            <div class="flex flex-col items-start">
                <div class="h-[81px] w-[322px] relative">
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <img alt="Patriot Metric" class="absolute h-[236.75%] left-[-0.11%] max-w-none top-[-58.29%] w-full" src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" />
                    </div>
                </div>
            </div>

            {{-- Tautan Cepat --}}
            <div class="flex flex-col gap-6">
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold leading-[28px] text-[#1B5E20] text-[18px]">Tautan Cepat</p>
                <div class="flex flex-col gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
                        <div class="bg-[#1B5E20] rounded-full size-[6px] shrink-0 transition-transform group-hover:scale-110"></div>
                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C] group-hover:text-[#1B5E20] transition-colors">Beranda</span>
                    </a>
                    
                    <div x-data="{ tentangOpen: false }" class="flex flex-col">
                        <button @click="tentangOpen = !tentangOpen" class="flex items-center justify-between w-[200px] group py-1 text-left">
                            <div class="flex items-center gap-3.5">
                                <div class="bg-[#1B5E20] rounded-full size-[6px] shrink-0"></div>
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">Tentang Kami</span>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300 text-[#45556C] opacity-60" :class="tentangOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="tentangOpen" x-transition.opacity.duration.300ms class="flex flex-col gap-2.5 pl-[20px] border-l-2 border-[#f1f5f9] ml-[2px] mt-2 mb-1" style="display: none;">
                            <a href="{{ url('/profile') }}" class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[13px] text-[#62748E] hover:text-[#1B5E20] hover:translate-x-1 transition-all">
                                Profil
                            </a>
                            <a href="{{ url('/visi-misi') }}" class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[13px] text-[#62748E] hover:text-[#1B5E20] hover:translate-x-1 transition-all">
                                Visi &amp; Misi
                            </a>
                            <a href="{{ url('/tim') }}" class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[13px] text-[#62748E] hover:text-[#1B5E20] hover:translate-x-1 transition-all">
                                Tim
                            </a>
                        </div>
                    </div>

                    <div x-data="{ infoOpen: false }" class="flex flex-col">
                        <button @click="infoOpen = !infoOpen" class="flex items-center justify-between w-[200px] group py-1 text-left">
                            <div class="flex items-center gap-3.5">
                                <div class="bg-[#1B5E20] rounded-full size-[6px] shrink-0"></div>
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">Informasi</span>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300 text-[#45556C] opacity-60" :class="infoOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="infoOpen" x-transition.opacity.duration.300ms class="flex flex-col gap-2.5 pl-[20px] border-l-2 border-[#f1f5f9] ml-[2px] mt-2 mb-1" style="display: none;">
                            <a href="{{ url('/penghargaan') }}" class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[13px] text-[#62748E] hover:text-[#1B5E20] hover:translate-x-1 transition-all">
                                Penghargaan 2025
                            </a>
                        </div>
                    </div>

                    <a href="{{ url('/panduan') }}" class="flex items-center gap-3.5 group mt-1">
                        <div class="bg-[#1B5E20] rounded-full size-[6px] shrink-0 transition-transform group-hover:scale-110"></div>
                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C] group-hover:text-[#1B5E20] transition-colors">Panduan</span>
                    </a>
                </div>
            </div>

            {{-- Hubungi Kami --}}
            <div class="flex flex-col gap-6">
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold leading-[28px] text-[#1B5E20] text-[18px]">Hubungi Kami</p>
                <div class="flex flex-col gap-4">
                    <div class="flex gap-3 items-start">
                        <i data-lucide="map-pin" class="shrink-0 size-5 mt-0.5 text-[#1B5E20]"></i>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">
                            Jl. Rungkut Madya No.1, Gn. Anyar, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294
                        </p>
                    </div>
                    <div class="flex gap-3 items-center">
                        <i data-lucide="phone" class="shrink-0 size-5 text-[#1B5E20]"></i>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">+62-341-575797</p>
                    </div>
                    <div class="flex gap-3 items-center">
                        <i data-lucide="mail" class="shrink-0 size-5 text-[#1B5E20]"></i>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">patriot@upnjatim.ac.id</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="flex flex-col md:flex-row items-center justify-between py-4 border-t border-[#e2e8f0] gap-4 text-center md:text-left mt-8">
            <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">
                &copy; {{ date('Y') }} Patriot Metric - Universitas Pembangunan Nasional "Veteran" Jawa Timur. Hak Cipta Dilindungi.
            </p>
            <div class="flex gap-4 items-center">
                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">Syarat &amp; Ketentuan</span>
                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">&bull;</span>
                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[#45556C]">Kebijakan Privasi</span>
            </div>
        </div>
    </div>
</footer>
