<x-layouts.reviewer>
    <x-slot:title>PANDUAN REVIEWER</x-slot:title>

    <div class="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
      {{-- Content Area --}}
      <div class="flex-1 overflow-y-auto p-[32px]">
        <div class="max-w-[920px] space-y-[32px] mx-auto">
          {{-- Hero / Title Section --}}
          <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[40px] text-center shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
            <h2 class="text-[28px] font-bold text-[#1d293d] tracking-[0.5px]">
              Panduan Penilaian Reviewer
            </h2>
            <p class="text-[#62748e] text-[16px] font-medium mt-[12px]">
              Langkah-langkah dan ketentuan dalam memberikan penilaian form Patriot Metric.
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
                <h3 class="text-[20px] font-bold text-[#1d293d]">Pilih Institusi dari Daftar Plotting</h3>
                <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                  Buka menu <span class="font-bold text-[#1d293d]">Dashboard Utama</span> dan lihat daftar Institusi/Submitter yang telah di-plotting kepada Anda. Klik tombol <span class="font-bold text-[#1d293d]">Lihat Detail</span> atau <span class="font-bold text-[#1d293d]">Nilai Sekarang</span> untuk mulai memeriksa isian rubrik dari submitter terkait.
                </p>
              </div>
            </div>

            {{-- Step 2 --}}
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex flex-col md:flex-row gap-[24px]">
              <div class="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
                <span class="text-[24px] font-bold text-[#1b5e20]">2</span>
              </div>
              <div class="space-y-[16px] pt-[4px] w-full">
                <h3 class="text-[20px] font-bold text-[#1d293d]">Verifikasi Kesesuaian Jawaban dan Bukti</h3>
                <p class="text-[#62748e] text-[15px] font-medium">Bandingkan isian dengan bukti pendukung:</p>
                <div class="space-y-[16px]">
                  <div class="flex gap-[12px]">
                    <i data-lucide="check" class="w-[20px] h-[20px] text-[#1b5e20] shrink-0 mt-[2px]" stroke-width="2"></i>
                    <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                      <span class="font-bold text-[#1d293d]">Buka Link Dokumen:</span> Klik tautan URL yang diberikan oleh submiter. Pastikan dokumen relevan dan sah.
                    </p>
                  </div>
                  <div class="flex gap-[12px]">
                    <i data-lucide="check" class="w-[20px] h-[20px] text-[#1b5e20] shrink-0 mt-[2px]" stroke-width="2"></i>
                    <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                      <span class="font-bold text-[#1d293d]">Cek Relevansi Pilihan Ganda/Isian:</span> Evaluasi apakah opsi jawaban yang dipilih submiter sesuai secara logika dan tertulis dalam dokumen bukti. Jika berlebihan (overclaim), Reviewer berhak menurunkan nilai.
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
                <h3 class="text-[20px] font-bold text-[#1d293d]">Berikan Penilaian Akhir (Skoring)</h3>
                <p class="text-[#62748e] text-[15px] font-medium leading-[24px]">
                  Berdasarkan validasi Anda, tentukan skor final untuk setiap indikator. Setelah semua indikator dinilai secara keseluruhan, sistem akan secara otomatis menghitung akumulasi total nilai untuk Institusi tersebut pada bagian <span class="font-bold text-[#1d293d]">Hasil Penilaian</span>.
                </p>

                <div class="bg-[#FFFBEB] border border-[#FDE68A] rounded-[8px] p-[20px] flex gap-[16px]">
                  <i data-lucide="alert-triangle" class="w-[24px] h-[24px] text-[#D97706] shrink-0" stroke-width="2"></i>
                  <p class="text-[#92400E] text-[14px] font-medium leading-[22px]">
                    Hati-hati, nilai yang sudah Anda Finalisasi dan Submit <span class="font-bold">tidak dapat diubah kembali</span> tanpa izin Administrator. Pastikan mengecek dua kali!
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-layouts.reviewer>
