{{-- ===================================================== --}}
{{-- PROFIL SECTION: Data Penanggung Jawab (PIC)          --}}
{{-- Inline editable: nama_pic, jabatan_pic, no_hp_pic    --}}
{{-- Email selalu read-only (terikat akun)                --}}
{{-- ===================================================== --}}
<div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden"
     :class="isEditMode ? 'ring-1 ring-[#1b5e20]/20' : ''">
    <div class="flex items-center gap-3 px-5 py-4 border-b border-[#e0e0e0]">
        <div class="w-[32px] h-[32px] bg-[#f5f5f5] rounded-lg flex items-center justify-center shrink-0 border border-[#e0e0e0]">
            <i data-lucide="contact" class="w-[17px] h-[17px] text-[#314158]"></i>
        </div>
        <h2 class="font-bold text-[#1d293d] text-[14px] tracking-wider uppercase">Data Penanggung Jawab (PIC)</h2>
    </div>

    <div class="p-5 md:p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Nama PIC --}}
            <div>
                <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Nama PIC</label>
                {{-- VIEW --}}
                <div x-show="!isEditMode" class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center">
                    <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.pengumpulan?.nama_pic || '-'"></p>
                </div>
                {{-- EDIT --}}
                <input x-show="isEditMode" style="display:none;" type="text"
                    x-model="editForm.nama_pic"
                    placeholder="Nama PIC..."
                    class="w-full bg-white border border-[#1b5e20]/40 rounded px-4 h-[42px] text-[13px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
            </div>

            {{-- Jabatan PIC --}}
            <div>
                <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Jabatan PIC</label>
                {{-- VIEW --}}
                <div x-show="!isEditMode" class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center">
                    <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.pengumpulan?.jabatan_pic || '-'"></p>
                </div>
                {{-- EDIT --}}
                <input x-show="isEditMode" style="display:none;" type="text"
                    x-model="editForm.jabatan_pic"
                    placeholder="Jabatan PIC..."
                    class="w-full bg-white border border-[#1b5e20]/40 rounded px-4 h-[42px] text-[13px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
            </div>

            {{-- Nomor HP --}}
            <div>
                <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Nomor HP / WhatsApp</label>
                {{-- VIEW --}}
                <div x-show="!isEditMode" class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center gap-2">
                    <i data-lucide="phone" class="w-[14px] h-[14px] text-[#90a1b9] shrink-0"></i>
                    <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.pengumpulan?.no_hp_pic || '-'"></p>
                </div>
                {{-- EDIT --}}
                <div x-show="isEditMode" style="display:none;" class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                        <i data-lucide="phone" class="w-[14px] h-[14px] text-[#90a1b9]"></i>
                    </div>
                    <input type="tel"
                        x-model="editForm.no_hp_pic"
                        placeholder="08xx-xxxx-xxxx"
                        class="w-full bg-white border border-[#1b5e20]/40 rounded pl-9 pr-4 h-[42px] text-[13px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                </div>
            </div>

            {{-- Email — editable saat mode edit (validasi .ac.id, terikat akun) --}}
            <div>
                <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Alamat Email</label>
                {{-- VIEW --}}
                <div x-show="!isEditMode" class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center gap-2">
                    <i data-lucide="mail" class="w-[14px] h-[14px] text-[#90a1b9] shrink-0"></i>
                    <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.pengumpulan?.email_pic || '-'"></p>
                </div>
                {{-- EDIT --}}
                <div x-show="isEditMode" style="display:none;" class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                        <i data-lucide="mail" class="w-[14px] h-[14px] text-[#90a1b9]"></i>
                    </div>
                    <input type="email"
                        x-model="editForm.email"
                        pattern="^[^@\s]+@[A-Za-z0-9.-]+\.ac\.id$"
                        title="Gunakan email berdomain .ac.id"
                        placeholder="dosen@institusi.ac.id"
                        class="w-full bg-white border border-[#1b5e20]/40 rounded pl-9 pr-4 h-[42px] text-[13px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                </div>
                <p x-show="isEditMode" style="display:none;" class="text-[10px] text-[#90a1b9] mt-1">* Email harus berdomain institusi resmi (.ac.id) dan akan menjadi alamat login akun Anda.</p>
            </div>
        </div>
    </div>
</div>
