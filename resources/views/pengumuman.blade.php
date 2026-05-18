@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('pengumuman');

    $hero = $content->get('hero', collect());
    $artikel = $content->get('artikel', collect());

    $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Pengumuman';
    $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? 'Informasi terbaru seputar Patriot Metric.';

    $artikelDaftar = $artikel->firstWhere('key', 'daftar')?->value ?? [];
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

        {{-- Article List --}}
        @if(!empty($artikelDaftar))
            <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
                <div class="divide-y divide-[#e2e8f0]">
                    @foreach($artikelDaftar as $article)
                        <a href="#" class="flex flex-col md:flex-row gap-5 md:gap-8 py-8 first:pt-0 last:pb-0 group">
                            {{-- Thumbnail --}}
                            <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                                @if(!empty($article['gambar']))
                                    <img src="{{ url('cms-assets/' . $article['gambar']) }}" alt="{{ $article['judul'] ?? '' }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                        <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                    </div>
                                @endif
                            </div>
                            {{-- Text --}}
                            <div class="flex-1 min-w-0">
                                @if(!empty($article['tanggal']))
                                    <span class="font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-[#94a3b8]">
                                        {{ \Carbon\Carbon::parse($article['tanggal'])->translatedFormat('j F Y') }}
                                    </span>
                                @endif
                                @if(!empty($article['judul']))
                                    <h2 class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d] group-hover:text-[#1B5E20] transition-colors">{{ $article['judul'] }}</h2>
                                @endif
                                @if(!empty($article['excerpt']))
                                    <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[25px] text-[#64748b] line-clamp-2">{{ $article['excerpt'] }}</p>
                                @endif
                                <span class="inline-block mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1B5E20] group-hover:underline">Lihat selengkapnya →</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
