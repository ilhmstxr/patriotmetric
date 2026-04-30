{{-- ===================================================== --}}
{{-- PROFIL SECTION: Data Demografi Agama Mahasiswa       --}}
{{-- ✏️ Ganti jumlah per agama di sini                    --}}
{{-- ===================================================== --}}
<div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden">
    <div class="flex items-center gap-3 px-5 py-4 border-b border-[#e0e0e0]">
        <div class="w-[32px] h-[32px] bg-[#f5f5f5] rounded-lg flex items-center justify-center shrink-0 border border-[#e0e0e0]">
            <i data-lucide="globe" class="w-[17px] h-[17px] text-[#314158]"></i>
        </div>
        <h2 class="font-bold text-[#1d293d] text-[14px] tracking-wider uppercase">Data Demografi Agama Mahasiswa</h2>
    </div>

    <div class="p-5 md:p-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {{-- ✏️ Nilai masing-masing agama --}}
            <template x-for="agama in ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Kepercayaan Terhadap Tuhan Yang Maha Esa']">
                <div>
                    <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block" x-text="agama"></label>
                    <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center gap-2">
                        <p class="text-[13px] font-medium text-[#45556c]" x-text="profileData.agamas[agama.toLowerCase()] || '0'"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
