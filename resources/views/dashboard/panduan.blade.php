<x-layouts.dashboard>
    <x-slot:title>PANDUAN PENGGUNA</x-slot:title>

    <div class="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
      {{-- Content Area --}}
      <div class="flex-1 overflow-y-auto p-[32px]">
        <div class="max-w-[920px] space-y-[32px]">
          {{-- Hero / Title Section --}}
          <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[40px] text-center shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
            <h2 class="text-[28px] font-bold text-[#1d293d] tracking-[0.5px]">
              Panduan Pengisian Rubrik
            </h2>
            <p class="text-[#62748e] text-[16px] font-medium mt-[12px]">
              Langkah-langkah dan ketentuan dalam mengisi form Patriot Metric.
            </p>
          </div>

          {{-- Steps --}}
          <div class="space-y-[24px]">
            {{-- Step 1 --}}
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex flex-col md:flex-row gap-[24px]">
              <div class="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
                <span class="text-[24px] font-bold text-[#1b5e20]">1</span>
              </div>
              <div class="space-y-[12px] pt-[4px]">
                <h3 class="text-[20px] font-bold text-[#1d293d]">Pahami Indikator</h3>
                <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                  Setiap pertanyaan terdiri dari <span class="font-bold text-[#1d293d]">Headline</span> (Nama Indikator) dan <span class="font-bold text-[#1d293d]">Deskripsi</span>. Bacalah dengan seksama untuk memastikan Anda memahami apa yang diminta oleh sistem sebelum memilih jawaban.
                </p>
              </div>
            </div>

            {{-- Step 2 --}}
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex flex-col md:flex-row gap-[24px]">
              <div class="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
                <span class="text-[24px] font-bold text-[#1b5e20]">2</span>
              </div>
              <div class="space-y-[16px] pt-[4px] w-full">
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
              </div>
            </div>

            {{-- Step 3 --}}
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex flex-col md:flex-row gap-[24px]">
              <div class="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
                <span class="text-[24px] font-bold text-[#1b5e20]">3</span>
              </div>
              <div class="space-y-[16px] pt-[4px] w-full">
                <h3 class="text-[20px] font-bold text-[#1d293d]">Unggah Bukti (Link)</h3>
                <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                  Setiap klaim yang Anda masukkan <span class="font-bold text-[#1d293d]">wajib</span> disertai dengan bukti pendukung. Siapkan dokumen pendukung seperti SK, Peraturan Rektor, Dokumentasi Foto, atau Laporan Kegiatan, unggah ke Google Drive/Cloud institusi, dan masukkan <span class="font-bold text-[#1d293d]">Link URL</span> pada kolom yang disediakan.
                </p>

                <div class="bg-[#FFFBEB] border border-[#FDE68A] rounded-[8px] p-[20px] flex gap-[16px]">
                  <i data-lucide="alert-triangle" class="w-[24px] h-[24px] text-[#D97706] shrink-0" stroke-width="2"></i>
                  <p class="text-[#92400E] text-[14px] font-medium leading-[22px]">
                    Pastikan link yang Anda berikan <span class="font-bold">dapat diakses (Public / Anyone with the link)</span> agar tim Reviewer/Asesor dapat membuka dan memvalidasi dokumen tersebut.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-layouts.dashboard>
