{{-- ===================================================== --}}
{{-- PROFIL SECTION: Data Demografi Agama Mahasiswa       --}}
{{-- Inline editable: jumlah per agama                    --}}
{{-- ===================================================== --}}
<div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden"
     :class="isEditMode ? 'ring-1 ring-[#1b5e20]/20' : ''">
    <div class="flex items-center gap-3 px-5 py-4 border-b border-[#e0e0e0]">
        <div class="w-[32px] h-[32px] bg-[#f5f5f5] rounded-lg flex items-center justify-center shrink-0 border border-[#e0e0e0]">
            <i data-lucide="globe" class="w-[17px] h-[17px] text-[#314158]"></i>
        </div>
        <h2 class="font-bold text-[#1d293d] text-[14px] tracking-wider uppercase">Data Demografi Agama Mahasiswa</h2>
    </div>

    <div class="p-5 md:p-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <template x-for="agama in ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Kepercayaan Terhadap Tuhan YME']">
                <div>
                    <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block" x-text="agama"></label>
                    {{-- VIEW --}}
                    <div x-show="!isEditMode" class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center gap-2">
                        <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.agamas[agama.toLowerCase()] || '0'"></p>
                    </div>
                    {{-- EDIT --}}
                    <input x-show="isEditMode" style="display:none;" type="number" min="0"
                        :x-model="`editForm.agamas['${agama.toLowerCase()}']`"
                        @input="editForm.agamas[agama.toLowerCase()] = $event.target.value"
                        :value="editForm.agamas[agama.toLowerCase()] || ''"
                        class="w-full bg-white border border-[#1b5e20]/40 rounded px-4 h-[42px] text-[13px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                </div>
            </template>
        </div>
    </div>
</div>
