<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - Tim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }
    </style>
</head>
<body class="bg-white min-h-screen">

    {{-- Hero Section --}}
    @php
        $hero = $content->get('hero', collect());
        $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Tim Kami';
        $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? '';
    @endphp
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

    {{-- Team Grid Section --}}
    @php
        $teamGrid = $content->get('team-grid', collect());
        $daftar = $teamGrid->firstWhere('key', 'daftar')?->value ?? [];
    @endphp
    @if(count($daftar) > 0)
        <section class="py-14 md:py-20 bg-[#f8fafc]">
            <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($daftar as $member)
                        <div class="bg-white rounded-2xl border border-[#f1f5f9] overflow-hidden hover:shadow-lg hover:border-[#1B5E20]/10 transition-all duration-300 group">
                            <div class="p-4 pb-0">
                                <div class="bg-[#f8fafc] rounded-xl overflow-hidden h-[280px]">
                                    @if(!empty($member['foto']))
                                        <img src="{{ url('cms-assets/' . $member['foto']) }}" alt="{{ $member['nama'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-5 pt-4 text-center">
                                <h3 class="font-bold text-[17px] text-[#1d293d]">{{ $member['nama'] ?? '' }}</h3>
                                <p class="mt-1.5 font-medium text-[13px] text-[#1B5E20]">{{ $member['role'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</body>
</html>
