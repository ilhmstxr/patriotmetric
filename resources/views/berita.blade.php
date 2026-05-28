@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('berita');

    $hero = $content->get('hero', collect());
    $berita = $content->get('berita', collect());

    $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Berita';
    $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? 'Informasi dan berita terbaru seputar Patriot Metric.';

    $beritaDaftar = $berita->firstWhere('key', 'daftar')?->value ?? [];
@endphp

<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
                @if($heroDeskripsi)
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                        {{ $heroDeskripsi }}
                    </p>
                @endif
            </div>
        </section>

        {{-- Berita List --}}
        @if(!empty($beritaDaftar))
            <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
                <div class="divide-y divide-[#e2e8f0]">
                    @foreach($beritaDaftar as $index => $item)
                        <div x-data="{ open: false }" class="py-8 first:pt-0 last:pb-0">
                            <div class="flex flex-col md:flex-row gap-5 md:gap-8 cursor-pointer group" @click="open = !open">
                                {{-- Thumbnail --}}
                                <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                                    @if(!empty($item['gambar']))
                                        <img src="{{ url('cms-assets/' . $item['gambar']) }}" alt="{{ $item['judul'] ?? '' }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                            <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                        </div>
                                    @endif
                                </div>
                                {{-- Text --}}
                                <div class="flex-1 min-w-0">
                                    @if(!empty($item['tanggal']))
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-[#94a3b8]">
                                            {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('j F Y') }}
                                        </span>
                                    @endif
                                    @if(!empty($item['judul']))
                                        <h2 class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d] group-hover:text-[#1B5E20] transition-colors">{{ $item['judul'] }}</h2>
                                    @endif
                                    @if(!empty($item['excerpt']))
                                        <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[25px] text-[#64748b] line-clamp-2">{{ $item['excerpt'] }}</p>
                                    @endif
                                    <span class="inline-flex items-center gap-1 mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1B5E20] group-hover:underline">
                                        <span x-text="open ? 'Tutup' : 'Baca selengkapnya'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </span>
                                </div>
                            </div>

                            {{-- Expandable Content --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="mt-6 ml-0 md:ml-[272px]" style="display: none;">
                                <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-lg p-6">
                                    <div class="font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[26px] text-[#334155] whitespace-pre-line">{{ $item['konten'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
