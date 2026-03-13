<x-layouts.app>
    <div class="bg-[#f8fafc]">
        {{-- Hero --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/b4f942a6770a3928dc2f82d398369a3d39ba1fde.png') }}" alt="" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[rgba(27,94,32,0.7)]"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#f8fafc] via-transparent to-transparent"></div>
            </div>
            <div class="relative max-w-[672px] mx-auto px-8 py-24 text-center">
                <div class="bg-[rgba(255,255,255,0.2)] rounded-full size-20 flex items-center justify-center mx-auto mb-6 border border-[rgba(255,255,255,0.5)] shadow-[0px_0px_30px_0px_rgba(255,255,255,0.3)] backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="40" height="40" fill="#F8FAFC"><path d="M341.9 38.1C328.5 29.9 311.6 29.9 298.2 38.1C273.8 53 258.7 57 230.1 56.4C214.4 56 199.8 64.5 192.2 78.3C178.5 103.4 167.4 114.5 142.3 128.2C128.5 135.7 120.1 150.4 120.4 166.1C121.1 194.7 117 209.8 102.1 234.2C93.9 247.6 93.9 264.5 102.1 277.9C117 302.3 121 317.4 120.4 346C120 361.7 128.5 376.3 142.3 383.9C164.4 396 175.6 406 187.4 425.4L138.7 522.5C132.8 534.4 137.6 548.8 149.4 554.7L235.4 597.7C246.9 603.4 260.9 599.1 267.1 587.9L319.9 492.8L372.7 587.9C378.9 599.1 392.9 603.5 404.4 597.7L490.4 554.7C502.3 548.8 507.1 534.4 501.1 522.5L452.5 425.3C464.2 405.9 475.5 395.9 497.6 383.8C511.4 376.3 519.8 361.6 519.5 345.9C518.8 317.3 522.9 302.2 537.8 277.8C546 264.4 546 247.5 537.8 234.1C522.9 209.7 518.9 194.6 519.5 166C519.9 150.3 511.4 135.7 497.6 128.1C472.5 114.4 461.4 103.3 447.7 78.2C440.2 64.4 425.5 56 409.8 56.3C381.2 57 366.1 52.9 341.7 38zM320 160C373 160 416 203 416 256C416 309 373 352 320 352C267 352 224 309 224 256C224 203 267 160 320 160z" /></svg>
                </div>
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[48px] md:text-[60px] leading-[1.2] text-white shadow-sm">Galeri Penghargaan</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[18px] leading-[28px] text-[rgba(255,255,255,0.9)]">
                    Penghormatan tertinggi bagi institusi yang telah membuktikan dedikasinya dalam membangun karakter patriotik.
                </p>
            </div>
        </section>

        {{-- Winners List --}}
        <section class="py-16" x-data="{ filter: 'Semua' }">
            <div class="max-w-[1472px] mx-auto px-8">
                {{-- Filter Bar --}}
                <div class="bg-white rounded-2xl border border-[#f1f5f9] shadow-sm p-4 flex items-center justify-between mb-8">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] leading-[32px] text-[#1d293d] pl-4">Daftar Pemenang</h2>
                    <select
                        x-model="filter"
                        class="bg-[#f8fafc] border border-[#e2e8f0] rounded-2xl px-4 py-2 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] text-[#45556c] outline-none"
                    >
                        <option value="Semua">Semua</option>
                        <option value="Edisi 2025">Edisi 2025</option>
                        <option value="Edisi 2024">Edisi 2024</option>
                    </select>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                    $winners = [
                        ['name' => "Universitas Indonesia", 'edition' => "Edisi 2025", 'rating' => 5],
                        ['name' => "Institut Teknologi Bandung", 'edition' => "Edisi 2025", 'rating' => 5],
                        ['name' => "Universitas Gadjah Mada", 'edition' => "Edisi 2025", 'rating' => 4],
                        ['name' => "Universitas Brawijaya", 'edition' => "Edisi 2025", 'rating' => 3.5],
                        ['name' => "Universitas Airlangga", 'edition' => "Edisi 2024", 'rating' => 5],
                        ['name' => "Universitas Diponegoro", 'edition' => "Edisi 2024", 'rating' => 4],
                    ];
                    @endphp
                    @foreach($winners as $winner)
                    <div x-show="filter === 'Semua' || filter === '{{ $winner['edition'] }}'" class="bg-white border border-[#f1f5f9] rounded-[32px] shadow-lg p-8 flex flex-col items-center">
                        <div class="bg-white rounded-2xl border border-[#f8fafc] shadow-md size-24 flex items-center justify-center mb-4">
                            <img src="{{ asset('assets/images/9700e32bd617466cb3ab48f30928d8a49957eb25.png') }}" alt="" class="w-20 h-20 object-contain rounded-2xl" />
                        </div>
                        <div class="bg-[#f8fafc] rounded-full px-3 py-1 flex items-center gap-1.5 mb-4">
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[12px] text-[#62748e]">{{ $winner['edition'] }}</span>
                        </div>
                        <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] leading-[28px] text-[#1d293d] text-center mb-3">{{ $winner['name'] }}</h3>
                        
                        {{-- Star Rating Component --}}
                        <div class="flex gap-1 justify-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $fillColor = $i <= floor($winner['rating']) ? '#D4AF37' : ($i - 0.5 <= $winner['rating'] ? '#D4AF37' : '#E2E8F0');
                                @endphp
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path
                                        d="M10 1.66667L12.575 6.88333L18.3333 7.72499L14.1667 11.7833L15.15 17.5167L10 14.8083L4.85 17.5167L5.83333 11.7833L1.66667 7.72499L7.425 6.88333L10 1.66667Z"
                                        fill="{{ $fillColor }}"
                                        stroke="{{ $fillColor }}"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.66667"
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
