{{-- ========================================================== --}}
{{-- EDIT PROFIL MODAL — Komponen modal untuk edit data PIC    --}}
{{-- Dikontrol oleh x-data dari parent: is_peserta_profile_edit_enabled --}}
{{-- ========================================================== --}}

<template x-if="is_peserta_profile_edit_enabled">
    <div
        x-show="editProfilOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display:none;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editProfilOpen = false"></div>

        {{-- Modal Card --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[440px] overflow-hidden z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#f1f5f9]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#f0fdf4] rounded-lg flex items-center justify-center">
                        <i data-lucide="user-pen" class="w-4 h-4 text-[#1b5e20]"></i>
                    </div>
                    <h3 class="font-bold text-[#1d293d] text-[15px]">Edit Data PIC</h3>
                </div>
                <button type="button" @click="editProfilOpen = false"
                    class="text-[#90a1b9] hover:text-[#45556c] p-1.5 rounded-lg hover:bg-[#f1f5f9] transition-colors focus:outline-none">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5 space-y-4">
                {{-- Info Notice --}}
                <div class="bg-[#f0fdf4] border border-[#bbf7d0] rounded-xl p-3 flex gap-2.5 items-start">
                    <i data-lucide="info" class="w-4 h-4 text-[#16a34a] shrink-0 mt-0.5"></i>
                    <p class="text-[12px] text-[#166534] leading-snug">
                        Data yang dapat diedit adalah informasi kontak PIC. Data institusi tidak dapat diubah.
                    </p>
                </div>

                {{-- Field: Nama PIC --}}
                <div>
                    <label class="block text-[12px] font-semibold text-[#45556c] mb-1.5">
                        Nama PIC <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i data-lucide="user" class="w-4 h-4 text-[#90a1b9]"></i>
                        </div>
                        <input type="text"
                            x-model="editForm.nama_pic"
                            placeholder="Nama lengkap PIC"
                            class="w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-xl pl-10 pr-4 py-2.5 text-[13px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition"
                            required />
                    </div>
                </div>

                {{-- Field: Jabatan PIC --}}
                <div>
                    <label class="block text-[12px] font-semibold text-[#45556c] mb-1.5">
                        Jabatan PIC
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i data-lucide="briefcase" class="w-4 h-4 text-[#90a1b9]"></i>
                        </div>
                        <input type="text"
                            x-model="editForm.jabatan_pic"
                            placeholder="Jabatan PIC di institusi"
                            class="w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-xl pl-10 pr-4 py-2.5 text-[13px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                    </div>
                </div>

                {{-- Field: No HP --}}
                <div>
                    <label class="block text-[12px] font-semibold text-[#45556c] mb-1.5">
                        No HP / WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i data-lucide="phone" class="w-4 h-4 text-[#90a1b9]"></i>
                        </div>
                        <input type="tel"
                            x-model="editForm.no_hp_pic"
                            placeholder="08xx-xxxx-xxxx"
                            class="w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-xl pl-10 pr-4 py-2.5 text-[13px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition"
                            required />
                    </div>
                </div>

                {{-- Error / Success Message --}}
                <div x-show="editProfilError" style="display:none;"
                    class="bg-red-50 border border-red-200 rounded-xl p-3 text-[12px] text-red-600 font-medium"
                    x-text="editProfilError"></div>
                <div x-show="editProfilSuccess" style="display:none;"
                    class="bg-green-50 border border-green-200 rounded-xl p-3 text-[12px] text-green-700 font-medium"
                    x-text="editProfilSuccess"></div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-[#f1f5f9] flex items-center justify-end gap-3">
                <button type="button" @click="editProfilOpen = false"
                    class="px-4 py-2 text-[13px] font-semibold text-[#62748e] bg-[#f1f5f9] hover:bg-[#e2e8f0] rounded-xl transition-colors focus:outline-none">
                    Batal
                </button>
                <button type="button" @click="submitEditProfil()"
                    :disabled="editProfilLoading"
                    class="px-5 py-2 text-[13px] font-semibold text-white bg-[#1b5e20] hover:bg-[#15461c] rounded-xl transition-colors focus:outline-none disabled:opacity-50 flex items-center gap-2">
                    <span x-show="editProfilLoading" style="display:none;">
                        <svg class="w-3.5 h-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span x-show="!editProfilLoading">Simpan Perubahan</span>
                    <span x-show="editProfilLoading" style="display:none;">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</template>
