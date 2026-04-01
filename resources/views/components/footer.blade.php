<footer class="bg-[#1b5e20] overflow-hidden relative w-full">
    <div class="max-w-[1536px] mx-auto px-8 pt-16 pb-0 flex flex-col gap-16">
        {{-- Main Content --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
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
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold leading-[28px] text-[#d4af37] text-[18px]">Tautan Cepat</p>
                <div class="flex flex-col gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3.5">
                        <div class="bg-[#d4af37] rounded-full size-[6px] shrink-0"></div>
                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.8)]">Beranda</span>
                    </a>
                    <a href="{{ url('/profile') }}" class="flex items-center gap-3.5">
                        <div class="bg-[#d4af37] rounded-full size-[6px] shrink-0"></div>
                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.8)]">Tentang Kami</span>
                    </a>
                    <a href="{{ url('/pemenang') }}" class="flex items-center gap-3.5">
                        <div class="bg-[#d4af37] rounded-full size-[6px] shrink-0"></div>
                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.8)]">Pemenang</span>
                    </a>
                    <a href="{{ url('/panduan') }}" class="flex items-center gap-3.5">
                        <div class="bg-[#d4af37] rounded-full size-[6px] shrink-0"></div>
                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.8)]">Panduan</span>
                    </a>
                </div>
            </div>

            {{-- Hubungi Kami --}}
            <div class="flex flex-col gap-6">
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold leading-[28px] text-[#d4af37] text-[18px]">Hubungi Kami</p>
                <div class="flex flex-col gap-4">
                    <div class="flex gap-3 items-start">
                        <svg class="shrink-0 size-5 mt-0.5" fill="none" viewBox="0 0 20 20">
                            <path d="M17.5 8.33333C17.5 14.1667 10 19.1667 10 19.1667C10 19.1667 2.5 14.1667 2.5 8.33333C2.5 6.34421 3.29018 4.43655 4.6967 3.03003C6.10322 1.62351 8.01088 0.833332 10 0.833332C11.9891 0.833332 13.8968 1.62351 15.3033 3.03003C16.7098 4.43655 17.5 6.34421 17.5 8.33333Z" stroke="#D4AF37" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M10 10.8333C11.3807 10.8333 12.5 9.71404 12.5 8.33333C12.5 6.95262 11.3807 5.83333 10 5.83333C8.61929 5.83333 7.5 6.95262 7.5 8.33333C7.5 9.71404 8.61929 10.8333 10 10.8333Z" stroke="#D4AF37" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                        </svg>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.8)]">
                            Jl. Rungkut Madya No.1, Gn. Anyar, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294
                        </p>
                    </div>
                    <div class="flex gap-3 items-center">
                        <svg class="shrink-0 size-5" fill="none" viewBox="0 0 20 20">
                            <path d="M18.3083 14.275V16.775C18.3095 17.0091 18.2627 17.2409 18.1706 17.4553C18.0785 17.6697 17.9432 17.862 17.7733 18.0193C17.6033 18.1767 17.4023 18.2959 17.1826 18.3694C16.9628 18.4428 16.7293 18.469 16.4967 18.4458C13.918 18.1675 11.4396 17.3064 9.25835 15.9333C7.23201 14.6864 5.51367 12.968 4.26668 10.9417C2.88835 8.75064 2.02695 6.26049 1.75418 3.67C1.73102 3.43812 1.757 3.20423 1.82991 2.98429C1.90282 2.76435 2.02112 2.56316 2.17736 2.39302C2.3336 2.22288 2.52451 2.08754 2.73754 1.99529C2.95057 1.90303 3.18102 1.85601 3.41418 1.85748H5.91418C6.32581 1.85348 6.72499 1.99546 7.03839 2.25784C7.35179 2.52022 7.55877 2.88583 7.62418 3.29248C7.74604 4.10507 7.96022 4.90116 8.26251 5.66581C8.37705 5.95002 8.40784 6.26134 8.35142 6.56242C8.29499 6.8635 8.15371 7.14175 7.94418 7.36415L6.86585 8.44248C8.02163 10.4812 9.68549 12.1451 11.7242 13.3008L12.8025 12.2225C13.0249 12.013 13.3032 11.8717 13.6043 11.8153C13.9053 11.7588 14.2167 11.7896 14.5009 11.9042C15.2655 12.2065 16.0616 12.4206 16.8742 12.5425C17.2853 12.6084 17.6547 12.8189 17.918 13.1375C18.1813 13.4561 18.3208 13.861 18.3083 14.275Z" stroke="#D4AF37" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                        </svg>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.8)]">+62-341-575797</p>
                    </div>
                    <div class="flex gap-3 items-center">
                        <svg class="shrink-0 size-5" fill="none" viewBox="0 0 20 20">
                            <path d="M3.33333 3.33333H16.6667C17.5833 3.33333 18.3333 4.08333 18.3333 4.99999V15C18.3333 15.9167 17.5833 16.6667 16.6667 16.6667H3.33333C2.41667 16.6667 1.66667 15.9167 1.66667 15V4.99999C1.66667 4.08333 2.41667 3.33333 3.33333 3.33333Z" stroke="#D4AF37" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M18.3333 5L10 10.8333L1.66667 5" stroke="#D4AF37" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                        </svg>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.8)]">patriot@upnjatim.ac.id</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="flex items-center justify-between py-4 border-t border-[rgba(255,255,255,0.1)]">
            <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.6)]">
                &copy; {{ date('Y') }} Patriot Metric - Universitas Pembangunan Nasional "Veteran" Jawa Timur. Hak Cipta Dilindungi.
            </p>
            <div class="flex gap-4 items-center">
                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.6)]">Syarat &amp; Ketentuan</span>
                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.6)]">&bull;</span>
                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[14px] text-[rgba(255,255,255,0.6)]">Kebijakan Privasi</span>
            </div>
        </div>
    </div>
</footer>
