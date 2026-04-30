{{-- ===================================================== --}}
{{-- PROFIL SECTION: Visi dan Misi                        --}}
{{-- ✏️ Ganti teks visi dan poin-poin misi di sini        --}}
{{-- ===================================================== --}}
<div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden">
    {{-- Section Header --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-[#e0e0e0]">
        <div class="w-[32px] h-[32px] bg-[#f5f5f5] rounded-lg flex items-center justify-center shrink-0 border border-[#e0e0e0]">
            <i data-lucide="target" class="w-[17px] h-[17px] text-[#314158]"></i>
        </div>
        <h2 class="font-bold text-[#1d293d] text-[14px] tracking-wider uppercase">Visi dan Misi</h2>
    </div>

    <div class="p-5 md:p-6 space-y-4">
        {{-- Visi --}}
        <div>
            <label class="text-[13px] font-semibold text-[#1d293d] mb-2 block">Visi</label>
            <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 py-3">
                <p class="text-[13px] font-medium text-[#45556c] leading-relaxed" x-text="profileData.identitas?.visi || '-'"></p>
            </div>
        </div>

        {{-- Misi --}}
        <div>
            <label class="text-[13px] font-semibold text-[#1d293d] mb-2 block">Misi</label>
            <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 py-3">
                <p class="text-[13px] font-medium text-[#45556c] leading-relaxed whitespace-pre-line" x-text="profileData.identitas?.misi || '-'"></p>
            </div>
        </div>
    </div>
</div>
