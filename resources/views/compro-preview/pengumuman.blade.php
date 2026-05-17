<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - Pengumuman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white min-h-screen">

    @php
        $hero = $content->get('hero', collect());
        $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Pengumuman';
        $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? '';

        $artikel = $content->get('artikel', collect());
        $artikelDaftar = $artikel->firstWhere('key', 'daftar')?->value ?? [];
    @endphp

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

    {{-- Article List --}}
    @if(!empty($artikelDaftar))
        <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
            <div class="divide-y divide-[#e2e8f0]">
                @foreach($artikelDaftar as $article)
                    <div class="flex flex-col md:flex-row gap-5 md:gap-8 py-8 first:pt-0 last:pb-0 group">
                        {{-- Thumbnail --}}
                        <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                            @if(!empty($article['gambar']))
                                <img src="{{ asset('storage/' . $article['gambar']) }}" alt="{{ $article['judul'] ?? '' }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                    <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                </div>
                            @endif
                        </div>
                        {{-- Text --}}
                        <div class="flex-1 min-w-0">
                            @if(!empty($article['tanggal']))
                                <span class="text-[13px] text-[#94a3b8]">
                                    {{ \Carbon\Carbon::parse($article['tanggal'])->translatedFormat('j F Y') }}
                                </span>
                            @endif
                            @if(!empty($article['judul']))
                                <h2 class="mt-1 font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d]">{{ $article['judul'] }}</h2>
                            @endif
                            @if(!empty($article['excerpt']))
                                <p class="mt-2 text-[15px] leading-[25px] text-[#64748b] line-clamp-2">{{ $article['excerpt'] }}</p>
                            @endif
                            <span class="inline-block mt-3 font-medium text-[14px] text-[#1B5E20]">Lihat selengkapnya →</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</body>
</html>
