<x-layouts.app>
    <div class="bg-white">
        {{-- Visi Section --}}
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-[896px] mx-auto px-6 md:px-8 text-center">
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] md:text-[20px] text-[#d4af37] tracking-[2px] uppercase">Visi Kami</p>
                <h1 class="mt-4 md:mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[48px] leading-[1.4] md:leading-[1.25] text-[#1d293d]">
                    "Menjadi platform pemeringkatan dan barometer paling prestisius di Indonesia dalam mengukur, mengembangkan, dan mengapresiasi implementasi nilai-nilai bela negara di perguruan tinggi."
                </h1>
            </div>
        </section>

        {{-- Misi Section --}}
        <section class="py-16 md:py-20 bg-[#f8fafc]">
            <div class="max-w-[1152px] mx-auto px-6 md:px-8">
                <div class="flex items-center gap-4 mb-10 md:mb-12">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[30px] leading-[36px] text-[#1d293d] whitespace-nowrap">Misi Strategis</h2>
                    <div class="flex-1 h-px bg-gradient-to-r from-[#e2e8f0] to-transparent"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @php
                    $misi = [
                        [
                            'num' => "01",
                            'title' => "Internalisasi",
                            'desc' => "Proses penanaman dan integrasi nilai-nilai bela negara ke dalam budaya, kebijakan, kurikulum, serta program pengembangan mahasiswa.",
                        ],
                        [
                            'num' => "02",
                            'title' => "Implementasi",
                            'desc' => "Perwujudan nyata dari proses internalisasi dalam bentuk tindakan, aktivitas, dan keterlibatan aktif sivitas akademika dalam kegiatan bertema kebangsaan dan bela negara.",
                        ],
                        [
                            'num' => "03",
                            'title' => "Pengembangan",
                            'desc' => "Upaya inovatif untuk memperkaya dan memperluas penerapan nilai-nilai bela negara, baik melalui penelitian, pengabdian kepada masyarakat, maupun kemitraan strategis.",
                        ]
                    ];
                    @endphp
                    @foreach($misi as $item)
                    <div class="bg-[#f8fafc] border border-transparent rounded-3xl p-8 relative overflow-hidden">
                        <span class="absolute top-8 right-8 font-['Plus_Jakarta_Sans',sans-serif] font-extrabold italic text-[60px] leading-[60px] text-[#e2e8f0]">{{ $item['num'] }}</span>
                        <div class="relative">
                            <div class="bg-white rounded-full shadow-sm size-12 flex items-center justify-center mb-6">
                                <i data-lucide="chevron-right" class="w-6 h-6 text-[#1B5E20]"></i>
                            </div>
                            <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] leading-[32px] text-[#1d293d]">{{ $item['title'] }}</h3>
                            <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[29.25px] text-[#45556c]">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
