<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - Penghargaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white min-h-screen">

    @php
        $heroSection = $content->get('hero', collect());
        $heroJudul = $heroSection->firstWhere('key', 'judul')?->value ?? '';
        $heroDeskripsi = $heroSection->firstWhere('key', 'deskripsi')?->value ?? '';
        $heroBackground = $heroSection->firstWhere('key', 'background_image')?->value ?? '';

        $daftarSection = $content->get('daftar-penerima', collect());
        $daftarJudul = $daftarSection->firstWhere('key', 'judul')?->value ?? '';
        $daftarPenerima = $daftarSection->firstWhere('key', 'daftar')?->value ?? [];
    @endphp

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            @if($heroBackground)
                <img src="{{ asset($heroBackground) }}" alt="" class="w-full h-full object-cover" />
            @else
                <div class="w-full h-full bg-gradient-to-br from-[#1B5E20] to-[#2E7D32]"></div>
            @endif
        </div>
        <div class="relative max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
            <h1 class="font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
            @if($heroDeskripsi)
                <p class="mt-4 text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    {{ $heroDeskripsi }}
                </p>
            @endif
        </div>
    </section>

    {{-- Daftar Penerima Section --}}
    @if(!empty($daftarPenerima))
        <section class="py-12 md:py-16 relative z-10">
            <div class="max-w-[1200px] mx-auto px-6 md:px-8">
                {{-- Section Title --}}
                @if($daftarJudul)
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-1 h-8 bg-[#1B5E20] rounded-full"></div>
                        <h2 class="font-bold text-[22px] md:text-[26px] text-[#1d293d]">{{ $daftarJudul }}</h2>
                    </div>
                @endif

                {{-- Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($daftarPenerima as $winner)
                        <div class="bg-white rounded-2xl border border-[#f1f5f9] p-8 flex flex-col items-center hover:shadow-lg hover:border-[#d4af37]/20 transition-all duration-300 group">
                            {{-- Logo --}}
                            <div class="bg-[#f8fafc] rounded-2xl border border-[#f1f5f9] size-20 flex items-center justify-center mb-5 group-hover:border-[#d4af37]/20 transition-colors">
                                @if(!empty($winner['logo']))
                                    <img src="{{ asset($winner['logo']) }}" alt="{{ $winner['nama'] ?? '' }}" class="w-14 h-14 object-contain" />
                                @else
                                    <div class="w-14 h-14 bg-gray-200 rounded-lg"></div>
                                @endif
                            </div>

                            {{-- Name --}}
                            <h3 class="font-bold text-[18px] text-[#1d293d] text-center mb-4">{{ $winner['nama'] ?? '' }}</h3>

                            {{-- Divider --}}
                            <div class="w-12 h-[2px] bg-[#e2e8f0] mb-4"></div>

                            {{-- Rating Stars --}}
                            @php
                                $rating = floatval($winner['rating'] ?? 0);
                            @endphp
                            <div class="flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        if ($i <= floor($rating)) {
                                            $fillColor = '#D0B55E'; // full star
                                        } elseif ($i - 0.5 <= $rating) {
                                            $fillColor = '#D0B55E'; // half star (rendered as full for simplicity matching original)
                                        } else {
                                            $fillColor = '#E2E8F0'; // empty star
                                        }
                                    @endphp
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="{{ $fillColor }}" stroke="{{ $fillColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</body>
</html>
