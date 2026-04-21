<x-layouts.app>
    <div class="bg-white">
        {{-- Hero Section --}}
        <section class="relative bg-[#0f172b] overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/46257018a5d0ac00852b82184ae3ed30ef9a74e4.png') }}" alt="" class="w-full h-full object-cover opacity-30" />
                <div class="absolute inset-0 bg-gradient-to-r from-[rgba(27,94,32,0.9)] via-[rgba(27,94,32,0.2)] to-transparent"></div>
            </div>
            <div class="relative max-w-[1536px] mx-auto px-6 md:px-8 py-24 md:py-44">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] sm:text-[48px] md:text-[60px] leading-[1.2] text-white max-w-[768px]">
                    Membangun Karakter
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d4af37] to-[#fff085]">Bangsa</span>
                    dari Kampus
                </h1>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] md:text-[20px] leading-[32.5px] text-[rgba(255,255,255,0.8)] max-w-[616px]">
                    Sebuah inisiatif pemeringkatan nasional yang didedikasikan untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ url('/daftar') }}" class="w-full sm:w-auto bg-[#d4af37] text-[#1d293d] font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] px-8 py-4 rounded-2xl shadow-lg hover:brightness-110 transition flex items-center justify-center gap-2">
                        Daftarkan Institusi Anda
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="{{ url('/panduan') }}" class="w-full sm:w-auto border border-white/30 text-white font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[16px] px-8 py-4 rounded-2xl hover:bg-white/10 transition flex items-center justify-center">
                        Panduan Penilaian
                    </a>
                </div>
            </div>
        </section>

        {{-- About Section --}}
        <section class="bg-[#f8fafc] py-24">
            <div class="max-w-[1536px] mx-auto px-6 md:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div class="relative">
                            <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[36px] leading-[40px] text-[#1d293d]">Patriot Metric</h2>
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
                                "Sertifikat penghargaan nasional",
                            ] as $item)
                            <div class="flex items-center gap-3">
                                <i data-lucide="check" class="w-5 h-5 text-[#1B5E20]"></i>
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
            <div class="max-w-[1536px] mx-auto px-6 md:px-8">
                <div class="text-center max-w-[672px] mx-auto mb-16">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[36px] leading-[40px] text-[#1d293d]">Mengapa Bergabung dengan Kami?</h2>
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[#45556c]">
                        Platform kami memberikan nilai tambah yang nyata bagi institusi yang berkomitmen pada pendidikan karakter.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                    $features = [
                        [
                            'icon' => '<i data-lucide="thumbs-up" class="w-8 h-8 text-[#1B5E20]"></i>',
                            'title' => "Meningkatkan Kesadaran Bela Negara",
                            'desc' => "Mendorong perguruan tinggi untuk menumbuhkan dan memperkuat karakter bela negara di lingkungan kampus."
                        ],
                        [
                            'icon' => '<i data-lucide="bar-chart-2" class="w-8 h-8 text-[#1B5E20]"></i>',
                            'title' => "Membangun Jejaring dan Kolaborasi",
                            'desc' => "Membuka peluang bagi perguruan tinggi peserta untuk menjadi bagian dari jejaring Patriot Metric yang memungkinkan terjalinnya kolaborasi."
                        ],
                        [
                            'icon' => '<i data-lucide="star" class="w-8 h-8 text-[#1B5E20]"></i>',
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
            <div class="max-w-[1536px] mx-auto px-6 md:px-8">
                <div class="text-center max-w-[672px] mx-auto mb-16">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[36px] leading-[40px] text-[#1d293d]">Timeline Patriot Metric</h2>
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[#45556c]">
                        Jadwal dan tahapan proses pemeringkatan institusi Anda.
                    </p>
                </div>
                <div class="relative max-w-[900px] mx-auto">
                    {{-- Timeline line --}}
                    <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-[rgba(27,94,32,0.1)] via-[#1b5e20] to-[rgba(27,94,32,0.1)] rounded-full -translate-x-1/2"></div>
                    <div class="md:hidden absolute left-[24px] top-0 bottom-0 w-1 bg-gradient-to-b from-[rgba(27,94,32,0.1)] via-[#1b5e20] to-[rgba(27,94,32,0.1)] rounded-full -translate-x-1/2"></div>
                    
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
                    <div class="relative flex md:items-center mb-12 last:mb-0">
                        {{-- Circle --}}
                        <div class="absolute left-[24px] md:left-1/2 -translate-x-1/2 bg-white border-4 border-[#f8fafc] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] rounded-full size-[48px] md:size-[74px] flex items-center justify-center z-10">
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] md:text-[24px] text-[#1b5e20]">{{ $item['num'] }}</span>
                        </div>
                        {{-- Content --}}
                        <div class="w-full pl-[64px] md:pl-0 md:w-[calc(50%-60px)] {{ $item['right'] ? 'md:ml-auto md:pl-8 text-left' : 'md:mr-auto md:pr-8 text-left md:text-right' }}">
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
    </div>
</x-layouts.app>
