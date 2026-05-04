{{-- ===================================================== --}}
{{-- PROFIL: Periode Bar + Tombol Edit / Simpan           --}}
{{-- Tombol berubah sesuai state isEditMode               --}}
{{-- ===================================================== --}}
<div class="flex items-center justify-end gap-2">
    <button x-show="isEditMode" style="display:none;" type="button" @click="cancelEditMode()"
        class="border border-[#e0e0e0] text-[#62748e] font-semibold text-[13px] px-4 py-1.5 rounded hover:bg-[#f5f5f5] transition-colors focus:outline-none">
        Batal
    </button>

    <button x-show="isEditMode" style="display:none;" type="button" @click="saveProfile()" :disabled="isSavingProfile"
        class="bg-[#1b5e20] text-white font-semibold text-[13px] px-4 py-1.5 rounded hover:bg-[#15461c] transition-colors disabled:opacity-50 focus:outline-none flex items-center gap-1.5">
        <span x-show="isSavingProfile" style="display:none;">
            <svg class="w-3.5 h-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </span>
        <span x-text="isSavingProfile ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
    </button>

    <button x-show="!isEditMode && is_peserta_profile_edit_enabled" style="display:none;" type="button" @click="enterEditMode()"
        class="inline-flex items-center gap-1.5 border border-[#1b5e20] text-[#1b5e20] font-semibold text-[13px] px-4 py-1.5 rounded hover:bg-[#f0fdf4] transition-colors focus:outline-none">
        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
        Edit Profil
    </button>
</div>
