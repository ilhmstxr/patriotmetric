<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">Panduan Penggunaan Sistem</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    Langkah mudah dan terstruktur untuk mendaftarkan dan menilai institusi Anda di Patriot Metric.
                </p>
                <div class="mt-8">
                    <a href="https://bit.ly/PEDOMANPATRIOTMETRIC" target="_blank" class="inline-flex flex-col sm:flex-row text-center items-center gap-2 bg-[#d4af37] text-[#1d293d] font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] md:text-[18px] px-6 sm:px-8 py-4 rounded-2xl shadow-lg hover:brightness-110 transition leading-tight">
                        Pedoman Patriot Metric UPN Veteran Jatim &rarr;
                    </a>
                </div>
            </div>
        </section>

        {{-- Steps --}}
        <section class="py-16 bg-[#f8fafc]">
            <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                <div class="relative">
                    {{-- Line --}}
                    <div class="hidden md:block absolute top-[168px] left-10 right-10 h-1 bg-[#1b5e20]"></div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        @php
                        $steps = [
                            [
                                'step' => "Langkah 1",
                                'title' => "Input Data",
                                'desc' => "Peserta mengisi formulir pemeringkatan secara daring dan mengunggah dokumen pendukung.",
                                'icon' => '<i data-lucide="user-plus" class="w-8 h-8 text-white"></i>'
                            ],
                            [
                                'step' => "Langkah 2",
                                'title' => "Validasi",
                                'desc' => "Proses validasi oleh Tim Evaluator untuk memastikan keabsahan data, termasuk wawancara & visitasi lapangan.",
                                'icon' => '<i data-lucide="file-check" class="w-8 h-8 text-white"></i>'
                            ],
                            [
                                'step' => "Langkah 3",
                                'title' => "Penilaian",
                                'desc' => "Penilaian untuk setiap indikator berbentuk skor angka dan diolah secara statistik.",
                                'icon' => '<i data-lucide="trending-up" class="w-8 h-8 text-white"></i>'
                            ],
                            [
                                'step' => "Langkah 4",
                                'title' => "Pengumuman dan Klasifikasi",
                                'desc' => "Hasil akhir ditetapkan berdasarkan skor kumulatif dan disampaikan dalam bentuk peringkat bintang.",
                                'icon' => '<i data-lucide="check-circle" class="w-8 h-8 text-white"></i>'
                            ]
                        ];
                        @endphp
                        @foreach($steps as $step)
                        <div class="bg-white rounded-3xl border border-[#f1f5f9] shadow-lg p-8 relative z-10">
                            <div class="bg-[#1b5e20] rounded-2xl size-16 flex items-center justify-center mb-6 shadow-[0px_10px_15px_0px_rgba(27,94,32,0.2)]">
                                {!! $step['icon'] !!}
                            </div>
                            <p class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[14px] text-[#1b5e20]">{{ $step['step'] }}</p>
                            <h3 class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] leading-[28px] text-[#1d293d]">{{ $step['title'] }}</h3>
                            <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] leading-[22.75px] text-[#45556c]">{{ $step['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="py-16 md:py-24 bg-white" x-data="{ openFaq: 0 }">
            <div class="max-w-[768px] mx-auto px-8">
                <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[30px] leading-[36px] text-[#1d293d] text-center mb-10">Tanya Jawab (FAQ)</h2>
                <div class="flex flex-col gap-4">
                    @php
                    $faqs = [
                        [
                            'q' => "Siapa yang berhak mendaftarkan institusi?",
                            'a' => "Pendaftaran dapat dilakukan oleh perwakilan resmi (PIC) yang ditunjuk oleh rektorat atau pimpinan perguruan tinggi dengan melampirkan Surat Tugas resmi.",
                        ],
                        [
                            'q' => "Berapa lama proses validasi berlangsung?",
                            'a' => "Proses validasi berlangsung selama 15 hari kerja setelah pendaftaran diterima dan dokumen dinyatakan lengkap.",
                        ],
                        [
                            'q' => "Apakah sistem ini berbayar?",
                            'a' => "Tidak, sistem Patriot Metric sepenuhnya gratis dan terbuka untuk seluruh perguruan tinggi di Indonesia.",
                        ],
                    ];
                    @endphp
                    @foreach($faqs as $i => $faq)
                    <div class="bg-white rounded-2xl border border-[#f1f5f9] shadow-sm overflow-hidden">
                        <button
                            @click="openFaq = openFaq === {{ $i }} ? -1 : {{ $i }}"
                            class="w-full flex items-center justify-between px-6 py-5 focus:outline-none"
                        >
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[18px] leading-[28px] text-[#1d293d] text-left">{{ $faq['q'] }}</span>
                            <div class="bg-[rgba(27,94,32,0.1)] rounded-full size-8 flex items-center justify-center shrink-0 ml-4 transition-transform" :class="openFaq === {{ $i }} ? 'rotate-180' : ''">
                                <i data-lucide="chevron-down" class="w-5 h-5 text-[#1B5E20]"></i>
                            </div>
                        </button>
                        <div x-show="openFaq === {{ $i }}" x-collapse.duration.300ms>
                            <div class="px-6 pb-5 border-t border-[#f8fafc]">
                                <p class="pt-3 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[26px] text-[#45556c]">{{ $faq['a'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>