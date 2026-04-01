<x-layouts.app>
    <div class="bg-[#f8fafc]">
        {{-- Header --}}
        <section class="pt-20 pb-0 bg-[#f8fafc]">
            <div class="max-w-[768px] mx-auto px-8 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[48px] leading-[48px] text-[#1d293d]">Panduan Penggunaan Sistem</h1>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[20px] leading-[28px] text-[#45556c]">
                    Langkah mudah dan terstruktur untuk mendaftarkan dan menilai institusi Anda di Patriot Metric.
                </p>
                <div class="mt-8">
                    <a href="https://bit.ly/PEDOMANPATRIOTMETRIC" target="_blank" class="inline-flex items-center gap-2 bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] px-8 py-4 rounded-2xl shadow-lg hover:bg-[#174d1a] transition">
                        Pedoman Patriot Metric UPN Veteran Jatim &rarr;
                    </a>
                </div>
            </div>
        </section>

        {{-- Steps --}}
        <section class="py-16">
            <div class="max-w-[1024px] mx-auto px-8">
                <div class="relative">
                    {{-- Line --}}
                    <div class="absolute top-[168px] left-10 right-10 h-1 bg-[#1b5e20]"></div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        @php
                        $steps = [
                            [
                                'step' => "Langkah 1",
                                'title' => "Input Data",
                                'desc' => "Peserta mengisi formulir pemeringkatan secara daring dan mengunggah dokumen pendukung.",
                                'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                            <path d="M21.3333 28V25.3333C21.3333 23.9188 20.7714 22.5623 19.7712 21.5621C18.771 20.5619 17.4145 20 16 20H6.66666C5.25217 20 3.89562 20.5619 2.89543 21.5621C1.89523 22.5623 1.33333 23.9188 1.33333 25.3333V28" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M11.3333 14.6667C14.2789 14.6667 16.6667 12.2789 16.6667 9.33333C16.6667 6.38781 14.2789 4 11.3333 4C8.38781 4 6 6.38781 6 9.33333C6 12.2789 8.38781 14.6667 11.3333 14.6667Z" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M25.3333 10.6667V18.6667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M29.3333 14.6667H21.3333" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                        </svg>'
                            ],
                            [
                                'step' => "Langkah 2",
                                'title' => "Validasi",
                                'desc' => "Proses validasi oleh Tim Evaluator untuk memastikan keabsahan data, termasuk wawancara & visitasi lapangan.",
                                'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                            <path d="M18.6667 2.66667H8C7.29276 2.66667 6.61448 2.94762 6.11438 3.44772C5.61428 3.94781 5.33333 4.62609 5.33333 5.33333V26.6667C5.33333 27.3739 5.61428 28.0522 6.11438 28.5523C6.61448 29.0524 7.29276 29.3333 8 29.3333H24C24.7073 29.3333 25.3855 29.0524 25.8856 28.5523C26.3857 28.0522 26.6667 27.3739 26.6667 26.6667V10.6667L18.6667 2.66667Z" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M18.6667 2.66667V10.6667H26.6667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M13.3333 12H10.6667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M21.3333 17.3333H10.6667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M21.3333 22.6667H10.6667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                        </svg>'
                            ],
                            [
                                'step' => "Langkah 3",
                                'title' => "Penilaian",
                                'desc' => "Penilaian untuk setiap indikator berbentuk skor angka dan diolah secara statistik.",
                                'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                            <path d="M16 17.3333V28" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M21.3333 22.6667L16 17.3333L10.6667 22.6667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M27.28 24.28C28.5567 23.5757 29.5575 22.4669 30.1279 21.1279C30.6982 19.7889 30.8051 18.2979 30.4319 16.894C30.0587 15.4901 29.2265 14.2553 28.0663 13.3844C26.906 12.5136 25.4845 12.0555 24.0267 12.0801H22.3467C21.9234 10.4654 21.1213 8.97316 20.0053 7.72645C18.8893 6.47973 17.493 5.51299 15.9322 4.90565C14.3715 4.2983 12.692 4.06667 11.0229 4.2288C9.35389 4.39093 7.74447 4.94215 6.31774 5.84065C4.89101 6.73915 3.68668 7.96005 2.80669 9.40132C1.92671 10.8426 1.39629 12.4619 1.25568 14.1335C1.11507 15.8051 1.36818 17.4819 1.99529 19.0336C2.6224 20.5854 3.60647 21.9684 4.86664 23.0667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                        </svg>'
                            ],
                            [
                                'step' => "Langkah 4",
                                'title' => "Pengumuman dan Klasifikasi",
                                'desc' => "Hasil akhir ditetapkan berdasarkan skor kumulatif dan disampaikan dalam bentuk peringkat bintang.",
                                'icon' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                            <path d="M29.3333 14.7733V16C29.3316 18.8753 28.3994 21.6726 26.6734 23.9758C24.9474 26.279 22.5178 27.9656 19.7596 28.7876C16.9938 29.6097 14.0449 29.524 11.3309 28.5424C8.61701 27.5609 6.28752 25.7341 4.69774 23.3329C3.10797 20.9317 2.34077 18.0841 2.50769 15.2141C2.67461 12.3441 3.76724 9.60366 5.62756 7.39754C7.48788 5.19143 9.71597 3.54191 12.3094 2.6531C14.9028 1.76428 17.7265 1.67802 20.3733 2.40667" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                            <path d="M29.3333 5.33333L16 18.68L12 14.68" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.66667"/>
                                        </svg>'
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
        <section class="pb-24" x-data="{ openFaq: 0 }">
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
                        [
                            'q' => "Apa kriteria untuk mendapatkan predikat Adhi Karya?",
                            'a' => "Predikat Adhi Karya diberikan kepada institusi yang mencapai skor sempurna di seluruh rubrik penilaian bela negara.",
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
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#1B5E20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                </svg>
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
