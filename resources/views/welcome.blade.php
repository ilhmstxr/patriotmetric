@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('welcome');

    $hero = $content->get('hero', collect());
    $about = $content->get('about', collect());
    $institusi = $content->get('institusi', collect());
    $timeline = $content->get('timeline', collect());
    $instagram = $content->get('instagram', collect());

    // Helper to get value by key from a section collection
    $getValue = function($section, $key) {
        return $section->firstWhere('key', $key)?->value;
    };
@endphp

<x-layouts.app>
    <div class="bg-white">
        {{-- Hero Section --}}
        <section class="relative bg-[#0f172b] overflow-hidden">
            <div class="absolute inset-0">
                @if($getValue($hero, 'background_image'))
                    <img src="{{ url('cms-assets/' . $getValue($hero, 'background_image')) }}" alt="" class="w-full h-full object-cover opacity-30" />
                @endif
                <div class="absolute inset-0 bg-gradient-to-r from-[rgba(27,94,32,0.9)] via-[rgba(27,94,32,0.2)] to-transparent"></div>
            </div>
            <div class="relative max-w-[1536px] mx-auto px-6 md:px-8 py-24 md:py-44 flex flex-col items-end text-right">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] sm:text-[48px] md:text-[60px] leading-[1.2] text-white max-w-[768px]">
                    {{ $getValue($hero, 'judul') ?? 'Membangun Karakter Bangsa dari Kampus' }}
                </h1>
                <p class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] md:text-[20px] leading-[32.5px] text-[rgba(255,255,255,0.8)] max-w-[616px]">
                    {{ $getValue($hero, 'deskripsi') ?? '' }}
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ url('/daftar') }}" class="w-full sm:w-auto bg-[#d4af37] text-[#1d293d] font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] px-8 py-4 rounded-2xl shadow-lg hover:brightness-110 transition flex items-center justify-center gap-2">
                        Daftarkan Institusi Anda
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="{{ url('/panduan') }}" class="w-full sm:w-auto border border-white/30 text-white font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[16px] px-8 py-4 rounded-2xl hover:bg-white/10 transition flex items-center justify-center">
                        Panduan Penilaian
                    </a>
                </div>
            </div>
        </section>

        {{-- About Section --}}
        <section class="bg-[#f8fafc] py-24">
            <div class="max-w-[1536px] mx-auto px-6 md:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div class="relative">
                            <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[36px] leading-[40px] text-[#1d293d]">{{ $getValue($about, 'judul') ?? 'Patriot Metric' }}</h2>
                            <div class="bg-[#d4af37] h-1 w-20 rounded-full mt-2"></div>
                        </div>
                        <div class="mt-8 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[29.25px] text-[#45556c] prose prose-lg max-w-none [&_p]:mb-4 [&_ul]:mt-6 [&_ul]:flex [&_ul]:flex-col [&_ul]:gap-3 [&_ul]:pt-4 [&_li]:flex [&_li]:items-center [&_li]:gap-3 [&_li]:font-medium [&_li]:text-[#314158] [&_li]:before:content-[''] [&_li]:before:w-2.5 [&_li]:before:h-2.5 [&_li]:before:rounded-full [&_li]:before:bg-[#1B5E20] [&_li]:before:shrink-0 [&_li]:list-none">
                            {!! $getValue($about, 'deskripsi') ?? '' !!}
                        </div>
                    </div>
                    <div class="relative">
                        @if($getValue($about, 'video_url'))
                            <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-[#f1f5f9] aspect-video">
                                <iframe
                                    class="w-full h-full absolute inset-0"
                                    src="{{ $getValue($about, 'video_url') }}"
                                    title="YouTube video player"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Institusi yang Telah Berpartisipasi --}}
        @php
            $institusiItems1 = $getValue($institusi, 'daftar_baris_1') ?? [];
            $institusiItems2 = $getValue($institusi, 'daftar_baris_2') ?? [];
        @endphp
        @if(count($institusiItems1) > 0 || count($institusiItems2) > 0)
        <section class="relative bg-[#f8fafc] py-16 overflow-hidden">
            <div class="max-w-[1536px] mx-auto px-6 md:px-8 mb-10 text-center">
                <h2 class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[30px] text-[#1d293d]">{{ $getValue($institusi, 'judul') ?? '' }}</h2>
                <p class="mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#45556c] max-w-[480px] mx-auto">
                    {{ $getValue($institusi, 'deskripsi') ?? '' }}
                </p>
            </div>

            {{-- Baris 1: scroll ke kiri --}}
            @if(count($institusiItems1) > 0)
            <div class="relative marquee-wrapper mb-4">
                <div class="pointer-events-none absolute left-0 top-0 h-full w-32 bg-gradient-to-r from-[#f8fafc] to-transparent z-10"></div>
                <div class="pointer-events-none absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-[#f8fafc] to-transparent z-10"></div>
                <div class="marquee-content marquee-content--left">
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusiItems1 as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#1B5E20]/20 transition-all duration-300">
                                @if(!empty($inst['logo']))
                                    <div class="size-9 rounded-full overflow-hidden shrink-0">
                                        <img src="{{ url('cms-assets/' . $inst['logo']) }}" alt="{{ $inst['nama'] }}" class="w-full h-full object-cover" />
                                    </div>
                                @else
                                    <div class="size-9 rounded-full bg-[#1B5E20]/10 flex items-center justify-center shrink-0">
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#1B5E20] text-center leading-tight">{{ Str::limit($inst['nama'] ?? '', 6, '') }}</span>
                                    </div>
                                @endif
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] ?? '' }}</span>
                            </div>
                        @endforeach
                    @endfor
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusiItems1 as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#1B5E20]/20 transition-all duration-300">
                                @if(!empty($inst['logo']))
                                    <div class="size-9 rounded-full overflow-hidden shrink-0">
                                        <img src="{{ url('cms-assets/' . $inst['logo']) }}" alt="{{ $inst['nama'] }}" class="w-full h-full object-cover" />
                                    </div>
                                @else
                                    <div class="size-9 rounded-full bg-[#1B5E20]/10 flex items-center justify-center shrink-0">
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#1B5E20] text-center leading-tight">{{ Str::limit($inst['nama'] ?? '', 6, '') }}</span>
                                    </div>
                                @endif
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] ?? '' }}</span>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
            @endif

            {{-- Baris 2: scroll ke kanan --}}
            @if(count($institusiItems2) > 0)
            <div class="relative marquee-wrapper">
                <div class="pointer-events-none absolute left-0 top-0 h-full w-32 bg-gradient-to-r from-[#f8fafc] to-transparent z-10"></div>
                <div class="pointer-events-none absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-[#f8fafc] to-transparent z-10"></div>
                <div class="marquee-content marquee-content--right">
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusiItems2 as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#d4af37]/30 transition-all duration-300">
                                @if(!empty($inst['logo']))
                                    <div class="size-9 rounded-full overflow-hidden shrink-0">
                                        <img src="{{ url('cms-assets/' . $inst['logo']) }}" alt="{{ $inst['nama'] }}" class="w-full h-full object-cover" />
                                    </div>
                                @else
                                    <div class="size-9 rounded-full bg-[#d4af37]/15 flex items-center justify-center shrink-0">
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#b8941f] text-center leading-tight">{{ Str::limit($inst['nama'] ?? '', 6, '') }}</span>
                                    </div>
                                @endif
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] ?? '' }}</span>
                            </div>
                        @endforeach
                    @endfor
                    @for($i = 0; $i < 6; $i++)
                        @foreach($institusiItems2 as $inst)
                            <div class="flex items-center gap-3 shrink-0 bg-white border border-[#f1f5f9] rounded-xl px-4 py-3 shadow-sm hover:shadow-md hover:border-[#d4af37]/30 transition-all duration-300">
                                @if(!empty($inst['logo']))
                                    <div class="size-9 rounded-full overflow-hidden shrink-0">
                                        <img src="{{ url('cms-assets/' . $inst['logo']) }}" alt="{{ $inst['nama'] }}" class="w-full h-full object-cover" />
                                    </div>
                                @else
                                    <div class="size-9 rounded-full bg-[#d4af37]/15 flex items-center justify-center shrink-0">
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[9px] text-[#b8941f] text-center leading-tight">{{ Str::limit($inst['nama'] ?? '', 6, '') }}</span>
                                    </div>
                                @endif
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#314158]">{{ $inst['nama'] ?? '' }}</span>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
            @endif
        </section>
        @endif

        {{-- Timeline Section --}}
        @php
            $timelineItems = $getValue($timeline, 'daftar') ?? [];
        @endphp
        @if(count($timelineItems) > 0)
        <section class="relative bg-white py-24 overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute top-20 left-0 w-[300px] h-[300px] bg-[#1B5E20]/[0.02] rounded-full -translate-x-1/2"></div>
            <div class="absolute bottom-20 right-0 w-[250px] h-[250px] bg-[#d4af37]/[0.03] rounded-full translate-x-1/2"></div>
            <div class="relative max-w-[1536px] mx-auto px-6 md:px-8">
                <div class="text-center max-w-[672px] mx-auto mb-16">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[36px] leading-[40px] text-[#1d293d]">{{ $getValue($timeline, 'judul') ?? '' }}</h2>
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[#45556c]">
                        {{ $getValue($timeline, 'deskripsi') ?? '' }}
                    </p>
                </div>

                <div class="relative max-w-[900px] mx-auto">
                    {{-- Timeline line --}}
                    <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-[rgba(27,94,32,0.1)] via-[#1b5e20] to-[rgba(27,94,32,0.1)] rounded-full -translate-x-1/2"></div>
                    <div class="md:hidden absolute left-[24px] top-0 bottom-0 w-1 bg-gradient-to-b from-[rgba(27,94,32,0.1)] via-[#1b5e20] to-[rgba(27,94,32,0.1)] rounded-full -translate-x-1/2"></div>

                    @foreach($timelineItems as $index => $item)
                        @php $isRight = $index % 2 === 0; @endphp
                        <div class="relative flex md:items-start mb-12 last:mb-0">
                            {{-- Circle --}}
                            <div class="absolute left-[24px] md:left-1/2 -translate-x-1/2 bg-white border-4 border-[#f8fafc] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] rounded-full size-[48px] md:size-[74px] flex items-center justify-center z-10">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] md:text-[24px] text-[#1b5e20]">{{ $item['nomor'] ?? '' }}</span>
                            </div>
                            {{-- Content --}}
                            <div class="w-full pl-[64px] md:pl-0 md:w-[calc(50%-60px)] {{ $isRight ? 'md:ml-auto md:pl-8 text-left' : 'md:mr-auto md:pr-8 text-left md:text-right' }}">
                                <div class="bg-white rounded-xl p-5 shadow-sm border border-[#f1f5f9] hover:shadow-md hover:border-[#1B5E20]/10 transition-all duration-300">
                                    <div class="inline-block bg-[rgba(212,175,55,0.1)] rounded-full px-3 py-1 mb-2">
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[13px] text-[#d4af37] uppercase tracking-wider">{{ $item['tanggal'] ?? '' }}</span>
                                    </div>
                                    <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d]">{{ $item['judul'] ?? '' }}</h3>
                                    <p class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[15px] leading-[24px] text-[#45556c]">{{ $item['deskripsi'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- Instagram Feed Section --}}
        @php
            $instagramPosts = $getValue($instagram, 'posts') ?? [];
        @endphp
        @if(count($instagramPosts) > 0)
        <section class="relative bg-[#f8fafc] py-24 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-[#1B5E20]/[0.02] rounded-full -translate-y-1/2"></div>
            <div class="relative max-w-[1536px] mx-auto px-6 md:px-8">
                <div class="text-center max-w-[672px] mx-auto mb-12">
                    <div class="inline-flex items-center gap-2 bg-[#1B5E20] text-white text-[13px] font-semibold px-4 py-1.5 rounded-full mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Instagram
                    </div>
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[36px] leading-[40px] text-[#1d293d]">{{ $getValue($instagram, 'judul') ?? '' }}</h2>
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[28px] text-[#45556c]">
                        {{ $getValue($instagram, 'deskripsi') ?? '' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-10 max-w-[672px] mx-auto">
                    @foreach($instagramPosts as $post)
                        <a href="{{ $post['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"
                           class="group relative block w-full overflow-hidden rounded-2xl border border-[#f1f5f9] shadow-sm hover:shadow-xl hover:border-[#d4af37] hover:-translate-y-1 transition-all duration-300"
                           style="height: 320px;">
                            @if(!empty($post['gambar']))
                                <img
                                    src="{{ url('cms-assets/' . $post['gambar']) }}"
                                    alt="{{ $post['alt_text'] ?? '' }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                />
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#1B5E20]/10 to-[#d4af37]/10 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </div>
                            @endif
                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-[#1B5E20]/75 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <svg class="w-8 h-8 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    <span class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px]">Lihat di Instagram</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="https://www.instagram.com/patriotmetric.upnjatim/" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-[#1B5E20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] px-8 py-4 rounded-2xl shadow-lg hover:bg-[#145214] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Kunjungi @patriotmetric.upnjatim
                    </a>
                </div>
            </div>
        </section>
        @endif
    </div>
</x-layouts.app>
