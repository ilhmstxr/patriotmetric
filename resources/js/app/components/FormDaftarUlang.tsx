import { useState } from "react";
import { useNavigate } from "react-router";
import { FileUp, Image as ImageIcon, Send, FileText, CheckCircle2, ChevronRight, AlertCircle, UploadCloud } from "lucide-react";
import logoUrl from "figma:asset/b89aca8b9cc2d0494234bedd13382da054b48ab6.png";

export function FormDaftarUlang() {
  const navigate = useNavigate();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [activeSection, setActiveSection] = useState(1);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    // Simulate API call
    setTimeout(() => {
      setIsSubmitting(false);
      navigate("/dashboard");
    }, 1500);
  };

  const FileUploadField = ({ label, description, accept = ".pdf", templateLink, exampleName }: any) => {
    return (
      <div className="flex flex-col gap-[8px]">
        <label className="font-semibold text-[#1d293d] text-[14px]">
          {label} <span className="text-red-500">*</span>
        </label>
        {description && <p className="text-[#62748e] text-[13px] leading-relaxed">{description}</p>}
        {templateLink && (
          <a href={templateLink.url} target="_blank" rel="noopener noreferrer" className="text-[#1b5e20] text-[13px] font-medium hover:underline flex items-center gap-[4px] w-fit">
            <FileText className="w-[14px] h-[14px]" /> {templateLink.text}
          </a>
        )}
        <div className="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
          <input type="file" accept={accept} className="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
          <div className="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
            {accept.includes("image") ? <ImageIcon className="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#0ea5e9]" /> : <FileUp className="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]" />}
          </div>
          <p className="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
          <p className="text-[#64748b] text-[12px]">
            Maksimal 5MB dan Format {accept.includes("image") ? "Image (JPG/PNG)" : "PDF"}
          </p>
        </div>
        {exampleName && (
          <div className="flex items-start gap-[6px] mt-[2px] bg-amber-50 p-[8px] rounded-[6px] border border-amber-100">
            <AlertCircle className="w-[14px] h-[14px] text-amber-600 mt-[2px] shrink-0" />
            <p className="text-amber-800 text-[12px] leading-tight">
              <span className="font-semibold">Format nama file:</span> {exampleName.format}<br/>
              <span className="text-amber-600/80">Contoh: {exampleName.example}</span>
            </p>
          </div>
        )}
      </div>
    );
  };

  return (
    <div className="min-h-screen bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif] flex flex-col selection:bg-[#1b5e20] selection:text-white">
      {/* Header Form */}
      <header className="bg-white border-b border-[#e2e8f0] py-[20px] px-[24px] sticky top-0 z-20 shadow-sm">
        <div className="max-w-[800px] mx-auto flex items-center justify-between">
          <img src={logoUrl} alt="Logo" className="h-[40px] object-contain" />
          <div className="hidden md:flex items-center gap-[12px]">
            <div className={`flex items-center gap-[8px] ${activeSection === 1 ? 'text-[#1b5e20]' : 'text-[#94a3b8]'}`}>
              <div className={`w-[28px] h-[28px] rounded-full flex items-center justify-center font-bold text-[13px] border-2 ${activeSection === 1 ? 'border-[#1b5e20] bg-[#f2fcf3]' : 'border-[#e2e8f0]'}`}>1</div>
              <span className="font-semibold text-[14px]">Dokumen Legal</span>
            </div>
            <div className="w-[32px] h-[2px] bg-[#e2e8f0]"></div>
            <div className={`flex items-center gap-[8px] ${activeSection === 2 ? 'text-[#1b5e20]' : 'text-[#94a3b8]'}`}>
              <div className={`w-[28px] h-[28px] rounded-full flex items-center justify-center font-bold text-[13px] border-2 ${activeSection === 2 ? 'border-[#1b5e20] bg-[#f2fcf3]' : 'border-[#e2e8f0]'}`}>2</div>
              <span className="font-semibold text-[14px]">Profil Institusi</span>
            </div>
          </div>
        </div>
      </header>

      <main className="flex-1 py-[40px] px-[24px]">
        <div className="max-w-[800px] mx-auto">
          <div className="text-center mb-[40px]">
            <h1 className="text-[28px] md:text-[32px] font-bold text-[#1d293d] tracking-tight mb-[12px]">Form Daftar Ulang</h1>
            <p className="text-[#64748b] text-[15px] md:text-[16px] max-w-[600px] mx-auto">
              Lengkapi formulir di bawah ini untuk mengonfirmasi partisipasi institusi Anda dalam kegiatan 
              <strong className="text-[#1d293d] font-semibold"> Patriot Metric University Ranking 2025</strong>.
            </p>
          </div>

          <form onSubmit={handleSubmit} className="bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-[#e2e8f0] overflow-hidden">
            {/* Progress Bar Mobile */}
            <div className="md:hidden flex bg-[#f8fafc] border-b border-[#e2e8f0]">
              <button 
                type="button" 
                onClick={() => setActiveSection(1)} 
                className={`flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors ${activeSection === 1 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'}`}
              >
                1. Dokumen Legal
              </button>
              <button 
                type="button" 
                onClick={() => setActiveSection(2)} 
                className={`flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors ${activeSection === 2 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'}`}
              >
                2. Profil Institusi
              </button>
            </div>

            <div className="p-[32px] md:p-[48px]">
              {/* SECTION 1: Dokumen Legal */}
              {activeSection === 1 && (
                <div className="space-y-[32px] animate-in fade-in slide-in-from-bottom-4 duration-500">
                  <div className="pb-[16px] border-b border-[#e2e8f0] mb-[32px]">
                    <h2 className="text-[20px] font-bold text-[#1d293d]">Bagian 1: Dokumen Legalitas</h2>
                    <p className="text-[#64748b] text-[14px] mt-[4px]">Unggah berkas-berkas legalitas perguruan tinggi Anda.</p>
                  </div>

                  <FileUploadField 
                    label="1. Surat Pernyataan Resmi"
                    description="Unggah Surat Pernyataan resmi yang ditandatangani oleh pimpinan perguruan tinggi sebagai bentuk konfirmasi keikutsertaan."
                    templateLink={{ url: "https://bit.ly/TemplateSuratPernyataanUPNJatimPatriotMetric", text: "Unduh Template Surat Pernyataan UPN Jatim Patriot Metric" }}
                    exampleName={{ format: "SuratPernyataan_[NamaPerguruanTinggi]", example: "SuratPernyataan_UPNVeteranJatim" }}
                  />

                  <FileUploadField 
                    label="2. Surat Keputusan (SK) Pendirian Perguruan Tinggi"
                    exampleName={{ format: "SKPendirian_[NamaPerguruanTinggi]", example: "SKPendirian_UPNVeteranJatim" }}
                  />

                  <FileUploadField 
                    label="3. Surat Keputusan Akreditasi Institusi Perguruan Tinggi (AIPT)"
                    description="Unggah SK AIPT yang masih berlaku."
                    exampleName={{ format: "SKAkreditasi_[NamaPerguruanTinggi]", example: "SKAkreditasi_UPNVeteranJatim" }}
                  />

                  <div className="pt-[24px] mt-[16px] flex justify-end">
                    <button 
                      type="button" 
                      onClick={() => setActiveSection(2)}
                      className="bg-[#1b5e20] hover:bg-[#15461c] text-white px-[24px] py-[12px] rounded-[10px] font-semibold flex items-center gap-[8px] transition-all shadow-sm"
                    >
                      Lanjut ke Bagian 2 <ChevronRight className="w-[18px] h-[18px]" />
                    </button>
                  </div>
                </div>
              )}

              {/* SECTION 2: Profil Institusi */}
              {activeSection === 2 && (
                <div className="space-y-[32px] animate-in fade-in slide-in-from-bottom-4 duration-500">
                  <div className="pb-[16px] border-b border-[#e2e8f0] mb-[32px]">
                    <h2 className="text-[20px] font-bold text-[#1d293d]">Bagian 2: Profil & Informasi Institusi</h2>
                    <p className="text-[#64748b] text-[14px] mt-[4px]">Lengkapi data identitas dan statistik perguruan tinggi.</p>
                  </div>

                  <FileUploadField 
                    label="4. Profil Perguruan Tinggi"
                    description="Unggah profil Perguruan Tinggi Peserta Pemeringkatan UPN Jatim Patriot Metric."
                    templateLink={{ url: "https://docs.google.com/document/d/14qJSdTvFKcjrlzMAiE-aqOR72YpzNPAa/edit?usp=sharing&ouid=114349104875977587212&rtpof=true&sd=true", text: "Unduh Template pengisian profil Perguruan Tinggi" }}
                    exampleName={{ format: "Profil PT_[NamaPerguruanTinggi]", example: "Profil PT_UPNVeteranJatim" }}
                  />

                  <FileUploadField 
                    label="5. Logo Instansi"
                    accept="image/png, image/jpeg"
                  />

                  <div className="grid grid-cols-1 gap-[24px]">
                    <div className="flex flex-col gap-[8px]">
                      <label className="font-semibold text-[#1d293d] text-[14px]">6. Visi Perguruan Tinggi <span className="text-red-500">*</span></label>
                      <textarea required rows={4} className="w-full border border-[#cbd5e1] rounded-[10px] p-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[14px] resize-y placeholder:text-[#94a3b8]" placeholder="Tuliskan Visi Perguruan Tinggi..."></textarea>
                    </div>

                    <div className="flex flex-col gap-[8px]">
                      <label className="font-semibold text-[#1d293d] text-[14px]">7. Misi Perguruan Tinggi <span className="text-red-500">*</span></label>
                      <textarea required rows={4} className="w-full border border-[#cbd5e1] rounded-[10px] p-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[14px] resize-y placeholder:text-[#94a3b8]" placeholder="Tuliskan Misi Perguruan Tinggi..."></textarea>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                    <div className="flex flex-col gap-[8px]">
                      <label className="font-semibold text-[#1d293d] text-[14px]">8. Jumlah Dosen <span className="text-red-500">*</span></label>
                      <input type="number" required min="0" className="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 750" />
                    </div>

                    <div className="flex flex-col gap-[8px]">
                      <label className="font-semibold text-[#1d293d] text-[14px]">9. Jumlah Tenaga Akademik <span className="text-red-500">*</span></label>
                      <input type="number" required min="0" className="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 420" />
                    </div>

                    <div className="flex flex-col gap-[8px] md:col-span-2">
                      <label className="font-semibold text-[#1d293d] text-[14px]">10. Jumlah Mahasiswa <span className="text-red-500">*</span></label>
                      <input type="number" required min="0" className="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 21000" />
                    </div>

                    <div className="flex flex-col gap-[8px]">
                      <label className="font-semibold text-[#1d293d] text-[14px]">11. Jumlah Fakultas <span className="text-red-500">*</span></label>
                      <input type="number" required min="0" className="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 7" />
                    </div>

                    <div className="flex flex-col gap-[8px]">
                      <label className="font-semibold text-[#1d293d] text-[14px]">12. Jumlah Program Studi <span className="text-red-500">*</span></label>
                      <input type="number" required min="0" className="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 30" />
                    </div>
                  </div>

                  <FileUploadField 
                    label="13. Struktur Organisasi Perguruan Tinggi"
                    exampleName={{ format: "StrukturOrganisasi_[NamaPerguruanTinggi]", example: "StrukturOrganisasi_UPNVeteranJatim" }}
                  />

                  <FileUploadField 
                    label="14. SK Tim Pemeringkatan UPN Jatim Patriot Metric"
                    exampleName={{ format: "SKTimPatriotMetric_[NamaPerguruanTinggi]", example: "SKTimPatriotMetric_UPNVeteranJatim" }}
                  />

                  <div className="pt-[32px] mt-[16px] border-t border-[#e2e8f0] flex flex-col md:flex-row gap-[16px] items-center justify-between">
                    <button 
                      type="button" 
                      onClick={() => setActiveSection(1)}
                      className="w-full md:w-auto text-[#64748b] hover:text-[#1d293d] px-[24px] py-[12px] rounded-[10px] font-semibold transition-colors flex items-center justify-center"
                    >
                      Kembali
                    </button>
                    <button 
                      type="submit" 
                      disabled={isSubmitting}
                      className="w-full md:w-auto bg-[#1b5e20] hover:bg-[#15461c] text-white px-[32px] py-[14px] rounded-[10px] font-bold flex items-center justify-center gap-[10px] transition-all shadow-[0_4px_14px_rgba(27,94,32,0.3)] hover:shadow-[0_6px_20px_rgba(27,94,32,0.4)] disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                      {isSubmitting ? (
                        <>
                          <div className="w-[20px] h-[20px] border-3 border-white/30 border-t-white rounded-full animate-spin"></div>
                          Memproses...
                        </>
                      ) : (
                        <>
                          <Send className="w-[18px] h-[18px]" /> Submit Pendaftaran
                        </>
                      )}
                    </button>
                  </div>
                </div>
              )}
            </div>
          </form>
        </div>
      </main>
    </div>
  );
}