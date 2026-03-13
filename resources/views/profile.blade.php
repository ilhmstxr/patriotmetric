<x-layouts.app>
    <div class="bg-white">
        {{-- Hero --}}
        <section class="relative bg-[#0f172b] overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/bg.jpeg') }}" alt="" class="w-full h-full object-cover opacity-30" />
                <div class="absolute inset-0 bg-gradient-to-r from-[rgba(27,94,32,0.9)] via-[rgba(27,94,32,0.3)] to-transparent"></div>
            </div>
            <div class="relative max-w-[1536px] mx-auto px-8 py-28 md:py-40">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[48px] md:text-[60px] leading-[1.2] text-white max-w-[768px]">
                    Membangun Karakter{" "}
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d4af37] to-[#fff085]">Bangsa</span>
                    {" "}dari Kampus
                </h1>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[20px] leading-[32.5px] text-[rgba(255,255,255,0.8)] max-w-[616px]">
                    Sebuah inisiatif pemeringkatan nasional yang didedikasikan untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi.
                </p>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-24 bg-white">
            <div class="max-w-[1200px] mx-auto px-8">
                <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[30px] leading-[36px] text-[#1d293d]">Latar Belakang</h2>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[29.25px] text-[#45556c] text-justify">
                    Di tengah arus globalisasi, nilai patriotisme menghadapi tantangan serius, mulai dari menurunnya pemahaman terhadap sejarah, derasnya arus disinformasi serta radikalisme digital yang memicu polarisasi, hingga meningkatnya individualisme yang melemahkan kepedulian sosial. Oleh karena itu, diperlukan instrumen yang terukur dan kredibel untuk menilai sejauh mana perguruan tinggi mampu menginternalisasikan nilai-nilai bela negara di seluruh elemennya.
                </p>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[29.25px] text-[#45556c] text-justify">
                    Universitas Pembangunan Nasional “Veteran” Jawa Timur memprakarsai Patriot Metric UPN Veteran Jatim sebagai jawaban atas kebutuhan tersebut, yaitu sebuah sistem pemeringkatan perguruan tinggi berbasis indikator bela negara. Konsep Patriot Metric muncul dari kebutuhan untuk menghadirkan instrumen evaluasi yang objektif dan terstandar agar pembinaan kesadaran bela negara, khususnya dalam konteks nasionalisme dan patriotisme, dapat dianalisis, dievaluasi, serta dikembangkan secara berkelanjutan.
                </p>

                {{-- Tujuan Utama --}}
                <div class="mt-16 bg-white border border-[#f1f5f9] rounded-3xl shadow-lg p-10 max-w-[832px] mx-auto relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-[rgba(27,94,32,0.05)] rounded-bl-full size-32"></div>
                    <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] leading-[32px] text-[#1d293d] mb-8">Tujuan Utama Program</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                        $tujuan = [
                            ['num' => "1", 'text' => "Instrumen Evaluasi Kelembagaan untuk menilai internalisasi karakter bela negara."],
                            ['num' => "2", 'text' => "Penguatan Ekosistem Pendidikan Berbasis Nilai Kebangsaan melalui Tri Dharma/."],
                            ['num' => "3", 'text' => "Peningkatan Sinergi dan kolaborasi Antarperguruan Tinggi."],
                            ['num' => "4", 'text' => "Mendorong setiap perguruan tinggi untuk perbaikan berkelanjutan."]
                        ];
                        @endphp
                        @foreach($tujuan as $item)
                        <div class="flex gap-4">
                            <div class="bg-[rgba(212,175,55,0.1)] rounded-full size-8 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] text-[#d4af37]">{{ $item['num'] }}</span>
                            </div>
                            <p class="font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[16px] leading-[26px] text-[#45556c]">{{ $item['text'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
