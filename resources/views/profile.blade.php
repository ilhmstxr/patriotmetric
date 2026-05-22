@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('profile');

    $hero = $content->get('hero', collect());
    $latarBelakang = $content->get('latar-belakang', collect());
    $tujuanUtama = $content->get('tujuan-utama', collect());

    $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Membangun Karakter <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#d4af37] to-[#fff085]">Bangsa</span> dari Kampus';
    $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? 'Sebuah sistem pemeringkatan nasional yang didedikasikan untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi.';
    $heroBackground = $hero->firstWhere('key', 'background_image')?->value ?? '';

    $latarJudul = $latarBelakang->firstWhere('key', 'judul')?->value ?? 'Latar Belakang';
    $latarDeskripsi = $latarBelakang->firstWhere('key', 'deskripsi')?->value ?? '';

    $tujuanJudul = $tujuanUtama->firstWhere('key', 'judul')?->value ?? 'Tujuan Utama Pemeringkatan';
    $tujuanDeskripsi = $tujuanUtama->firstWhere('key', 'deskripsi')?->value ?? '';
    $tujuanDaftar = $tujuanUtama->firstWhere('key', 'daftar')?->value ?? [];
@endphp

<x-layouts.app>
    <div class="bg-white">
        {{-- Hero --}}
        <section class="relative bg-[#0f172b] overflow-hidden">
            <div class="absolute inset-0">
                @if($heroBackground)
                    <img src="{{ url('cms-assets/' . $heroBackground) }}" alt="" class="w-full h-full object-cover opacity-20" />
                @else
                    <img src="{{ asset('assets/images/bg.webp') }}" alt="" class="w-full h-full object-cover opacity-20" />
                @endif
                <div class="absolute inset-0 bg-gradient-to-b from-[rgba(27,94,32,0.85)] to-[#0f172b]/90"></div>
            </div>
            <div class="absolute top-16 right-16 w-80 h-80 bg-[#d4af37]/5 rounded-full blur-3xl"></div>
            <div class="relative max-w-[1200px] mx-auto px-6 md:px-8 py-20 md:py-32">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] sm:text-[48px] md:text-[56px] leading-[1.15] text-white max-w-[700px]">
                    {!! $heroJudul !!}
                </h1>
                <p class="mt-5 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[17px] md:text-[19px] leading-[30px] text-white/75 max-w-[580px]">
                    {{ $heroDeskripsi }}
                </p>
            </div>
        </section>

        {{-- Latar Belakang --}}
        <section class="py-16 md:py-24 bg-white">
            <div class="max-w-[860px] mx-auto px-6 md:px-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-1 h-8 bg-[#1B5E20] rounded-full"></div>
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[26px] md:text-[32px] text-[#1d293d]">{{ $latarJudul }}</h2>
                </div>
                <div class="space-y-5 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[17px] leading-[28px] md:leading-[30px] text-[#45556c]">
                    {!! $latarDeskripsi !!}
                </div>
            </div>
        </section>

        {{-- Tujuan Utama --}}
        @if(is_array($tujuanDaftar) && count($tujuanDaftar) > 0)
        <section class="py-16 md:py-20 bg-[#f8fafc]">
            <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                <div class="text-center mb-12">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[26px] md:text-[32px] text-[#1d293d]">{{ $tujuanJudul }}</h2>
                    @if($tujuanDeskripsi)
                        <p class="mt-3 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] text-[#64748b] max-w-[500px] mx-auto">{{ $tujuanDeskripsi }}</p>
                    @endif
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($tujuanDaftar as $item)
                        <div class="bg-white rounded-2xl border border-[#f1f5f9] p-7 hover:shadow-lg hover:border-[#1B5E20]/10 transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] text-[#d4af37]/40 leading-none">{{ $item['nomor'] ?? '' }}</span>
                                <div>
                                    <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[17px] text-[#1d293d]">{{ $item['judul'] ?? '' }}</h3>
                                    <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[24px] text-[#45556c]">{{ $item['deskripsi'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </div>
</x-layouts.app>
