{{-- ===================================================== --}}
{{-- RUBRIK: Sticky Footer Simpan Draft                   --}}
{{-- ✏️ Ganti teks label status atau action button        --}}
{{-- ===================================================== --}}
<div class="sticky bottom-0 bg-white border-t border-[#e0e0e0] px-4 md:px-8 py-3 flex items-center justify-between gap-4 z-10">
    <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full" :class="isSaving ? 'bg-orange-400 animate-pulse' : 'bg-[#c8e6c9]'"></span>
        <span class="text-[12px] font-medium text-[#62748e]">
            <template x-if="isSaving"><span>Menyimpan draft...</span></template>
            <template x-if="!isSaving && lastSaved"><span>Draft terakhir disimpan pada <span x-text="lastSaved"></span></span></template>
            <template x-if="!isSaving && !lastSaved"><span>Data formulir disimpan secara berkala di peramban Anda.</span></template>
        </span>
    </div>
    
    <template x-if="status !== 'SUBMITTED' && status !== 'GRADED'">
        <button 
            @click="saveDraft()"
            :disabled="isSaving"
            class="bg-[#1b5e20] hover:bg-[#15461c] text-white text-[13px] font-semibold px-5 h-[38px] rounded transition-colors shrink-0 disabled:opacity-50">
            Simpan & Submit Pendaftaran
        </button>
    </template>
    
    <template x-if="status === 'SUBMITTED' || status === 'GRADED'">
        <span class="bg-blue-100 text-blue-700 text-[13px] font-bold px-4 py-2 rounded-full">
            <i data-lucide="check-circle" class="w-4 h-4 inline-block mr-1 mb-0.5"></i> Formulir Terkirim
        </span>
    </template>
</div>
