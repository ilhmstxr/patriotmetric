<x-layouts.app>
    <div class="bg-white">
        {{-- Hero Section --}}
        <section class="relative bg-[#0f172b] overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/46257018a5d0ac00852b82184ae3ed30ef9a74e4.png') }}" alt="" class="w-full h-full object-cover opacity-30" />
                <div class="absolute inset-0 bg-gradient-to-r from-[rgba(27,94,32,0.9)] via-[rgba(27,94,32,0.2)] to-transparent"></div>
            </div>
            <div class="relative max-w-[1536px] mx-auto px-8 py-32 md:py-44">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[48px] md:text-[60px] leading-[1.2] text-white max-w-[768px]">
                    Membangun Karakter
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d4af37] to-[#fff085]">Bangsa</span>
                    dari Kampus
                </h1>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[20px] leading-[32.5px] text-[rgba(255,255,255,0.8)] max-w-[616px]">
                    Sebuah inisiatif pemeringkatan nasional yang didedikasikan untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi.
                </p>
                <div class="mt-10 flex gap-4">
                    <a href="{{ url('/daftar') }}" class="bg-[#d4af37] text-[#1d293d] font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] px-8 py-4 rounded-2xl shadow-lg hover:brightness-110 transition flex items-center gap-2">
                        Daftarkan Institusi Anda
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.16667 10H15.8333" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" /><path d="M10 4.16667L15.8333 10L10 15.8333" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" /></svg>
                    </a>
                    <a href="{{ url('/panduan') }}" class="border border-white/30 text-white font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[16px] px-8 py-4 rounded-2xl hover:bg-white/10 transition">
                        Panduan Penilaian
                    </a>
                </div>
            </div>
        </section>

        {{-- About Section --}}
        <section class="bg-[#f8fafc] py-24">
            <div class="max-w-[1536px] mx-auto px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div class="relative">
                            <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] leading-[40px] text-[#1d293d]">Patriot Metric</h2>
                            <div class="bg-[#d4af37] h-1 w-20 rounded-full mt-2"></div>
                        </div>
                        <p class="mt-8 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[29.25px] text-[#45556c]">
                            Patriot Metric adalah platform digital interaktif yang diinisiasi oleh Universitas Pembangunan Nasional "Veteran" Jawa Timur untuk mengukur, memvalidasi, dan memeringkat implementasi nilai-nilai bela negara di berbagai institusi akademis.
                        </p>
                        <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[29.25px] text-[#45556c]">
                            Dengan instrumen yang terstandar, kami memberikan gambaran objektif mengenai seberapa baik sebuah institusi mengintegrasikan patriotisme ke dalam kurikulum, kegiatan kemahasiswaan, dan budaya kampusnya.
                        </p>
                        <div class="mt-6 flex flex-col gap-3 pt-4">
                            @foreach([
                                "Sistem penilaian transparan & objektif",
                                "Dashboard institusional terintegrasi",
                                "Sertifikat & predikat penghargaan nasional",
                            ] as $item)
                            <div class="flex items-center gap-3">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="#1B5E20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                </svg>
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[18px] text-[#314158]">{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="relative">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-[#f1f5f9] aspect-video">
                            <iframe
                                class="w-full h-full absolute inset-0"
                                src="https://www.youtube.com/embed/nB4YzOhnkBo?si=KXFTn2dRpO-TDdKc"
                                title="YouTube video player"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Why Join Section --}}
        <section class="bg-white py-24">
            <div class="max-w-[1536px] mx-auto px-8">
                <div class="text-center max-w-[672px] mx-auto mb-16">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] leading-[40px] text-[#1d293d]">Mengapa Bergabung dengan Kami?</h2>
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[#45556c]">
                        Platform kami memberikan nilai tambah yang nyata bagi institusi yang berkomitmen pada pendidikan karakter.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                    $features = [
                        [
                            'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M10.6667 26.6667H5.33333C4.59695 26.6667 4 26.0697 4 25.3333V18.6667C4 17.9303 4.59695 17.3333 5.33333 17.3333H10.6667M18.6667 14.6667V9.33333C18.6667 7.86057 17.4728 6.66667 16 6.66667L10.6667 17.3333V26.6667H23.2533C23.8351 26.6741 24.3366 26.2547 24.4267 25.68L25.9067 16.3467C25.9599 16.0124 25.8645 15.6717 25.6462 15.4136C25.4279 15.1555 25.1085 15.0065 24.7733 15.0067L18.6667 14.6667Z" stroke="#1B5E20" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667" /></svg>',
                            'title' => "Meningkatkan Kesadaran Bela Negara",
                            'desc' => "Mendorong perguruan tinggi untuk menumbuhkan dan memperkuat karakter bela negara di lingkungan kampus."
                        ],
                        [
                            'icon' => '<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="#1B5E20"><path d="M256 144C256 117.5 277.5 96 304 96L336 96C362.5 96 384 117.5 384 144L384 496C384 522.5 362.5 544 336 544L304 544C277.5 544 256 522.5 256 496L256 144zM64 336C64 309.5 85.5 288 112 288L144 288C170.5 288 192 309.5 192 336L192 496C192 522.5 170.5 544 144 544L112 544C85.5 544 64 522.5 64 496L64 336zM496 160L528 160C554.5 160 576 181.5 576 208L576 496C576 522.5 554.5 544 528 544L496 544C469.5 544 448 522.5 448 496L448 208C448 181.5 469.5 160 496 160z" /></svg>',
                            'title' => "Membangun Jejaring dan Kolaborasi",
                            'desc' => "Membuka peluang bagi perguruan tinggi peserta untuk menjadi bagian dari jejaring Patriot Metric yang memungkinkan terjalinnya kolaborasi."
                        ],
                        [
                            'icon' => '<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="#1B5E20"><path d="M320.1 32C329.1 32 337.4 37.1 341.5 45.1L415 189.3L574.9 214.7C583.8 216.1 591.2 222.4 594 231C596.8 239.6 594.5 249 588.2 255.4L473.7 369.9L499 529.8C500.4 538.7 496.7 547.7 489.4 553C482.1 558.3 472.4 559.1 464.4 555L320.1 481.6L175.8 555C167.8 559.1 158.1 558.3 150.8 553C143.5 547.7 139.8 538.8 141.2 529.8L166.4 369.9L52 255.4C45.6 249 43.4 239.6 46.2 231C49 222.4 56.3 216.1 65.3 214.7L225.2 189.3L298.8 45.1C302.9 37.1 311.2 32 320.2 32zM320.1 108.8L262.3 222C258.8 228.8 252.3 233.6 244.7 234.8L119.2 254.8L209 344.7C214.4 350.1 216.9 357.8 215.7 365.4L195.9 490.9L309.2 433.3C316 429.8 324.1 429.8 331 433.3L444.3 490.9L424.5 365.4C423.3 357.8 425.8 350.1 431.2 344.7L521 254.8L395.5 234.8C387.9 233.6 381.4 228.8 377.9 222L320.1 108.8z" /></svg>',
                            'title' => "Meningkatkan Pengakuan & Reputasi Nasional",
                            'desc' => "Memperkuat citra dan reputasi perguruan tinggi sebagai institusi yang berkomitmen terhadap penguatan karakter bela negara."
                        ]
                    ];
                    @endphp
                    @foreach($features as $item)
                    <div class="bg-[#f8fafc] border border-[#f1f5f9] rounded-2xl p-8 shadow-sm">
                        <div class="bg-white rounded-2xl shadow-sm size-16 flex items-center justify-center mb-6">
                            {!! $item['icon'] !!}
                        </div>
                        <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] leading-[28px] text-[#1d293d]">{{ $item['title'] }}</h3>
                        <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[26px] text-[#45556c]">{{ $item['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Timeline Section --}}
        <section class="bg-[#f8fafc] py-24">
            <div class="max-w-[1536px] mx-auto px-8">
                <div class="text-center max-w-[672px] mx-auto mb-16">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] leading-[40px] text-[#1d293d]">Timeline Patriot Metric</h2>
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[#45556c]">
                        Jadwal dan tahapan proses pemeringkatan institusi Anda.
                    </p>
                </div>
                <div class="relative max-w-[900px] mx-auto">
                    {{-- Timeline line --}}
                    <div class="absolute left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-[rgba(27,94,32,0.1)] via-[#1b5e20] to-[rgba(27,94,32,0.1)] rounded-full -translate-x-1/2"></div>
                    
                    @php
                    $timeline = [
                        ['num' => "01", 'date' => "1 - 31 Agustus", 'title' => "Pembukaan Registrasi", 'desc' => "Periode pendaftaran institusi melalui portal Patriot Metric.", 'right' => true],
                        ['num' => "02", 'date' => "1 - 15 September", 'title' => "Validasi Akun", 'desc' => "Verifikasi data institusi dan PIC yang telah didaftarkan.", 'right' => false],
                        ['num' => "03", 'date' => "16 Sep - 31 Okt", 'title' => "Mulai Pengisian Rubrik", 'desc' => "Periode pengisian rubrik penilaian dan unggah bukti pendukung.", 'right' => true],
                        ['num' => "04", 'date' => "1 - 30 November", 'title' => "Validasi Penilaian Rubrik", 'desc' => "Tim penilai melakukan verifikasi data melalui Patriot Metric.", 'right' => false],
                        ['num' => "05", 'date' => "1 - 15 Desember", 'title' => "Pengolahan", 'desc' => "Pengolahan data dan kalkulasi skor pemeringkatan nasional.", 'right' => true],
                        ['num' => "06", 'date' => "17 Agustus", 'title' => "Penghargaan", 'desc' => "Pengumuman hasil dan upacara penghargaan nasional.", 'right' => false],
                    ];
                    @endphp
                    @foreach($timeline as $item)
                    <div class="relative flex items-center mb-12 last:mb-0">
                        {{-- Circle --}}
                        <div class="absolute left-1/2 -translate-x-1/2 bg-white border-4 border-[#f8fafc] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] rounded-full size-[74px] flex items-center justify-center z-10">
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] text-[#1b5e20]">{{ $item['num'] }}</span>
                        </div>
                        {{-- Content --}}
                        <div class="w-[calc(50%-60px)] {{ $item['right'] ? 'ml-auto pl-8 text-left' : 'mr-auto pr-8 text-right' }}">
                            <div class="inline-block bg-[rgba(212,175,55,0.1)] rounded-full px-3 py-1 mb-2">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[14px] text-[#d4af37] uppercase tracking-wider">{{ $item['date'] }}</span>
                            </div>
                            <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] leading-[28px] text-[#1d293d]">{{ $item['title'] }}</h3>
                            <p class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[26px] text-[#45556c]">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA Section --}}
        <section class="bg-gradient-to-r from-[#1b5e20] to-[#2e7d32] py-24">
            <div class="max-w-[768px] mx-auto px-8 text-center">
                <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] leading-[48px] text-white">
                    Wujudkan Kampus Patriot: Mari Bergabung Sekarang!
                </h2>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[rgba(255,255,255,0.8)]">
                    Satu langkah kecil untuk membuat institusi Anda yang lebih baik. Daftarkan kampus Anda dan jadilah bagian dari perubahan karakter generasi penerus bangsa.
                </p>
                <a href="{{ url('/daftar') }}" class="inline-flex items-center gap-2 mt-8 bg-[#d4af37] text-[#1d293d] font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] px-10 py-5 rounded-2xl shadow-lg hover:brightness-110 transition">
                    Daftarkan Institusi Anda
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.16667 10H15.8333" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" /><path d="M10 4.16667L15.8333 10L10 15.8333" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" /></svg>
                </a>
            </div>
        </section>
    </div>
</x-layouts.app>
