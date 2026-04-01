import { useState } from "react";

// Mock database for Rubric Questions
const mockDatabase = [
  {
    category: "KEBIJAKAN (01)",
    weight: "25%",
    questions: [
      {
        id: "kebijakan_1",
        code: "01",
        title: "Kebijakan/implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma",
        description: "Berdasarkan pedoman dan/atau implementasi terdapat unsur bela negara di tingkat akademik.",
        evidenceRequirements: [
          "1. Dokumen Kebijakan berupa SK",
          "2. Bukti implementasi (disertasi, tesis, penelitian, atau pengabdian)"
        ],
        type: "multiple-choice",
        options: [
          "Tidak Ada",
          "Ada kebijakan namun tetap belum diimplementasikan",
          "Dibutuhkan dan diimplementasikan dalam mana kegiatan dari Tridharma",
          "Dibutuhkan dan diimplementasikan dalam dua kegiatan dari Tridharma",
          "Dibutuhkan dan diimplementasikan dalam seluruh kegiatan Tridharma serta kegiatan penunjang",
          "Dibutuhkan dan diimplementasikan dalam seluruh kegiatan Tridharma dan kegiatan penunjang"
        ]
      },
      {
        id: "kebijakan_2",
        code: "02",
        title: "Kebijakan pencegahan dan penanganan kekerasan",
        description: "Meliputi kelengkapan instrumen pencegahan dan penanganan kekerasan di kampus.",
        evidenceRequirements: [
          "1. Dokumen Kebijakan / Pedoman",
          "2. SK Satgas PPKS",
          "3. Dokumentasi Sosialisasi",
          "4. Laporan/jurnal tindak lanjut"
        ],
        type: "multiple-choice",
        options: [
          "Tidak Ada",
          "Ada kebijakan, pedoman pencegahan dan penanganan kekerasan tetapi belum diimplementasikan",
          "Ada kebijakan, pedoman, sosialisasi, langkah pencegahan dan penanganan kekerasan",
          "Ada kebijakan, pedoman, sosialisasi, Satgas Pencegahan dan Penanganan Kekerasan",
          "Ada kebijakan, pedoman, sosialisasi, Satgas, jurnal pelaporan/tindak lanjut",
          "Lengkap beserta tindak lanjut laporan (termasuk pendampingan, perlindungan, pemulihan korban dan sanksi)"
        ]
      },
      {
        id: "kebijakan_3",
        code: "03",
        title: "Kebijakan bagi sivitas akademika untuk bangga menggunakan produk lokal dalam pelaksanaan pembelajaran",
        description: "Contoh: penggunaan Batik, pakaian adat.",
        evidenceRequirements: [
          "1. Dokumen Edaran Rektor/Dekan",
          "2. Foto implementasi di lingkungan kampus"
        ],
        type: "multiple-choice",
        options: [
          "Tidak Ada",
          "Kebijakan ada/tidak tidak diterapkan dalam praktik.",
          "Kebijakan ada, tetapi penerapannya tidak konsisten dan tidak diawasi/dikendalikan",
          "Kebijakan ada dan diterapkan tetapi hanya dalam beberapa bagian/karakteristik tertentu",
          "Kebijakan penggunaan produk lokal diterapkan luas, tetapi belum sepenuhnya didukung oleh seluruh sivitas akademik",
          "Kebijakan penggunaan produk lokal diterapkan di terapkan secara konsisten dan mendapat dukungan penuh"
        ]
      }
    ]
  },
  {
    category: "KELEMBAGAAN (02)",
    weight: "25%",
    questions: [
      {
        id: "kelembagaan_1",
        code: "01",
        title: "Unit kerja yang berfokus pada pengembangan karakter bela negara",
        description: "Unit khusus yang berdedikasi membangun karakter kepemimpinan dan patriotisme sivitas.",
        evidenceRequirements: [
          "1. SK Pembentukan Unit Kerja",
          "2. Dokumen Program Kerja",
          "3. Laporan Pelaksanaan Program"
        ],
        type: "multiple-choice",
        options: [
          "Tidak Ada",
          "Ada unit kerja",
          "Ada unit kerja, program kerja",
          "Ada unit kerja, program kerja, kegiatan implementasi program kerja",
          "Ada unit kerja, program kerja, kegiatan implementasi, evaluasi program",
          "Ada unit kerja, program kerja, kegiatan implementasi, evaluasi, perencanaan tahun berikutnya"
        ]
      },
      {
        id: "kelembagaan_2",
        code: "02",
        title: "Jumlah kelompok riset berkarakter bela negara",
        description: "Mendata total persentase proporsi dosen/mahasiswa dengan tema terkait bela negara.",
        evidenceRequirements: [
          "1. Daftar Kelompok Riset / PkM",
          "2. Publikasi Hasil Jurnal Terkait"
        ],
        type: "short-answer",
        options: []
      }
    ]
  },
  {
    category: "PATRIOTISME MAHASISWA (03)",
    weight: "10%",
    questions: [
      {
        id: "patriotisme_1",
        code: "01",
        title: "Mahasiswa aktif sebagai anggota komponen cadangan (KOMCAD)",
        description: "Jumlah mahasiswa aktif yang terdaftar secara resmi sebagai KOMCAD sampai masa TS.",
        evidenceRequirements: [
          "1. Daftar Mahasiswa KOMCAD",
          "2. Sertifikat/Kartu Anggota KOMCAD"
        ],
        type: "multiple-choice",
        options: [
          "Tidak Ada",
          "1 mahasiswa aktif",
          "2 mahasiswa aktif",
          "3 mahasiswa aktif",
          "4 mahasiswa aktif",
          "> 4 mahasiswa aktif"
        ]
      },
      {
        id: "patriotisme_2",
        code: "02",
        title: "Jumlah Unit Kegiatan Mahasiswa (UKM)",
        description: "Jumlah total UKM resmi yang aktif beroperasi di perguruan tinggi.",
        evidenceRequirements: [
          "1. SK Rektor/Direktur tentang UKM",
          "2. Laporan Kegiatan Tahunan UKM"
        ],
        type: "multiple-choice",
        options: [
          "Tidak Ada",
          "Terdapat 1 - 5 UKM",
          "Terdapat 6 - 10 UKM",
          "Terdapat 11 - 15 UKM",
          "Terdapat 16 - 20 UKM",
          "Terdapat > 20 UKM"
        ]
      }
    ]
  }
];

export function FormRubrik() {
  // State to hold answers (id -> selected option or string)
  const [answers, setAnswers] = useState<Record<string, string>>({});
  const [links, setLinks] = useState<Record<string, string>>({});

  const handleSelectOption = (questionId: string, option: string) => {
    setAnswers(prev => ({ ...prev, [questionId]: option }));
  };

  const handleLinkChange = (questionId: string, value: string) => {
    setLinks(prev => ({ ...prev, [questionId]: value }));
  };

  return (
    <div className="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
      {/* Content Area */}
      <div className="flex-1 overflow-y-auto p-[32px] relative">
        <div className="space-y-[32px] max-w-[920px]">
          {mockDatabase.map((categoryData, cIdx) => (
            <div key={cIdx} className="space-y-[16px]">
              {/* Category Header */}
              <div className="flex items-center justify-between border-b-[2px] border-[#e2e8f0] pb-[8px] mb-[16px]">
                <h2 className="text-[18px] font-bold text-[#1d293d] uppercase">{categoryData.category}</h2>
                <span className="text-[14px] font-semibold text-[#62748e] bg-white border border-[#e2e8f0] px-[12px] py-[4px] rounded-full shadow-sm">
                  Bobot: {categoryData.weight}
                </span>
              </div>

              {/* Questions */}
              {categoryData.questions.map((q) => (
                <div key={q.id} className="bg-white border border-[#e2e8f0] rounded-[10px] p-[24px] flex gap-[24px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1),0px_1px_2px_-1px_rgba(0,0,0,0.1)]">
                  
                  {/* Left Column: Question & Evidence */}
                  <div className="flex-[1.2] space-y-[16px]">
                    <div className="flex gap-[16px]">
                      <div className="w-[40px] h-[40px] rounded-[8px] bg-[#f8fafc] border border-[#e2e8f0] flex items-center justify-center font-bold text-[#1d293d] text-[16px] shrink-0">
                        {q.code}
                      </div>
                      <div>
                        <h3 className="font-bold text-[#1d293d] text-[15px] leading-[22px]">{q.title}</h3>
                        <p className="text-[13px] font-medium text-[#62748e] mt-[6px] leading-[20px]">{q.description}</p>
                      </div>
                    </div>

                    <div className="bg-[#f8fafc] border border-[#e2e8f0] rounded-[8px] p-[16px] ml-[56px]">
                      <h4 className="text-[12px] font-bold text-[#45556c] mb-[8px] uppercase tracking-[0.4px]">Syarat Bukti:</h4>
                      <ul className="text-[13px] font-medium text-[#62748e] space-y-[6px]">
                        {q.evidenceRequirements.map((req, rIdx) => (
                          <li key={rIdx} className="flex gap-[6px] items-start">
                            <span className="mt-[2px] w-[4px] h-[4px] bg-[#90A1B9] rounded-full shrink-0"></span>
                            <span className="leading-[18px]">{req.replace(/^\d+\.\s*/, '')}</span>
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>

                  {/* Right Column: Answers & Link */}
                  <div className="flex-[1.8] flex flex-col space-y-[16px] border-l border-[#e2e8f0] pl-[24px]">
                    <h4 className="text-[12px] font-bold text-[#45556c] uppercase tracking-[0.4px]">Jawaban:</h4>
                    
                    {q.type === "multiple-choice" ? (
                      <div className="space-y-[10px]">
                        {q.options.map((opt, oIdx) => {
                          const isSelected = answers[q.id] === opt;
                          return (
                            <button
                              key={oIdx}
                              onClick={() => handleSelectOption(q.id, opt)}
                              className={`w-full text-left px-[16px] py-[12px] rounded-[8px] border text-[14px] leading-[20px] transition-colors ${
                                isSelected
                                  ? "bg-[rgba(27,94,32,0.05)] border-[#1b5e20] text-[#1b5e20] font-semibold"
                                  : "bg-white border-[#cad5e2] text-[#62748e] font-medium hover:border-[#1b5e20] hover:bg-[rgba(27,94,32,0.02)]"
                              }`}
                            >
                              {opt}
                            </button>
                          );
                        })}
                      </div>
                    ) : (
                      <div className="space-y-[8px]">
                        <input
                          type="text"
                          placeholder="Masukkan jawaban..."
                          className="w-full px-[16px] py-[12px] rounded-[8px] border border-[#cad5e2] text-[14px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] bg-white placeholder-[#90A1B9]"
                          value={answers[q.id] || ""}
                          onChange={(e) => handleSelectOption(q.id, e.target.value)}
                        />
                      </div>
                    )}

                    <div className="pt-[8px] mt-auto">
                      <h4 className="text-[12px] font-bold text-[#45556c] uppercase tracking-[0.4px] flex items-center gap-[6px] mb-[8px]">
                        <svg className="w-[14px] h-[14px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        Tautan BUKTI / Dokumen
                      </h4>
                      <input
                        type="url"
                        placeholder="https://drive.google.com/..."
                        className="w-full px-[16px] py-[12px] rounded-[8px] border border-[#cad5e2] text-[14px] font-medium focus:outline-none focus:border-[#1b5e20] bg-[#f8fafc] text-[#1d293d] placeholder-[#90A1B9]"
                        value={links[q.id] || ""}
                        onChange={(e) => handleLinkChange(q.id, e.target.value)}
                      />
                      <p className="text-[11px] font-medium text-[#90A1B9] mt-[6px]">
                        * Pastikan tautan dapat diakses publik (Anyone with the link).
                      </p>
                    </div>
                  </div>

                </div>
              ))}
            </div>
          ))}
        </div>
      </div>

      {/* Footer sticky area */}
      <div className="bg-white border-t border-[#e2e8f0] px-[32px] py-[16px] flex justify-between items-center shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div className="flex items-center gap-[8px] text-[14px] font-medium text-[#62748e]">
          <span className="w-[8px] h-[8px] rounded-full bg-[#cad5e2]"></span>
          Data formulir disimpan secara lokal di peramban Anda.
        </div>
        <button className="bg-[#1b5e20] hover:bg-[#15461c] text-white px-[24px] h-[44px] rounded-[8px] text-[14px] font-semibold flex items-center gap-[8px] transition-colors shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
          <svg className="w-[16px] h-[16px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
          </svg>
          Simpan Draft
        </button>
      </div>
    </div>
  );
}
