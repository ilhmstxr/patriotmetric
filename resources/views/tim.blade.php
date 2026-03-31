<x-layouts.app>
    <div class="bg-[#f8fafc]">
        {{-- Header --}}
        <section class="py-20 bg-[#f8fafc]">
            <div class="max-w-[768px] mx-auto px-8 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[48px] leading-[48px] text-[#1d293d]">Di Balik Layar Patriot Metric</h1>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[20px] leading-[32.5px] text-[#45556c]">
                    Para pakar dan praktisi pendidikan yang berdedikasi untuk menjaga standar penilaian bela negara secara independen, profesional, dan objektif.
                </p>
            </div>
        </section>

        {{-- Team Grid --}}
        <section class="pb-20">
            <div class="max-w-[1280px] mx-auto px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                    $teamMembers = [
                        [
                            'name' => "Dr. Anthoni Syahputra, M.Si",
                            'role' => "Direktur Eksekutif Patriot Metric",
                            'img' => asset('assets/images/b46f9b1290aa05875cfd913832567d502ffcdf7b.png')
                        ],
                        [
                            'name' => "Prof. Rina Mulyani, Ph.D",
                            'role' => "Kepala Bidang Penilaian",
                            'img' => asset('assets/images/8065f006493aaaa55c477f042940d24c97af0cce.png')
                        ],
                        [
                            'name' => "Dr. Budi Santoso",
                            'role' => "Ketua Komite Validasi",
                            'img' => asset('assets/images/e313a1a2d57d113b5249eb71085596beeb74aa76.png')
                        ],
                        [
                            'name' => "Siti Rahmawati, M.Pd",
                            'role' => "Manajer Program & Hubungan Kelembagaan",
                            'img' => asset('assets/images/c35a4e22b8dfb8518f0477da3e0c2a8c6552cb64.png')
                        ]
                    ];
                    @endphp
                    @foreach($teamMembers as $member)
                    <div class="bg-white rounded-3xl border border-[#f1f5f9] shadow-[0px_20px_25px_0px_rgba(226,232,240,0.5)] overflow-hidden">
                        <div class="p-3.5 pb-0">
                            <div class="bg-[#f1f5f9] rounded-2xl overflow-hidden h-[337px]">
                                <img src="{{ $member['img'] }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover" />
                            </div>
                        </div>
                        <div class="p-4 pt-5 text-center">
                            <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] leading-[28px] text-[#1d293d]">{{ $member['name'] }}</h3>
                            <p class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] leading-[19.25px] text-[#1b5e20]">{{ $member['role'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
