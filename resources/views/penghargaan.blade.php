<x-layouts.app>
    <div class="bg-[#f8fafc]">
        {{-- Hero --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/b4f942a6770a3928dc2f82d398369a3d39ba1fde.png') }}" alt="" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[#1B5E20] opacity-80"></div>
                <div class="absolute bottom-0 w-full h-[100px] bg-gradient-to-t from-[#f8fafc] to-transparent"></div>
            </div>
            <div class="relative max-w-[800px] mx-auto px-8 py-24 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[48px] md:text-[56px] text-white leading-tight">Galeri Penghargaan</h1>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[18px] md:text-[20px] leading-[1.6] text-[rgba(255,255,255,0.9)] max-w-2xl mx-auto">
                    Penghormatan tertinggi bagi institusi yang telah membuktikan dedikasinya dalam membangun karakter patriotik dan bela negara.
                </p>
            </div>
        </section>

        {{-- Penghargaan List --}}
        <section class="py-16 relative z-10">
            <div class="max-w-[1472px] mx-auto px-8">
                
                {{-- Title Bar --}}
                <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm p-5 mb-10 flex flex-col md:flex-row items-center justify-between gap-4">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] text-[#1e293b] flex items-center gap-3">
                        Daftar Institusi Peraih Penghargaan
                    </h2>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                    <div class="bg-white rounded-[24px] p-8 flex flex-col items-center border border-[#e2e8f0] hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-[#f8fafc] rounded-2xl border border-[#f1f5f9] size-24 flex items-center justify-center mb-6">
                            <img src="{{ asset('assets/images/199dc2ebf1e9cecf5218f4b20951209708831231.png') }}" alt="" class="w-16 h-16 object-contain" />
                        </div>
                        
                        <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] leading-[28px] text-[#1e293b] text-center mb-4 min-h-[56px]">{{ $winner['name'] }}</h3>
                        
                        <div class="w-16 h-[2px] bg-[#e2e8f0] mb-5"></div>
                        
                        {{-- Star Rating Component --}}
                        <div class="flex gap-1 justify-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $fillColor = $i <= floor($winner['rating']) ? '#D0B55E' : ($i - 0.5 <= $winner['rating'] ? '#D0B55E' : '#E2E8F0');
                                @endphp
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path
                                        d="M10 1.66667L12.575 6.88333L18.3333 7.72499L14.1667 11.7833L15.15 17.5167L10 14.8083L4.85 17.5167L5.83333 11.7833L1.66667 7.72499L7.425 6.88333L10 1.66667Z"
                                        fill="{{ $fillColor }}"
                                        stroke="{{ $fillColor }}"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1"
                                    />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
