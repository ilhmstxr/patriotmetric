@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('berita');

    $hero = $content->get('hero', collect());
    $berita = $content->get('berita', collect());

    $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Berita';
    $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? 'Informasi dan berita terbaru seputar Patriot Metric.';

    $beritaDaftar = $berita->firstWhere('key', 'daftar')?->value ?? [];
@endphp

<div class="bg-white min-h-screen font-['Plus_Jakarta_Sans',sans-serif]">
    {{-- Hero --}}
    <section class="bg-[#1B5E20]">
        <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
            <h1 class="font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
            @if($heroDeskripsi)
                <p class="mt-4 text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    {{ $heroDeskripsi }}
                </p>
            @endif
        </div>
    </section>

    {{-- Berita List --}}
    @if(!empty($beritaDaftar))
        <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
            <div class="divide-y divide-[#e2e8f0]">
                @foreach($beritaDaftar as $item)
                    <div class="py-8 first:pt-0 last:pb-0">
                        <div class="flex flex-col md:flex-row gap-5 md:gap-8">
                            <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                                @if(!empty($item['gambar']))
                                    <img src="{{ url('cms-assets/' . $item['gambar']) }}" alt="{{ $item['judul'] ?? '' }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                        <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                @if(!empty($item['tanggal']))
                                    <span class="text-[13px] text-[#94a3b8]">{{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('j F Y') }}</span>
                                @endif
                                @if(!empty($item['judul']))
                                    <h2 class="mt-1 font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d]">{{ $item['judul'] }}</h2>
                                @endif
                                @if(!empty($item['excerpt']))
                                    <p class="mt-2 text-[15px] leading-[25px] text-[#64748b]">{{ $item['excerpt'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
