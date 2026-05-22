<x-layouts.dashboard>
    <x-slot:title>Panduan Pengguna</x-slot:title>

    <div class="flex-1 flex flex-col h-full bg-[#f8fafc]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="flex-1 overflow-y-auto p-[32px]">
            <div class="max-w-[920px] mx-auto space-y-[32px]">

                {{-- ✏️ Hero judul panduan → components/dashboard/panduan/hero.blade.php --}}
                <x-dashboard.panduan.hero />

                {{-- ===================================== --}}
                {{-- LANGKAH-LANGKAH PANDUAN               --}}
                {{-- ✏️ Tambah langkah baru dengan          --}}
                {{--    <x-dashboard.panduan.step number="4"> --}}
                {{--        <h3>Judul</h3>...               --}}
                {{--    </x-dashboard.panduan.step>          --}}
                {{-- ===================================== --}}
                <div class="space-y-[24px]">

                    {{-- Step 1: Pahami Indikator --}}
                    <x-dashboard.panduan.step number="1">
                        <h3 class="text-[20px] font-bold text-[#1d293d]">Pahami Indikator</h3>
                        <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                            Setiap pertanyaan terdiri dari <span class="font-bold text-[#1d293d]">Headline</span> (Nama Indikator) dan <span class="font-bold text-[#1d293d]">Deskripsi</span>. Bacalah dengan seksama untuk memastikan Anda memahami apa yang diminta oleh sistem sebelum memilih jawaban.
                        </p>
                    </x-dashboard.panduan.step>

                    {{-- Step 2: Pilih Jawaban --}}
                    <x-dashboard.panduan.step number="2">
                        <h3 class="text-[20px] font-bold text-[#1d293d]">Pilih Jawaban Sesuai Kondisi Riil</h3>
                        <p class="text-[#62748e] text-[15px] font-medium">Terdapat dua jenis pertanyaan:</p>
                        <div class="space-y-[16px]">
                            <div class="flex gap-[12px]">
                                <i data-lucide="check" class="w-[20px] h-[20px] text-[#1b5e20] shrink-0 mt-[2px]" stroke-width="2"></i>
                                <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                                    <span class="font-bold text-[#1d293d]">Pilihan Ganda:</span> Pilih opsi yang paling mendeskripsikan capaian institusi Anda. Opsi bernilai dari 0 (Tidak Ada) hingga 5 (Sangat Baik/Lengkap).
                                </p>
                            </div>
                            <div class="flex gap-[12px]">
                                <i data-lucide="check" class="w-[20px] h-[20px] text-[#1b5e20] shrink-0 mt-[2px]" stroke-width="2"></i>
                                <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                                    <span class="font-bold text-[#1d293d]">Isian Singkat:</span> Masukkan angka kuantitatif sesuai dengan data valid institusi Anda (contoh: Jumlah UKM, Jumlah Dosen Terlatih).
                                </p>
                            </div>
                        </div>
                    </x-dashboard.panduan.step>

                    {{-- Step 3: Unggah Bukti --}}
                    <x-dashboard.panduan.step number="3">
                        <h3 class="text-[20px] font-bold text-[#1d293d]">Unggah Bukti (Link)</h3>
                        <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                            Setiap klaim yang Anda masukkan <span class="font-bold text-[#1d293d]">wajib</span> disertai dengan bukti pendukung. Siapkan dokumen pendukung seperti SK, Peraturan Rektor, Dokumentasi Foto, atau Laporan Kegiatan, unggah ke Google Drive/Cloud institusi, dan masukkan <span class="font-bold text-[#1d293d]">Link URL</span> pada kolom yang disediakan.
                        </p>
                        <div class="bg-[#FFFBEB] border border-[#FDE68A] rounded-[8px] p-[20px] flex gap-[16px]">
                            <i data-lucide="alert-triangle" class="w-[24px] h-[24px] text-[#D97706] shrink-0" stroke-width="2"></i>
                            <p class="text-[#92400E] text-[14px] font-medium leading-[22px]">
                                Pastikan link yang Anda berikan <span class="font-bold">dapat diakses (Public / Anyone with the link)</span> agar tim Reviewer dapat membuka dan memvalidasi dokumen tersebut.
                            </p>
                        </div>
                    </x-dashboard.panduan.step>

                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
