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
<body class="bg-[oklch(0.985_0.005_145)] min-h-screen">

    @php
        $hero = $content->get('hero', collect());
        $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Pengumuman';
        $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? '';

        $artikel = $content->get('artikel', collect());
        $artikelDaftar = $artikel->firstWhere('key', 'daftar')?->value ?? [];
    @endphp

    {{-- Hero --}}
    <section class="bg-[#1B5E20]">
        <div class="max-w-[900px] mx-auto px-6 md:px-8 py-16 md:py-22 text-center">
            <h1 class="font-bold text-[30px] md:text-[44px] text-white leading-tight">{{ $heroJudul }}</h1>
            @if($heroDeskripsi)
                <p class="mt-3 text-[15px] md:text-[17px] leading-[26px] text-white/80 max-w-[540px] mx-auto">
                    {{ $heroDeskripsi }}
                </p>
            @endif
        </div>
    </section>

    {{-- Document List --}}
    <div class="max-w-[900px] mx-auto px-6 md:px-8 py-10 md:py-14">
        @if(!empty($artikelDaftar))
            <div class="space-y-3">
                @foreach($artikelDaftar as $item)
                    <div class="bg-white rounded-lg border border-[oklch(0.92_0.005_145)] px-5 py-4 md:px-6 md:py-5 flex items-start gap-4 md:gap-5">
                        {{-- PDF Icon --}}
                        <div class="shrink-0 w-10 h-10 md:w-11 md:h-11 rounded-lg bg-[oklch(0.94_0.02_15)] flex items-center justify-center mt-0.5">
                            <svg class="w-5 h-5 md:w-[22px] md:h-[22px] text-[oklch(0.5_0.15_15)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <h2 class="font-semibold text-[15px] md:text-[16px] leading-[22px] text-[oklch(0.25_0.02_250)]">
                                {{ $item['judul'] ?? '' }}
                            </h2>
                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                @if(!empty($item['tanggal']))
                                    <span class="text-[13px] text-[oklch(0.55_0.01_250)]">
                                        {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('j F Y') }}
                                    </span>
                                @endif
                                @if(!empty($item['excerpt']))
                                    <span class="hidden md:inline text-[oklch(0.8_0.005_250)]">&middot;</span>
                                    <span class="text-[13px] text-[oklch(0.55_0.01_250)]">
                                        {{ $item['excerpt'] }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        @if(!empty($item['dokumen']))
                            <div class="shrink-0 flex items-center gap-2 mt-0.5">
                                <a href="{{ url('assets/' . $item['dokumen']) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-[13px] font-medium text-[#1B5E20] bg-[oklch(0.95_0.03_145)] hover:bg-[oklch(0.91_0.05_145)] transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="hidden sm:inline">Lihat</span>
                                </a>
                                <a href="{{ url('assets/' . $item['dokumen']) }}" download
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-[13px] font-medium text-white bg-[#1B5E20] hover:bg-[oklch(0.32_0.08_145)] transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    <span class="hidden sm:inline">Unduh</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-14 h-14 mx-auto rounded-full bg-[oklch(0.94_0.02_145)] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[oklch(0.55_0.05_145)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9.75m3 0H9.75m0 0v3m0-3v-3m-3.375-6H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <p class="text-[15px] text-[oklch(0.55_0.01_250)]">Belum ada dokumen pengumuman.</p>
            </div>
        @endif
    </div>

</body>
</html>
