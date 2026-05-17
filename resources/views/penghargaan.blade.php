<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/b4f942a6770a3928dc2f82d398369a3d39ba1fde.webp') }}" alt="" class="w-full h-full object-cover" />
            </div>
            <div class="relative max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">Galeri Penghargaan</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    Penghormatan tertinggi bagi institusi yang telah membuktikan dedikasinya dalam membangun karakter patriotik dan bela negara.
                </p>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-12 md:py-16 relative z-10">
            <div class="max-w-[1200px] mx-auto px-6 md:px-8">
                {{-- Title --}}
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-1 h-8 bg-[#1B5E20] rounded-full"></div>
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[22px] md:text-[26px] text-[#1d293d]">Daftar Institusi Peraih Penghargaan</h2>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $winners = [
                            ['name' => "Lorem Ipsum", 'rating' => 5],
                            ['name' => "Lorem Ipsum", 'rating' => 5],
                            ['name' => "Lorem Ipsum", 'rating' => 4.5],
                            ['name' => "Lorem Ipsum", 'rating' => 4],
                            ['name' => "Lorem Ipsum", 'rating' => 5],
                            ['name' => "Lorem Ipsum", 'rating' => 4],
                        ];
                    @endphp
                    @foreach($winners as $winner)
                        <div class="bg-white rounded-2xl border border-[#f1f5f9] p-8 flex flex-col items-center hover:shadow-lg hover:border-[#d4af37]/20 transition-all duration-300 group">
                            <div class="bg-[#f8fafc] rounded-2xl border border-[#f1f5f9] size-20 flex items-center justify-center mb-5 group-hover:border-[#d4af37]/20 transition-colors">
                                <img src="{{ asset('assets/images/199dc2ebf1e9cecf5218f4b20951209708831231.webp') }}" alt="" class="w-14 h-14 object-contain" />
                            </div>
                            <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] text-[#1d293d] text-center mb-4">{{ $winner['name'] }}</h3>
                            <div class="w-12 h-[2px] bg-[#e2e8f0] mb-4"></div>
                            {{-- Stars --}}
                            <div class="flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        $fillColor = $i <= floor($winner['rating']) ? '#D0B55E' : ($i - 0.5 <= $winner['rating'] ? '#D0B55E' : '#E2E8F0');
                                    @endphp
                                    <i data-lucide="star" class="w-5 h-5" style="fill: {{ $fillColor }}; color: {{ $fillColor }};"></i>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
