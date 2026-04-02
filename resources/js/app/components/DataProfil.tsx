import { useState } from "react";
import { 
  Building2, 
  Users, 
  School, 
  Lock, 
  Edit2, 
  Check, 
  X, 
  UserCircle2, 
  Phone, 
  Mail, 
  Briefcase,
  GraduationCap,
  LayoutGrid
} from "lucide-react";

export function DataProfil() {
  const [showPasswordModal, setShowPasswordModal] = useState(false);
  
  const [formData, setFormData] = useState({
    namaPt: "Universitas Pembangunan Nasional Veteran Jawa Timur",
    jenisPt: "PTN",
    jmlFakultas: "7",
    jmlProdi: "30",
    jmlDosen: "750",
    jmlTendik: "420",
    jmlMhs: "21000",
    namaPic: "Ilham Bintang Herlambang",
    jabatanPic: "Rektor",
    hpPic: "+621234567890",
    emailPic: "ilhamgaming117@upnjatim.ac.id"
  });

  const [passwordData, setPasswordData] = useState({
    oldPassword: "",
    newPassword: "",
    confirmPassword: ""
  });

  const handlePasswordSave = () => {
    setShowPasswordModal(false);
    setPasswordData({ oldPassword: "", newPassword: "", confirmPassword: "" });
  };

  const InputField = ({ label, icon: Icon, value, field, type = "text", colSpan = 1 }: any) => {
    return (
      <div className={`flex flex-col gap-[6px] ${colSpan === 2 ? 'col-span-1 md:col-span-2' : ''}`}>
        <label className="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">
          {label}
        </label>
        <div className="relative group">
          {Icon && (
            <div className="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
              <Icon strokeWidth={2} className="w-[18px] h-[18px]" />
            </div>
          )}
          <input 
            type={type}
            value={value}
            readOnly
            className={`w-full border rounded-[10px] h-[48px] ${Icon ? 'pl-[44px]' : 'pl-[16px]'} pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default`}
          />
        </div>
      </div>
    );
  };

  return (
    <div className="flex-1 overflow-y-auto bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif] relative flex flex-col h-full [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
      
      {/* Password Modal */}
      {showPasswordModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-[#1d293d]/60 backdrop-blur-sm p-[20px]">
          <div className="bg-white rounded-[16px] w-full max-w-[440px] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.1),0_10px_10px_-5px_rgba(0,0,0,0.04)] overflow-hidden transform transition-all">
            <div className="px-[24px] py-[20px] border-b border-[#e2e8f0] flex justify-between items-center bg-white">
              <div className="flex items-center gap-[12px]">
                <div className="w-[36px] h-[36px] bg-[#f2fcf3] rounded-full flex items-center justify-center text-[#1b5e20]">
                  <Lock className="w-[18px] h-[18px]" strokeWidth={2.5} />
                </div>
                <h3 className="font-bold text-[#1d293d] text-[18px]">Ganti Password</h3>
              </div>
              <button onClick={() => setShowPasswordModal(false)} className="text-[#90a1b9] hover:text-[#e7000b] hover:bg-[#fee2e2] p-[6px] rounded-full transition-colors">
                <X className="w-[20px] h-[20px]" />
              </button>
            </div>
            
            <div className="p-[24px] space-y-[20px]">
              <div className="flex flex-col gap-[6px]">
                <label className="font-medium text-[#45556c] text-[14px]">Password Lama</label>
                <div className="relative">
                  <div className="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <Lock className="w-[18px] h-[18px]" />
                  </div>
                  <input 
                    type="password" 
                    value={passwordData.oldPassword}
                    onChange={(e) => setPasswordData({...passwordData, oldPassword: e.target.value})}
                    className="w-full bg-white border border-[#cad5e2] rounded-[10px] h-[48px] pl-[44px] pr-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[#1d293d] text-[15px] transition-all"
                    placeholder="Masukkan password lama"
                  />
                </div>
              </div>
              <div className="flex flex-col gap-[6px]">
                <label className="font-medium text-[#45556c] text-[14px]">Password Baru</label>
                <div className="relative">
                  <div className="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <Lock className="w-[18px] h-[18px]" />
                  </div>
                  <input 
                    type="password" 
                    value={passwordData.newPassword}
                    onChange={(e) => setPasswordData({...passwordData, newPassword: e.target.value})}
                    className="w-full bg-white border border-[#cad5e2] rounded-[10px] h-[48px] pl-[44px] pr-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[#1d293d] text-[15px] transition-all"
                    placeholder="Masukkan password baru"
                  />
                </div>
              </div>
              <div className="flex flex-col gap-[6px]">
                <label className="font-medium text-[#45556c] text-[14px]">Konfirmasi Password Baru</label>
                <div className="relative">
                  <div className="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <Lock className="w-[18px] h-[18px]" />
                  </div>
                  <input 
                    type="password" 
                    value={passwordData.confirmPassword}
                    onChange={(e) => setPasswordData({...passwordData, confirmPassword: e.target.value})}
                    className="w-full bg-white border border-[#cad5e2] rounded-[10px] h-[48px] pl-[44px] pr-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[#1d293d] text-[15px] transition-all"
                    placeholder="Konfirmasi password baru"
                  />
                </div>
              </div>
            </div>
            
            <div className="px-[24px] py-[20px] border-t border-[#e2e8f0] bg-[#f8fafc] flex justify-end gap-[12px]">
              <button 
                onClick={() => setShowPasswordModal(false)}
                className="font-semibold text-[#62748e] text-[14px] px-[20px] py-[10px] bg-white border border-[#cad5e2] rounded-[8px] hover:bg-[#f1f5f9] hover:text-[#1d293d] transition-all shadow-sm"
              >
                Batal
              </button>
              <button 
                onClick={handlePasswordSave}
                className="font-semibold text-white bg-[#1b5e20] text-[14px] px-[20px] py-[10px] rounded-[8px] hover:bg-[#15461c] hover:shadow-md transition-all flex items-center gap-[8px]"
              >
                <Check className="w-[16px] h-[16px]" />
                Simpan Password
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Page Header */}
      <div className="bg-white border-b border-[#e2e8f0] px-[40px] py-[28px] flex items-center justify-between sticky top-0 z-10 shadow-sm">
        <div>
          <h1 className="font-bold text-[#1d293d] text-[26px] tracking-tight">Data Profil</h1>
          <p className="text-[#62748e] text-[15px] mt-[6px]">Kelola informasi institusi dan data penanggung jawab (PIC) Anda.</p>
        </div>
        
        <div className="flex items-center gap-[16px]">
          <button onClick={() => setShowPasswordModal(true)} className="flex items-center gap-[8px] font-semibold text-[#45556c] text-[14px] px-[18px] py-[10px] border border-[#cad5e2] rounded-[8px] hover:bg-white hover:text-[#1d293d] hover:border-[#90a1b9] hover:shadow-md transition-all bg-white shadow-sm">
            <Lock className="w-[16px] h-[16px]" /> Ganti Password
          </button>
        </div>
      </div>

      <div className="p-[40px] flex-1">
        <div className="max-w-[1000px] flex flex-col gap-[32px] mx-auto">
          
          {/* Data Institusi Card */}
          <div className="bg-white rounded-[16px] w-full border border-[#e2e8f0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05),0_2px_4px_-1px_rgba(0,0,0,0.03)] overflow-hidden transition-all hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.05),0_4px_6px_-2px_rgba(0,0,0,0.02)]">
            <div className="bg-gradient-to-r from-[#f8fafc] to-white px-[32px] py-[24px] border-b border-[#e2e8f0] flex items-center justify-between">
              <div className="flex items-center gap-[16px]">
                <div className="bg-white rounded-[10px] w-[48px] h-[48px] border border-[#e2e8f0] shadow-sm flex items-center justify-center">
                  <Building2 className="w-[24px] h-[24px] text-[#1b5e20]" />
                </div>
                <div>
                  <h2 className="font-bold text-[#1d293d] text-[18px]">Data Institusi</h2>
                  <p className="text-[#62748e] text-[13px] mt-[2px]">Informasi detail mengenai perguruan tinggi</p>
                </div>
              </div>
              <div className="hidden md:flex bg-[#f1f5f9] px-[12px] py-[6px] rounded-full items-center gap-[6px]">
                <span className="w-[8px] h-[8px] rounded-full bg-[#1b5e20]"></span>
                <span className="text-[12px] font-semibold text-[#45556c]">Terverifikasi</span>
              </div>
            </div>

            <div className="p-[32px] grid grid-cols-1 md:grid-cols-2 gap-x-[32px] gap-y-[24px]">
              <InputField label="Nama Perguruan Tinggi" icon={School} value={formData.namaPt} field="namaPt" colSpan={2} />
              <InputField label="Jenis Perguruan Tinggi" icon={Building2} value={formData.jenisPt} field="jenisPt" />
              <InputField label="Jumlah Mahasiswa Aktif" icon={Users} value={formData.jmlMhs} field="jmlMhs" type="number" />
              
              <div className="col-span-1 md:col-span-2 pt-[16px]">
                <div className="w-full h-[1px] bg-[#f1f5f9] mb-[24px]"></div>
                <h3 className="text-[15px] font-bold text-[#1d293d] mb-[20px] flex items-center gap-[8px]">
                  <LayoutGrid className="w-[18px] h-[18px] text-[#90a1b9]" /> Statistik Akademik
                </h3>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-[20px]">
                  <InputField label="Fakultas" icon={Building2} value={formData.jmlFakultas} field="jmlFakultas" type="number" />
                  <InputField label="Program Studi" icon={School} value={formData.jmlProdi} field="jmlProdi" type="number" />
                  <InputField label="Dosen" icon={GraduationCap} value={formData.jmlDosen} field="jmlDosen" type="number" />
                  <InputField label="Tenaga Kependidikan" icon={Briefcase} value={formData.jmlTendik} field="jmlTendik" type="number" />
                </div>
              </div>
            </div>
          </div>

          {/* Data Penanggung Jawab Card */}
          <div className="bg-white rounded-[16px] w-full border border-[#e2e8f0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05),0_2px_4px_-1px_rgba(0,0,0,0.03)] overflow-hidden transition-all hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.05),0_4px_6px_-2px_rgba(0,0,0,0.02)]">
            <div className="bg-gradient-to-r from-[#f8fafc] to-white px-[32px] py-[24px] border-b border-[#e2e8f0] flex items-center gap-[16px]">
              <div className="bg-white rounded-[10px] w-[48px] h-[48px] border border-[#e2e8f0] shadow-sm flex items-center justify-center">
                <UserCircle2 className="w-[24px] h-[24px] text-[#0ea5e9]" />
              </div>
              <div>
                <h2 className="font-bold text-[#1d293d] text-[18px]">Data Penanggung Jawab (PIC)</h2>
                <p className="text-[#62748e] text-[13px] mt-[2px]">Kontak utama yang dapat dihubungi</p>
              </div>
            </div>

            <div className="p-[32px] grid grid-cols-1 md:grid-cols-2 gap-x-[32px] gap-y-[24px]">
              <InputField label="Nama PIC Lengkap" icon={UserCircle2} value={formData.namaPic} field="namaPic" />
              <InputField label="Jabatan/Posisi" icon={Briefcase} value={formData.jabatanPic} field="jabatanPic" />
              <InputField label="Nomor HP / WhatsApp" icon={Phone} value={formData.hpPic} field="hpPic" type="tel" />
              <InputField label="Alamat Email" icon={Mail} value={formData.emailPic} field="emailPic" type="email" />
            </div>
          </div>
          
        </div>
      </div>
    </div>
  );
}