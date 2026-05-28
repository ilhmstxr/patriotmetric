{{-- ===================================================== --}}
{{-- PROFIL SECTION: Visi dan Misi                        --}}
{{-- Inline edit: textarea muncul saat isEditMode = true  --}}
{{-- ===================================================== --}}
<div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden"
     :class="isEditMode ? 'ring-1 ring-[#1b5e20]/20' : ''">
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
            {{-- VIEW MODE --}}
            <div x-show="!isEditMode" class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 py-3">
                <p class="text-[13px] font-medium text-[#45556c] leading-relaxed" x-text="profileData.identitas?.visi || '-'"></p>
            </div>
            {{-- EDIT MODE --}}
            <textarea x-show="isEditMode" style="display:none;"
                x-model="editForm.visi"
                rows="3"
                placeholder="Tuliskan visi perguruan tinggi..."
                class="w-full bg-white border border-[#1b5e20]/40 rounded px-4 py-3 text-[13px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition leading-relaxed resize-none"></textarea>
        </div>

        {{-- Misi --}}
        <div>
            <label class="text-[13px] font-semibold text-[#1d293d] mb-2 block">Misi</label>
            {{-- VIEW MODE --}}
            <div x-show="!isEditMode" class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 py-3">
                <p class="text-[13px] font-medium text-[#45556c] leading-relaxed whitespace-pre-line" x-text="profileData.identitas?.misi || '-'"></p>
            </div>
            {{-- EDIT MODE --}}
            <textarea x-show="isEditMode" style="display:none;"
                x-model="editForm.misi"
                rows="5"
                placeholder="Tuliskan misi perguruan tinggi (pisahkan tiap misi dengan baris baru)..."
                class="w-full bg-white border border-[#1b5e20]/40 rounded px-4 py-3 text-[13px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition leading-relaxed resize-none"></textarea>
        </div>
    </div>
</div>
