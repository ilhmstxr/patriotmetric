<x-layouts.app>
    <div class="bg-white">
        {{-- Hero --}}
        <section class="relative bg-[#0f172b] overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/bg.webp') }}" alt="" class="w-full h-full object-cover opacity-20" />
                <div class="absolute inset-0 bg-gradient-to-b from-[rgba(27,94,32,0.85)] to-[#0f172b]/90"></div>
            </div>
            <div class="absolute top-16 right-16 w-80 h-80 bg-[#d4af37]/5 rounded-full blur-3xl"></div>
            <div class="relative max-w-[1200px] mx-auto px-6 md:px-8 py-20 md:py-32">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] sm:text-[48px] md:text-[56px] leading-[1.15] text-white max-w-[700px]">
                    Membangun Karakter
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d4af37] to-[#fff085]">Bangsa</span>
                    dari Kampus
                </h1>
                <p class="mt-5 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[17px] md:text-[19px] leading-[30px] text-white/75 max-w-[580px]">
                    Sebuah inisiatif pemeringkatan nasional yang didedikasikan untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi.
                </p>
            </div>
        </section>

        {{-- Latar Belakang --}}
        <section class="py-16 md:py-24 bg-white">
            <div class="max-w-[860px] mx-auto px-6 md:px-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-1 h-8 bg-[#1B5E20] rounded-full"></div>
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[26px] md:text-[32px] text-[#1d293d]">Latar Belakang</h2>
                </div>
                <div class="space-y-5 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[17px] leading-[28px] md:leading-[30px] text-[#45556c]">
                    <p>
                        Di tengah arus globalisasi, nilai patriotisme menghadapi tantangan serius, mulai dari menurunnya pemahaman terhadap sejarah, derasnya arus disinformasi serta radikalisme digital yang memicu polarisasi, hingga meningkatnya individualisme yang melemahkan kepedulian sosial. Oleh karena itu, diperlukan instrumen yang terukur dan kredibel untuk menilai sejauh mana perguruan tinggi mampu menginternalisasikan nilai-nilai bela negara di seluruh elemennya.
                    </p>
                    <p>
                        Universitas Pembangunan Nasional "Veteran" Jawa Timur memprakarsai Patriot Metric UPN Veteran Jatim sebagai jawaban atas kebutuhan tersebut, yaitu sebuah sistem pemeringkatan perguruan tinggi berbasis indikator bela negara. Konsep Patriot Metric muncul dari kebutuhan untuk menghadirkan instrumen evaluasi yang objektif dan terstandar agar pembinaan kesadaran bela negara, khususnya dalam konteks nasionalisme dan patriotisme, dapat dianalisis, dievaluasi, serta dikembangkan secara berkelanjutan.
                    </p>
                </div>
            </div>
        </section>

        {{-- Tujuan Utama --}}
        <section class="py-16 md:py-20 bg-[#f8fafc]">
            <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                <div class="text-center mb-12">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[26px] md:text-[32px] text-[#1d293d]">Tujuan Utama Program</h2>
                    <p class="mt-3 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] text-[#64748b] max-w-[500px] mx-auto">Empat pilar yang menjadi landasan pengembangan Patriot Metric.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @php
                        $tujuan = [
                            ['num' => "01", 'title' => "Instrumen Evaluasi", 'text' => "Menilai internalisasi karakter bela negara secara terukur dan objektif di lingkungan perguruan tinggi."],
                            ['num' => "02", 'title' => "Penguatan Ekosistem", 'text' => "Memperkuat ekosistem pendidikan berbasis nilai kebangsaan melalui implementasi Tri Dharma."],
                            ['num' => "03", 'title' => "Sinergi Antarperguruan Tinggi", 'text' => "Mendorong kolaborasi dan sinergi antarperguruan tinggi dalam pembinaan bela negara."],
                            ['num' => "04", 'title' => "Perbaikan Berkelanjutan", 'text' => "Mendorong setiap perguruan tinggi untuk terus melakukan perbaikan dan inovasi berkelanjutan."],
                        ];
                    @endphp
                    @foreach($tujuan as $item)
                        <div class="bg-white rounded-2xl border border-[#f1f5f9] p-7 hover:shadow-lg hover:border-[#1B5E20]/10 transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] text-[#d4af37]/40 leading-none">{{ $item['num'] }}</span>
                                <div>
                                    <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[17px] text-[#1d293d]">{{ $item['title'] }}</h3>
                                    <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[24px] text-[#45556c]">{{ $item['text'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
