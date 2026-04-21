export function PanduanPengguna() {
  return (
    <div className="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
      {/* Content Area */}
      <div className="flex-1 overflow-y-auto p-[32px]">
        <div className="max-w-[920px] space-y-[32px]">
          {/* Hero / Title Section */}
          <div className="bg-white border border-[#e2e8f0] rounded-[10px] p-[40px] text-center shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
            <h2 className="text-[28px] font-bold text-[#1d293d] tracking-[0.5px]">
              Panduan Pengisian Rubrik
            </h2>
            <p className="text-[#62748e] text-[16px] font-medium mt-[12px]">
              Langkah-langkah dan ketentuan dalam mengisi form Patriot Metric.
            </p>
          </div>

          {/* Steps */}
          <div className="space-y-[24px]">
            {/* Step 1 */}
            <div className="bg-white border border-[#e2e8f0] rounded-[10px] p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex gap-[24px]">
              <div className="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
                <span className="text-[24px] font-bold text-[#1b5e20]">1</span>
              </div>
              <div className="space-y-[12px] pt-[4px]">
                <h3 className="text-[20px] font-bold text-[#1d293d]">Pahami Indikator</h3>
                <p className="text-[#62748e] text-[15px] font-medium leading-[24px]">
                  Setiap pertanyaan terdiri dari <span className="font-bold text-[#1d293d]">Headline</span> (Nama Indikator) dan <span className="font-bold text-[#1d293d]">Deskripsi</span>. Bacalah dengan seksama untuk memastikan Anda memahami apa yang diminta oleh sistem sebelum memilih jawaban.
                </p>
              </div>
            </div>

            {/* Step 2 */}
            <div className="bg-white border border-[#e2e8f0] rounded-[10px] p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex gap-[24px]">
              <div className="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
                <span className="text-[24px] font-bold text-[#1b5e20]">2</span>
              </div>
              <div className="space-y-[16px] pt-[4px] w-full">
                <h3 className="text-[20px] font-bold text-[#1d293d]">Pilih Jawaban Sesuai Kondisi Riil</h3>
                <p className="text-[#62748e] text-[15px] font-medium">Terdapat dua jenis pertanyaan:</p>
                <div className="space-y-[16px]">
                  <div className="flex gap-[12px]">
                    <svg className="w-[20px] h-[20px] text-[#1b5e20] shrink-0 mt-[2px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p className="text-[#62748e] text-[15px] font-medium leading-[24px]">
                      <span className="font-bold text-[#1d293d]">Pilihan Ganda:</span> Pilih opsi yang paling mendeskripsikan capaian institusi Anda. Opsi bernilai dari 0 (Tidak Ada) hingga 5 (Sangat Baik/Lengkap).
                    </p>
                  </div>
                  <div className="flex gap-[12px]">
                    <svg className="w-[20px] h-[20px] text-[#1b5e20] shrink-0 mt-[2px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p className="text-[#62748e] text-[15px] font-medium leading-[24px]">
                      <span className="font-bold text-[#1d293d]">Isian Singkat:</span> Masukkan angka kuantitatif sesuai dengan data valid institusi Anda (contoh: Jumlah UKM, Jumlah Dosen Terlatih).
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* Step 3 */}
            <div className="bg-white border border-[#e2e8f0] rounded-[10px] p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex gap-[24px]">
              <div className="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
                <span className="text-[24px] font-bold text-[#1b5e20]">3</span>
              </div>
              <div className="space-y-[16px] pt-[4px] w-full">
                <h3 className="text-[20px] font-bold text-[#1d293d]">Unggah Bukti (Link)</h3>
                <p className="text-[#62748e] text-[15px] font-medium leading-[24px]">
                  Setiap klaim yang Anda masukkan <span className="font-bold text-[#1d293d]">wajib</span> disertai dengan bukti pendukung. Siapkan dokumen pendukung seperti SK, Peraturan Rektor, Dokumentasi Foto, atau Laporan Kegiatan, unggah ke Google Drive/Cloud institusi, dan masukkan <span className="font-bold text-[#1d293d]">Link URL</span> pada kolom yang disediakan.
                </p>

                <div className="bg-[#FFFBEB] border border-[#FDE68A] rounded-[8px] p-[20px] flex gap-[16px]">
                  <svg className="w-[24px] h-[24px] text-[#D97706] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <p className="text-[#92400E] text-[14px] font-medium leading-[22px]">
                    Pastikan link yang Anda berikan <span className="font-bold">dapat diakses (Public / Anyone with the link)</span> agar tim Reviewer dapat membuka dan memvalidasi dokumen tersebut.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
