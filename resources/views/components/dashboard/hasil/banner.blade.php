{{-- ===================================================== --}}
{{-- HASIL: Banner Total Penilaian                        --}}
{{-- ✏️ Ganti tahun, nama PT, dan skor di sini            --}}
{{-- ===================================================== --}}
<div class="bg-orange-500 rounded-lg p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 relative overflow-hidden">

    {{-- Teks kiri --}}
    <div class="relative z-10">
        {{-- ✏️ Ganti tahun penilaian --}}
        <h2 class="text-white font-bold text-[22px] md:text-[28px] leading-tight tracking-tight">
            Total Penilaian <span x-text="tahun_periode"></span>
        </h2>
        {{-- ✏️ Ganti nama institusi --}}
        <p class="text-white/70 text-[13px] md:text-[14px] font-medium mt-1" x-text="institusi"></p>
    </div>

    {{-- Kotak skor kanan (Circular Progress) --}}
    <div class="relative z-10 shrink-0">
        <div class="relative w-[170px] h-[170px] md:w-[190px] md:h-[190px]">
            {{-- SVG Circular Progress --}}
            <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                {{-- Track --}}
                <circle cx="60" cy="60" r="52" fill="none"
                        stroke="rgba(255,255,255,0.15)" stroke-width="10" />
            </svg>
            {{-- Center Text --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-white font-extrabold text-[28px] md:text-[32px] leading-none tracking-tight whitespace-nowrap"
                      x-text="Number(total_capaian_skor || 0).toFixed(2).replace('.', ',') + '%'"></span>
                <span class="text-white/80 text-[10px] font-bold tracking-[0.2em] uppercase mt-2">Total Skor</span>
            </div>
        </div>
    </div>
</div>
