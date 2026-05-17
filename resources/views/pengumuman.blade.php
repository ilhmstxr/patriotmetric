<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">Pengumuman</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    Informasi terbaru seputar Patriot Metric.
                </p>
            </div>
        </section>

        {{-- Article List --}}
        <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">

            @php
                $articles = [
                    [
                        'date' => '1 Agustus 2025',
                        'title' => 'Cara Mendaftarkan Institusi Anda di Portal Patriot Metric',
                        'excerpt' => 'Panduan lengkap langkah-langkah pendaftaran institusi di portal Patriot Metric. Mulai dari pembuatan akun, pengisian data institusi, hingga penunjukan PIC yang bertanggung jawab.',
                        'img' => 'article-registrasi.webp',
                    ],
                    [
                        'date' => '1 September 2025',
                        'title' => 'Proses Validasi & Verifikasi Data Institusi',
                        'excerpt' => 'Ketahui dokumen apa saja yang diperlukan untuk validasi akun institusi dan bagaimana proses verifikasi dilakukan oleh tim kami untuk memastikan keabsahan data.',
                        'img' => 'article-validasi.webp',
                    ],
                    [
                        'date' => '16 September 2025',
                        'title' => 'Tips Pengisian Rubrik Penilaian yang Efektif',
                        'excerpt' => 'Strategi dan tips agar pengisian rubrik penilaian berjalan optimal. Termasuk jenis bukti pendukung yang direkomendasikan dan format yang diterima sistem.',
                        'img' => 'article-rubrik.webp',
                    ],
                    [
                        'date' => '1 November 2025',
                        'title' => 'Mekanisme Penilaian oleh Tim Reviewer',
                        'excerpt' => 'Bagaimana tim penilai memverifikasi dan mengevaluasi data institusi. Transparansi proses penilaian dan kriteria yang digunakan dalam evaluasi.',
                        'img' => 'article-penilaian.webp',
                    ],
                    [
                        'date' => '1 Desember 2025',
                        'title' => 'Metodologi Kalkulasi Skor Pemeringkatan',
                        'excerpt' => 'Penjelasan sistem scoring dan bobot penilaian Patriot Metric. Bagaimana skor akhir dihitung dari berbagai dimensi penilaian bela negara.',
                        'img' => 'article-pengolahan.webp',
                    ],
                    [
                        'date' => '17 Agustus 2026',
                        'title' => 'Upacara Penghargaan Nasional Patriot Metric',
                        'excerpt' => 'Informasi seputar acara pengumuman dan penyerahan penghargaan bagi institusi terbaik dalam implementasi nilai-nilai bela negara di lingkungan kampus.',
                        'img' => 'article-penghargaan.webp',
                    ],
                ];
            @endphp

            <div class="divide-y divide-[#e2e8f0]">
                @foreach($articles as $index => $article)
                    <a href="#" class="flex flex-col md:flex-row gap-5 md:gap-8 py-8 first:pt-0 last:pb-0 group">
                        {{-- Thumbnail --}}
                        <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                            <img src="{{ asset('assets/images/' . $article['img']) }}" alt="" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-[#f1f5f9]\' ><span class=\'text-[#94a3b8] text-[13px]\'>Gambar</span></div>'">
                        </div>
                        {{-- Text --}}
                        <div class="flex-1 min-w-0">
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-[#94a3b8]">{{ $article['date'] }}</span>
                            <h2 class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d] group-hover:text-[#1B5E20] transition-colors">{{ $article['title'] }}</h2>
                            <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[25px] text-[#64748b] line-clamp-2">{{ $article['excerpt'] }}</p>
                            <span class="inline-block mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1B5E20] group-hover:underline">Lihat selengkapnya →</span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10 flex items-center justify-center gap-2">
                <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#1B5E20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px]">1</span>
                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg border border-[#e2e8f0] text-[#64748b] font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] hover:border-[#1B5E20] hover:text-[#1B5E20] transition-colors">2</a>
                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg border border-[#e2e8f0] text-[#64748b] font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] hover:border-[#1B5E20] hover:text-[#1B5E20] transition-colors">3</a>
                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg border border-[#e2e8f0] text-[#64748b] font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] hover:border-[#1B5E20] hover:text-[#1B5E20] transition-colors">→</a>
            </div>

        </div>
    </div>
</x-layouts.app>
