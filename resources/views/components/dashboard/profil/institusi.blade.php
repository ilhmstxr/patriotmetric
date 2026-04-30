{{-- ===================================================== --}}
{{-- PROFIL SECTION: Data Institusi                       --}}
{{-- ✏️ Ganti data institusi di sini                      --}}
{{-- ===================================================== --}}
<div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden">
    {{-- Section Header --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-[#e0e0e0]">
        <div class="w-[32px] h-[32px] bg-[#f5f5f5] rounded-lg flex items-center justify-center shrink-0 border border-[#e0e0e0]">
            <i data-lucide="building-2" class="w-[17px] h-[17px] text-[#314158]"></i>
        </div>
        <h2 class="font-bold text-[#1d293d] text-[14px] tracking-wider uppercase">Data Institusi</h2>
    </div>

    <div class="p-5 md:p-6 space-y-4">
        {{-- Nama Perguruan Tinggi --}}
        <div>
            <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Nama Perguruan Tinggi</label>
            <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center">
                <p class="text-[13px] font-medium text-[#45556c] truncate" x-text="profileData.institusi?.nama_institusi || '-'"></p>
            </div>
        </div>

        {{-- Jenis PT --}}
        <div>
            <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Jenis Perguruan Tinggi</label>
            <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center w-full md:w-1/2">
                <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.institusi?.jenis_institusi || '-'"></p>
            </div>
        </div>

        {{-- Jumlah Fakultas & Prodi --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Jumlah Fakultas</label>
                <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center gap-2">
                    <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.identitas?.jml_fakultas || '0'"></p>
                </div>
            </div>
            <div>
                <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">Jumlah Prodi</label>
                <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center gap-2">
                    <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.identitas?.jml_prodi || '0'"></p>
                </div>
            </div>
        </div>
    </div>
</div>
