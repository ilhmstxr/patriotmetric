@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('penghargaan');

    $heroSection = $content->get('hero', collect());
    $heroJudul = $heroSection->firstWhere('key', 'judul')?->value ?? 'Galeri Penghargaan';
    $heroDeskripsi = $heroSection->firstWhere('key', 'deskripsi')?->value ?? '';
    $heroBackground = $heroSection->firstWhere('key', 'background_image')?->value ?? '';

    $daftarSection = $content->get('daftar-penerima', collect());
    $daftarJudul = $daftarSection->firstWhere('key', 'judul')?->value ?? 'Daftar Institusi Peraih Penghargaan';
    $daftarPenerima = $daftarSection->firstWhere('key', 'daftar')?->value ?? [];
@endphp

<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-0">
                @if($heroBackground)
                    <img src="{{ asset($heroBackground) }}" alt="" class="w-full h-full object-cover" />
                @else
                    <img src="{{ asset('assets/images/b4f942a6770a3928dc2f82d398369a3d39ba1fde.webp') }}" alt="" class="w-full h-full object-cover" />
                @endif
            </div>
            <div class="relative max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
                @if($heroDeskripsi)
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                        {{ $heroDeskripsi }}
                    </p>
                @endif
            </div>
        </section>

        {{-- Daftar Penerima --}}
        @if(!empty($daftarPenerima))
            <section class="py-12 md:py-16 relative z-10">
                <div class="max-w-[1200px] mx-auto px-6 md:px-8">
                    {{-- Title --}}
                    @if($daftarJudul)
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-1 h-8 bg-[#1B5E20] rounded-full"></div>
                            <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[22px] md:text-[26px] text-[#1d293d]">{{ $daftarJudul }}</h2>
                        </div>
                    @endif

                    {{-- Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($daftarPenerima as $winner)
                            <div class="bg-white rounded-2xl border border-[#f1f5f9] p-8 flex flex-col items-center hover:shadow-lg hover:border-[#d4af37]/20 transition-all duration-300 group">
                                {{-- Logo --}}
                                <div class="bg-[#f8fafc] rounded-2xl border border-[#f1f5f9] size-20 flex items-center justify-center mb-5 group-hover:border-[#d4af37]/20 transition-colors">
                                    @if(!empty($winner['logo']))
                                        <img src="{{ url('cms-assets/' . $winner['logo']) }}" alt="{{ $winner['nama'] ?? '' }}" class="w-14 h-14 object-contain" />
                                    @else
                                        <img src="{{ asset('assets/images/199dc2ebf1e9cecf5218f4b20951209708831231.webp') }}" alt="" class="w-14 h-14 object-contain" />
                                    @endif
                                </div>

                                {{-- Name --}}
                                <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] text-[#1d293d] text-center mb-4">{{ $winner['nama'] ?? '' }}</h3>

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
                                                $fillColor = '#D0B55E'; // half star
                                            } else {
                                                $fillColor = '#E2E8F0'; // empty star
                                            }
                                        @endphp
                                        <i data-lucide="star" class="w-5 h-5" style="fill: {{ $fillColor }}; color: {{ $fillColor }};"></i>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
