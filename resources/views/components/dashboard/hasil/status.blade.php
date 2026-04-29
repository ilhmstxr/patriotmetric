{{-- ===================================================== --}}
{{-- HASIL: Card Status Penilaian                         --}}
{{-- ✏️ Ganti status label di button kanan                --}}
{{-- ===================================================== --}}
<div class="bg-[#e8f5e9] border border-[#c8e6c9] rounded-lg px-5 py-4 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-[36px] h-[36px] bg-[#1b5e20] rounded-lg flex items-center justify-center shrink-0 shadow-lg">
            <i data-lucide="check" class="w-[18px] h-[18px] text-white"></i>
        </div>
        <div>
            <p class="font-bold text-[#1d293d] text-[13px] uppercase tracking-wide">STATUS PENILAIAN</p>
            <p class="text-[#62748e] text-[12px] font-medium mt-0.5">Kondisi data rubrik Anda saat ini</p>
        </div>
    </div>
    {{-- ✏️ Ganti teks status (misal: "Belum Divalidasi", "Dalam Review", dll) --}}
    <template x-if="is_validated">
        <div class="bg-[#1b5e20] text-white font-semibold text-[12px] px-4 py-2 rounded shrink-0">
            Telah Divalidasi Asesor
        </div>
    </template>
    <template x-if="!is_validated && status === 'SUBMITTED'">
        <div class="bg-blue-600 text-white font-semibold text-[12px] px-4 py-2 rounded shrink-0">
            Menunggu Review Asesor
        </div>
    </template>
    <template x-if="status === 'IN_PROGRESS' || status === 'ACTIVE'">
        <div class="bg-orange-500 text-white font-semibold text-[12px] px-4 py-2 rounded shrink-0">
            Draft (Belum Submit)
        </div>
    </template>
</div>
