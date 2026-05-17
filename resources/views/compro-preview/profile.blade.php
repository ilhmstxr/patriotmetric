<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }
    </style>
</head>
<body class="bg-white">
    @php
        $hero = $content->get('hero', collect());
        $latarBelakang = $content->get('latar-belakang', collect());
        $tujuanUtama = $content->get('tujuan-utama', collect());

        $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? '';
        $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? '';
        $heroBackground = $hero->firstWhere('key', 'background_image')?->value ?? '';

        $latarJudul = $latarBelakang->firstWhere('key', 'judul')?->value ?? 'Latar Belakang';
        $latarDeskripsi = $latarBelakang->firstWhere('key', 'deskripsi')?->value ?? '';

        $tujuanJudul = $tujuanUtama->firstWhere('key', 'judul')?->value ?? '';
        $tujuanDeskripsi = $tujuanUtama->firstWhere('key', 'deskripsi')?->value ?? '';
        $tujuanDaftar = $tujuanUtama->firstWhere('key', 'daftar')?->value ?? [];
    @endphp

    {{-- Hero Section --}}
    <section class="relative bg-[#0f172b] overflow-hidden">
        <div class="absolute inset-0">
            @if($heroBackground)
                <img src="{{ asset('storage/' . $heroBackground) }}" alt="" class="w-full h-full object-cover opacity-20" />
            @else
                <img src="{{ asset('assets/images/bg.webp') }}" alt="" class="w-full h-full object-cover opacity-20" />
            @endif
            <div class="absolute inset-0 bg-gradient-to-b from-[rgba(27,94,32,0.85)] to-[#0f172b]/90"></div>
        </div>
        <div class="absolute top-16 right-16 w-80 h-80 bg-[#d4af37]/5 rounded-full blur-3xl"></div>
        <div class="relative max-w-[1200px] mx-auto px-6 md:px-8 py-20 md:py-32">
            <h1 class="font-bold text-[36px] sm:text-[48px] md:text-[56px] leading-[1.15] text-white max-w-[700px]">
                {!! $heroJudul !!}
            </h1>
            <p class="mt-5 font-normal text-[17px] md:text-[19px] leading-[30px] text-white/75 max-w-[580px]">
                {{ $heroDeskripsi }}
            </p>
        </div>
    </section>

    {{-- Latar Belakang Section --}}
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-[860px] mx-auto px-6 md:px-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1 h-8 bg-[#1B5E20] rounded-full"></div>
                <h2 class="font-bold text-[26px] md:text-[32px] text-[#1d293d]">{{ $latarJudul }}</h2>
            </div>
            <div class="space-y-5 text-[16px] md:text-[17px] leading-[28px] md:leading-[30px] text-[#45556c] prose prose-p:text-[#45556c] prose-li:text-[#45556c] max-w-none">
                {!! $latarDeskripsi !!}
            </div>
        </div>
    </section>

    {{-- Tujuan Utama Section --}}
    @if(is_array($tujuanDaftar) && count($tujuanDaftar) > 0)
    <section class="py-16 md:py-20 bg-[#f8fafc]">
        <div class="max-w-[1100px] mx-auto px-6 md:px-8">
            <div class="text-center mb-12">
                <h2 class="font-bold text-[26px] md:text-[32px] text-[#1d293d]">{{ $tujuanJudul }}</h2>
                @if($tujuanDeskripsi)
                    <p class="mt-3 text-[16px] text-[#64748b] max-w-[500px] mx-auto">{{ $tujuanDeskripsi }}</p>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($tujuanDaftar as $item)
                    <div class="bg-white rounded-2xl border border-[#f1f5f9] p-7 hover:shadow-lg hover:border-[#1B5E20]/10 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <span class="font-bold text-[28px] text-[#d4af37]/40 leading-none">{{ $item['nomor'] ?? '' }}</span>
                            <div>
                                <h3 class="font-bold text-[17px] text-[#1d293d]">{{ $item['judul'] ?? '' }}</h3>
                                <p class="mt-2 text-[15px] leading-[24px] text-[#45556c]">{{ $item['deskripsi'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</body>
</html>
