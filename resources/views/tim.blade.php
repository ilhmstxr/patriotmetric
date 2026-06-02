@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('tim');

    // Hero section
    $hero = $content->get('hero', collect());
    $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Tim Kami';
    $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? '';

    // Team Grid section
    $teamGrid = $content->get('team-grid', collect());
    $daftar = $teamGrid->firstWhere('key', 'daftar')?->value ?? [];
    if (is_string($daftar)) {
        $daftar = json_decode($daftar, true) ?? [];
    }
@endphp

<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[1200px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
                @if($heroDeskripsi)
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                        {{ $heroDeskripsi }}
                    </p>
                @endif
            </div>
        </section>

        {{-- Team Grid --}}
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
                                            <img src="{{ asset('assets/tim/blank-profile.webp') }}" alt="{{ $member['nama'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500" />
                                        @endif
                                    </div>
                                </div>
                                <div class="p-5 pt-4 text-center">
                                    <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[17px] text-[#1d293d]">{{ $member['nama'] ?? '' }}</h3>
                                    <p class="mt-1.5 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[13px] text-[#1B5E20]">{{ $member['role'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
