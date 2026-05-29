@php
    $heroJudul = 'Berita';
    $heroDeskripsi = 'Informasi dan berita terkini seputar kegiatan Patriot Metric';
@endphp

<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    {{ $heroDeskripsi }}
                </p>
            </div>
        </section>

        {{-- Berita List --}}
        @if($beritas->isNotEmpty())
            <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
                <div class="divide-y divide-[#e2e8f0]">
                    @foreach($beritas as $item)
                        <a href="{{ route('berita.show', $item->slug) }}" class="flex flex-col md:flex-row gap-5 md:gap-8 py-8 first:pt-0 last:pb-0 group">
                            {{-- Thumbnail --}}
                            <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                                @if($item->gambar)
                                    <img src="{{ asset($item->gambar) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                        <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                    </div>
                                @endif
                            </div>
                            {{-- Text --}}
                            <div class="flex-1 min-w-0">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-[#94a3b8]">
                                    {{ $item->tanggal->translatedFormat('j F Y') }}
                                </span>
                                <h2 class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d] group-hover:text-[#1B5E20] transition-colors">{{ $item->judul }}</h2>
                                <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[25px] text-[#64748b] line-clamp-2">{{ $item->excerpt }}</p>
                                <span class="inline-block mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1B5E20] group-hover:underline">Baca selengkapnya &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <section class="py-16 bg-[#f8fafc]">
                <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                    <div class="text-center py-20">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#1B5E20]/10 mb-6">
                            <i data-lucide="newspaper" class="w-10 h-10 text-[#1B5E20]"></i>
                        </div>
                        <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] text-[#1d293d] mb-3">Belum Ada Berita</h2>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#45556c] max-w-[480px] mx-auto">
                            Belum ada berita yang dipublikasikan. Nantikan informasi dan berita terbaru dari Patriot Metric.
                        </p>
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
