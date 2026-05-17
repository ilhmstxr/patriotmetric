<x-layouts.app>
    <div class="bg-white">
        {{-- Hero Section --}}
        <section class="relative bg-[#0f172b] overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/46257018a5d0ac00852b82184ae3ed30ef9a74e4.webp') }}" alt="" class="w-full h-full object-cover opacity-30" />
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
                                <div class="w-2.5 h-2.5 rounded-full bg-[#1B5E20] shrink-0"></div>
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

        {{-- Institusi yang Telah Berpartisipasi --}}
        <section class="relative bg-[#f8fafc] py-16 overflow-hidden">
            <div class="max-w-[1536px] mx-auto px-6 md:px-8 mb-10 text-center">
                <h2 class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[30px] text-[#1d293d]">Institusi yang Telah Berpartisipasi</h2>
                <p class="mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#45556c] max-w-[480px] mx-auto">
                    Bergabung bersama perguruan tinggi terbaik Indonesia dalam mewujudkan kampus berkarakter bela negara.
                </p>
            </div>

            @php
                $institusi = [
                    ['nama' => 'UPN "Veteran" Jawa Timur', 'singkatan' => 'UPNVJT'],
                    ['nama' => 'Universitas Negeri Surabaya', 'singkatan' => 'UNESA'],
                    ['nama' => 'Universitas 17 Agustus', 'singkatan' => 'UNTAG'],
                ];
                $institusi2 = [
                    ['nama' => 'UPN "Veteran" Yogyakarta', 'singkatan' => 'UPNVY'],
                    ['nama' => 'Universitas Bhayangkara Jakarta Raya', 'singkatan' => 'UBHARAJAYA'],
                    ['nama' => 'Universitas Mega Buana Palopo', 'singkatan' => 'UMB Palopo'],
                ];
            @endphp

            {{-- Baris 1: scroll ke kiri --}}
            <div class="relative marquee-wrapper mb-4">
                <div class="pointer-events-none absolute left-0 top-0 h-full w-32 bg-gradient-to-r from-[#f8fafc] to-transparent z-10"></div>
                <div class="pointer-events-none absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-[#f8fafc] to-transparent z-10"></div>
                <div class="marquee-content marquee-content--left">
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusi as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#1B5E20]/20 transition-all duration-300">
                                <div class="size-9 rounded-full bg-[#1B5E20]/10 flex items-center justify-center shrink-0">
                                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#1B5E20] text-center leading-tight">{{ $inst['singkatan'] }}</span>
                                </div>
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] }}</span>
                            </div>
                        @endforeach
                    @endfor
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusi as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#1B5E20]/20 transition-all duration-300">
                                <div class="size-9 rounded-full bg-[#1B5E20]/10 flex items-center justify-center shrink-0">
                                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#1B5E20] text-center leading-tight">{{ $inst['singkatan'] }}</span>
                                </div>
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] }}</span>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>

            {{-- Baris 2: scroll ke kanan --}}
            <div class="relative marquee-wrapper">
                <div class="pointer-events-none absolute left-0 top-0 h-full w-32 bg-gradient-to-r from-[#f8fafc] to-transparent z-10"></div>
                <div class="pointer-events-none absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-[#f8fafc] to-transparent z-10"></div>
                <div class="marquee-content marquee-content--right">
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusi2 as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#d4af37]/30 transition-all duration-300">
                                <div class="size-9 rounded-full bg-[#d4af37]/15 flex items-center justify-center shrink-0">
                                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#b8941f] text-center leading-tight">{{ $inst['singkatan'] }}</span>
                                </div>
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] }}</span>
                            </div>
                        @endforeach
                    @endfor
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusi2 as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#d4af37]/30 transition-all duration-300">
                                <div class="size-9 rounded-full bg-[#d4af37]/15 flex items-center justify-center shrink-0">
                                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#b8941f] text-center leading-tight">{{ $inst['singkatan'] }}</span>
                                </div>
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] }}</span>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </section>

        {{-- Timeline Section --}}
        <section class="relative bg-white py-24 overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute top-20 left-0 w-[300px] h-[300px] bg-[#1B5E20]/[0.02] rounded-full -translate-x-1/2"></div>
            <div class="absolute bottom-20 right-0 w-[250px] h-[250px] bg-[#d4af37]/[0.03] rounded-full translate-x-1/2"></div>
            <div class="relative max-w-[1536px] mx-auto px-6 md:px-8">
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
                            ['num' => "01", 'date' => "1 - 31 Agustus", 'title' => "Pembukaan Registrasi", 'desc' => "Periode pendaftaran institusi melalui portal Patriot Metric.", 'right' => true, 'article' => ['title' => 'Cara Mendaftarkan Institusi Anda', 'excerpt' => 'Panduan lengkap langkah-langkah pendaftaran institusi di portal Patriot Metric.', 'img' => 'article-registrasi.webp']],
                            ['num' => "02", 'date' => "1 - 15 September", 'title' => "Validasi Akun", 'desc' => "Verifikasi data institusi dan PIC yang telah didaftarkan.", 'right' => false, 'article' => ['title' => 'Proses Validasi & Verifikasi Data', 'excerpt' => 'Ketahui dokumen apa saja yang diperlukan untuk validasi akun institusi.', 'img' => 'article-validasi.webp']],
                            ['num' => "03", 'date' => "16 Sep - 31 Okt", 'title' => "Mulai Pengisian Rubrik", 'desc' => "Periode pengisian rubrik penilaian dan unggah bukti pendukung.", 'right' => true, 'article' => ['title' => 'Tips Pengisian Rubrik yang Efektif', 'excerpt' => 'Strategi dan tips agar pengisian rubrik penilaian berjalan optimal.', 'img' => 'article-rubrik.webp']],
                            ['num' => "04", 'date' => "1 - 30 November", 'title' => "Validasi Penilaian Rubrik", 'desc' => "Tim penilai melakukan verifikasi data melalui Patriot Metric.", 'right' => false, 'article' => ['title' => 'Mekanisme Penilaian oleh Tim Reviewer', 'excerpt' => 'Bagaimana tim penilai memverifikasi dan mengevaluasi data institusi.', 'img' => 'article-penilaian.webp']],
                            ['num' => "05", 'date' => "1 - 15 Desember", 'title' => "Pengolahan", 'desc' => "Pengolahan data dan kalkulasi skor pemeringkatan nasional.", 'right' => true, 'article' => ['title' => 'Metodologi Kalkulasi Skor', 'excerpt' => 'Penjelasan sistem scoring dan bobot penilaian Patriot Metric.', 'img' => 'article-pengolahan.webp']],
                            ['num' => "06", 'date' => "17 Agustus", 'title' => "Penghargaan", 'desc' => "Pengumuman hasil dan upacara penghargaan nasional.", 'right' => false, 'article' => ['title' => 'Upacara Penghargaan Nasional', 'excerpt' => 'Informasi seputar acara pengumuman dan penyerahan penghargaan.', 'img' => 'article-penghargaan.webp']],
                        ];
                    @endphp

                    @foreach($timeline as $item)
                        <div class="relative flex md:items-start mb-12 last:mb-0">
                            {{-- Circle --}}
                            <div class="absolute left-[24px] md:left-1/2 -translate-x-1/2 bg-white border-4 border-[#f8fafc] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] rounded-full size-[48px] md:size-[74px] flex items-center justify-center z-10">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] md:text-[24px] text-[#1b5e20]">{{ $item['num'] }}</span>
                            </div>
                            {{-- Content --}}
                            <div class="w-full pl-[64px] md:pl-0 md:w-[calc(50%-60px)] {{ $item['right'] ? 'md:ml-auto md:pl-8 text-left' : 'md:mr-auto md:pr-8 text-left md:text-right' }}">
                                <div class="bg-white rounded-xl p-5 shadow-sm border border-[#f1f5f9] hover:shadow-md hover:border-[#1B5E20]/10 transition-all duration-300">
                                    <div class="inline-block bg-[rgba(212,175,55,0.1)] rounded-full px-3 py-1 mb-2">
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[13px] text-[#d4af37] uppercase tracking-wider">{{ $item['date'] }}</span>
                                    </div>
                                    <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d]">{{ $item['title'] }}</h3>
                                    <p class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[15px] leading-[24px] text-[#45556c]">{{ $item['desc'] }}</p>
                                    {{-- Article preview with image --}}
                                    <a href="{{ url('/pengumuman') }}" class="mt-4 block rounded-lg overflow-hidden border border-[#e2e8f0] hover:border-[#1B5E20]/20 hover:shadow-md transition-all duration-300 group text-left">
                                        <div class="relative h-[120px] bg-gradient-to-br from-[#1B5E20]/10 to-[#d4af37]/10 overflow-hidden">
                                            <img src="{{ asset('assets/images/' . $item['article']['img']) }}" alt="{{ $item['article']['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.style.display='none'">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                        </div>
                                        <div class="p-3 bg-white">
                                            <h4 class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[13px] text-[#1d293d] group-hover:text-[#1B5E20] transition-colors leading-tight">{{ $item['article']['title'] }}</h4>
                                            <p class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[12px] leading-[18px] text-[#64748b] line-clamp-2">{{ $item['article']['excerpt'] }}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Instagram Feed Section --}}
        <section class="relative bg-[#f8fafc] py-24 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-[#1B5E20]/[0.02] rounded-full -translate-y-1/2"></div>
            <div class="relative max-w-[1536px] mx-auto px-6 md:px-8">
                <div class="text-center max-w-[672px] mx-auto mb-12">
                    <div class="inline-flex items-center gap-2 bg-[#1B5E20] text-white text-[13px] font-semibold px-4 py-1.5 rounded-full mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Instagram
                    </div>
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[36px] leading-[40px] text-[#1d293d]">Ikuti Aktivitas Kami</h2>
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[#45556c]">
                        Pantau perkembangan terbaru Patriot Metric melalui Instagram kami.
                    </p>
                </div>

                @php
                    $posts = [
                        [
                            'url' => 'https://www.instagram.com/reel/DQ5YayFEtfN/',
                            'img' => asset('assets/images/ig-post-1.webp'),
                            'alt' => 'Post Instagram Patriot Metric 1',
                        ],
                        [
                            'url' => 'https://www.instagram.com/p/DQssRuxksft/',
                            'img' => asset('assets/images/ig-post-2.webp'),
                            'alt' => 'Post Instagram Patriot Metric 2',
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-2 gap-4 mb-10 max-w-[672px] mx-auto">
                    @foreach($posts as $post)
                        <a href="{{ $post['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="group relative block w-full overflow-hidden rounded-2xl border border-[#f1f5f9] shadow-sm hover:shadow-xl hover:border-[#d4af37] hover:-translate-y-1 transition-all duration-300"
                           style="height: 320px;">
                            <img
                                src="{{ $post['img'] }}"
                                alt="{{ $post['alt'] }}"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                            />
                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-[#1B5E20]/75 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <svg class="w-8 h-8 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px]">Lihat di Instagram</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="https://www.instagram.com/patriotmetric.upnjatim/" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-[#1B5E20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] px-8 py-4 rounded-2xl shadow-lg hover:bg-[#145214] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Kunjungi @patriotmetric.upnjatim
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
