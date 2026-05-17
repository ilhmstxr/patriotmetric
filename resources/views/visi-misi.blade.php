@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('visi-misi');
    $heroJudul = $content->get('hero', collect())->firstWhere('key', 'judul')?->value ?? 'Visi & Misi';
    $heroDeskripsi = $content->get('hero', collect())->firstWhere('key', 'deskripsi')?->value ?? '';
    $visiTeks = $content->get('visi', collect())->firstWhere('key', 'teks')?->value ?? '';
    $misiJudul = $content->get('misi', collect())->firstWhere('key', 'judul')?->value ?? 'Misi Strategis';
    $misiDaftarRaw = $content->get('misi', collect())->firstWhere('key', 'daftar')?->value;
    $misiDaftar = is_array($misiDaftarRaw) ? $misiDaftarRaw : (is_string($misiDaftarRaw) ? json_decode($misiDaftarRaw, true) ?? [] : []);
@endphp

<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    {{ $heroDeskripsi }}
                </p>
            </div>
        </section>

        {{-- Visi --}}
        <section class="py-16 md:py-24 bg-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-[#1B5E20]/[0.02] rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative max-w-[900px] mx-auto px-6 md:px-8 text-center">
                <div class="inline-flex items-center gap-2 mb-6">
                    <div class="w-8 h-[2px] bg-[#d4af37]"></div>
                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[14px] text-[#d4af37] uppercase tracking-[2px]">Visi Kami</span>
                    <div class="w-8 h-[2px] bg-[#d4af37]"></div>
                </div>
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[26px] sm:text-[32px] md:text-[40px] leading-[1.35] text-[#1d293d]">
                    "{{ $visiTeks }}"
                </h1>
            </div>
        </section>

        {{-- Misi --}}
        @if(count($misiDaftar) > 0)
        <section class="py-16 md:py-24 bg-[#f8fafc]">
            <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                <div class="flex items-center gap-4 mb-12">
                    <div class="w-1 h-8 bg-[#1B5E20] rounded-full"></div>
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[26px] md:text-[32px] text-[#1d293d]">{{ $misiJudul }}</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($misiDaftar as $item)
                        <div class="bg-white rounded-2xl border border-[#f1f5f9] p-8 relative overflow-hidden hover:shadow-lg hover:border-[#1B5E20]/10 transition-all duration-300 group">
                            <span class="absolute top-6 right-6 font-['Plus_Jakarta_Sans',sans-serif] font-extrabold text-[56px] leading-none text-[#f1f5f9] group-hover:text-[#1B5E20]/5 transition-colors">{{ $item['nomor'] ?? '' }}</span>
                            <div class="relative">
                                <div class="w-10 h-10 rounded-xl bg-[#1B5E20]/10 flex items-center justify-center mb-5">
                                    <i data-lucide="chevron-right" class="w-5 h-5 text-[#1B5E20]"></i>
                                </div>
                                <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[20px] md:text-[22px] text-[#1d293d] mb-3">{{ $item['judul'] ?? '' }}</h3>
                                <p class="font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[25px] text-[#45556c]">{{ $item['deskripsi'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </div>
</x-layouts.app>
