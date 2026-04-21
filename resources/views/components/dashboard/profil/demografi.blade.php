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
        <div class="grid grid-cols-3 gap-4">
            {{-- ✏️ Ganti nilai masing-masing agama --}}
            @foreach([
                'Islam'    => '19845',
                'Kristen'  => '523',
                'Katolik'  => '287',
                'Hindu'    => '156',
                'Buddha'   => '134',
                'Konghucu' => '55',
            ] as $agama => $jumlah)
            <div>
                <label class="text-[12px] font-medium text-[#62748e] mb-1.5 block">{{ $agama }}</label>
                <div class="bg-[#fafafa] border border-[#e0e0e0] rounded px-4 h-[42px] flex items-center gap-2">
                    <i data-lucide="users" class="w-[14px] h-[14px] text-[#90a1b9] shrink-0"></i>
                    <p class="text-[13px] font-medium text-[#45556c]">{{ $jumlah }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
