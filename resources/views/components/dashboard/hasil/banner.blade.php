{{-- ===================================================== --}}
{{-- HASIL: Banner Total Penilaian                        --}}
{{-- ✏️ Ganti tahun, nama PT, dan skor di sini            --}}
{{-- ===================================================== --}}
<div class="bg-[#1b5e20] rounded-lg p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 relative overflow-hidden">
    {{-- Dekorasi lingkaran --}}
    <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-white opacity-5 rounded-full -translate-y-1/3 translate-x-1/3"></div>

    {{-- Teks kiri --}}
    <div class="relative z-10">
        {{-- ✏️ Ganti tahun penilaian --}}
        <h2 class="text-white font-bold text-[22px] md:text-[28px] leading-tight tracking-tight">
            Total Penilaian <span x-text="tahun_periode"></span>
        </h2>
        {{-- ✏️ Ganti nama institusi --}}
        <p class="text-white/70 text-[13px] md:text-[14px] font-medium mt-1" x-text="institusi"></p>
    </div>

    {{-- Kotak skor kanan --}}
    <div class="relative z-10 bg-white/10 backdrop-blur border border-white/20 rounded-lg px-6 py-4 flex flex-col items-center justify-center min-w-[120px] md:min-w-[150px] shrink-0">
        {{-- ✏️ Ganti angka skor total --}}
        <span class="text-white font-extrabold text-[42px] md:text-[52px] leading-none" x-text="total_score"></span>
        {{-- ✏️ Ganti skor maksimal --}}
        <span class="text-white/60 text-[13px] font-medium">/ 100</span>
        <span class="text-white/80 text-[10px] font-bold tracking-widest uppercase mt-1">TOTAL POIN</span>
    </div>
</div>
