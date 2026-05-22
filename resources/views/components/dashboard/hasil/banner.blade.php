{{-- ===================================================== --}}
{{-- HASIL: Banner Total Penilaian                        --}}
{{-- Warna: orange = draft/sementara, hijau = published   --}}
{{-- ===================================================== --}}
<div class="rounded-lg p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 relative overflow-hidden"
     :class="is_published ? 'bg-[#1b5e20]' : 'bg-orange-500'">

    {{-- Teks kiri --}}
    <div class="relative z-10 flex-1">

        {{-- Disclaimer — selalu tampil di kiri atas, kecuali sudah PUBLISHED --}}
        <template x-if="!is_published">
            <div class="flex items-start gap-2 bg-white/20 border border-white/30 rounded-lg px-3 py-2 mb-3 max-w-[420px]">
                <svg class="w-3.5 h-3.5 text-white shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-[11px] text-white/90 font-medium leading-relaxed">
                    <strong>Disclaimer:</strong> Poin bersifat <strong>sementara</strong> berdasarkan jawaban Anda. Nilai final ditentukan oleh reviewer setelah proses validasi selesai.
                </p>
            </div>
        </template>

        {{-- Badge PUBLISHED --}}
        <template x-if="is_published">
            <div class="flex items-center gap-2 bg-white/20 border border-white/30 rounded-lg px-3 py-2 mb-3 max-w-[280px]">
                <svg class="w-3.5 h-3.5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-[11px] text-white/90 font-semibold">Nilai Final — Telah Divalidasi Reviewer</p>
            </div>
        </template>

        {{-- Judul --}}
        <h2 class="text-white font-bold text-[22px] md:text-[28px] leading-tight tracking-tight">
            Total Penilaian <span x-text="tahun_periode"></span>
        </h2>
        <p class="text-white/70 text-[13px] md:text-[14px] font-medium mt-1" x-text="institusi"></p>
    </div>

    {{-- Kotak skor kanan (Circular Progress) --}}
    <div class="relative z-10 shrink-0">
        <div class="relative w-[170px] h-[170px] md:w-[190px] md:h-[190px]">
            <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="52" fill="none"
                        stroke="rgba(255,255,255,0.15)" stroke-width="10" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-white font-extrabold text-[28px] md:text-[32px] leading-none tracking-tight whitespace-nowrap"
                      x-text="Number(total_capaian_skor || 0).toFixed(2).replace('.', ',')"></span>
                <span class="text-white/80 text-[10px] font-bold tracking-[0.2em] uppercase mt-2"
                      x-text="is_published ? 'Skor Final' : 'Estimasi Skor'"></span>
            </div>
        </div>
    </div>
</div>
