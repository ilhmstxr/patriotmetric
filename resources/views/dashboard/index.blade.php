<x-layouts.dashboard>
    <x-slot:title>DATA PROFIL</x-slot:title>

    <div x-data="{
        showPasswordModal: false,
        passwordData: { oldPassword: '', newPassword: '', confirmPassword: '' },
        handlePasswordSave() {
            this.showPasswordModal = false;
            this.passwordData = { oldPassword: '', newPassword: '', confirmPassword: '' };
        }
    }" class="flex-1 overflow-y-auto bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif] relative flex flex-col h-full [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
      
      {{-- Password Modal --}}
      <div x-show="showPasswordModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-[#1d293d]/60 backdrop-blur-sm p-[20px]">
        <div @click.away="showPasswordModal = false" class="bg-white rounded-[16px] w-full max-w-[440px] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.1),0_10px_10px_-5px_rgba(0,0,0,0.04)] overflow-hidden transform transition-all">
          <div class="px-[24px] py-[20px] border-b border-[#e2e8f0] flex justify-between items-center bg-white">
            <div class="flex items-center gap-[12px]">
              <div class="w-[36px] h-[36px] bg-[#f2fcf3] rounded-full flex items-center justify-center text-[#1b5e20]">
                <i data-lucide="lock" class="w-[18px] h-[18px]" stroke-width="2.5"></i>
              </div>
              <h3 class="font-bold text-[#1d293d] text-[18px]">Ganti Password</h3>
            </div>
            <button @click="showPasswordModal = false" class="text-[#90a1b9] hover:text-[#e7000b] hover:bg-[#fee2e2] p-[6px] rounded-full transition-colors">
              <i data-lucide="x" class="w-[20px] h-[20px]"></i>
            </button>
          </div>
          
          <div class="p-[24px] space-y-[20px]">
            <div class="flex flex-col gap-[6px]">
              <label class="font-medium text-[#45556c] text-[14px]">Password Lama</label>
              <div class="relative">
                <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                  <i data-lucide="lock" class="w-[18px] h-[18px]"></i>
                </div>
                <input 
                  type="password" 
                  x-model="passwordData.oldPassword"
                  class="w-full bg-white border border-[#cad5e2] rounded-[10px] h-[48px] pl-[44px] pr-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[#1d293d] text-[15px] transition-all"
                  placeholder="Masukkan password lama"
                />
              </div>
            </div>
            <div class="flex flex-col gap-[6px]">
              <label class="font-medium text-[#45556c] text-[14px]">Password Baru</label>
              <div class="relative">
                <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                  <i data-lucide="lock" class="w-[18px] h-[18px]"></i>
                </div>
                <input 
                  type="password" 
                  x-model="passwordData.newPassword"
                  class="w-full bg-white border border-[#cad5e2] rounded-[10px] h-[48px] pl-[44px] pr-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[#1d293d] text-[15px] transition-all"
                  placeholder="Masukkan password baru"
                />
              </div>
            </div>
            <div class="flex flex-col gap-[6px]">
              <label class="font-medium text-[#45556c] text-[14px]">Konfirmasi Password Baru</label>
              <div class="relative">
                <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                  <i data-lucide="lock" class="w-[18px] h-[18px]"></i>
                </div>
                <input 
                  type="password" 
                  x-model="passwordData.confirmPassword"
                  class="w-full bg-white border border-[#cad5e2] rounded-[10px] h-[48px] pl-[44px] pr-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[#1d293d] text-[15px] transition-all"
                  placeholder="Konfirmasi password baru"
                />
              </div>
            </div>
          </div>
          
          <div class="px-[24px] py-[20px] border-t border-[#e2e8f0] bg-[#f8fafc] flex justify-end gap-[12px]">
            <button 
              @click="showPasswordModal = false"
              class="font-semibold text-[#62748e] text-[14px] px-[20px] py-[10px] bg-white border border-[#cad5e2] rounded-[8px] hover:bg-[#f1f5f9] hover:text-[#1d293d] transition-all shadow-sm"
            >
              Batal
            </button>
            <button 
              @click="handlePasswordSave()"
              class="font-semibold text-white bg-[#1b5e20] text-[14px] px-[20px] py-[10px] rounded-[8px] hover:bg-[#15461c] hover:shadow-md transition-all flex items-center gap-[8px]"
            >
              <i data-lucide="check" class="w-[16px] h-[16px]"></i>
              Simpan Password
            </button>
          </div>
        </div>
      </div>

      {{-- Page Header --}}
      <div class="bg-white border-b border-[#e2e8f0] px-[40px] py-[28px] flex items-center justify-between sticky top-0 z-10 shadow-sm">
        <div>
          <h1 class="font-bold text-[#1d293d] text-[26px] tracking-tight">Data Profil</h1>
          <p class="text-[#62748e] text-[15px] mt-[6px]">Kelola informasi institusi dan data penanggung jawab (PIC) Anda.</p>
        </div>
        
        <div class="flex items-center gap-[16px]">
          <button @click="showPasswordModal = true" class="flex items-center gap-[8px] font-semibold text-[#45556c] text-[14px] px-[18px] py-[10px] border border-[#cad5e2] rounded-[8px] hover:bg-white hover:text-[#1d293d] hover:border-[#90a1b9] hover:shadow-md transition-all bg-white shadow-sm">
            <i data-lucide="lock" class="w-[16px] h-[16px]"></i> Ganti Password
          </button>
        </div>
      </div>

      <div class="p-[40px] flex-1">
        <div class="max-w-[1000px] flex flex-col gap-[32px] mx-auto">
          
          {{-- Data Institusi Card --}}
          <div class="bg-white rounded-[16px] w-full border border-[#e2e8f0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05),0_2px_4px_-1px_rgba(0,0,0,0.03)] overflow-hidden transition-all hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.05),0_4px_6px_-2px_rgba(0,0,0,0.02)]">
            <div class="bg-gradient-to-r from-[#f8fafc] to-white px-[32px] py-[24px] border-b border-[#e2e8f0] flex items-center justify-between">
              <div class="flex items-center gap-[16px]">
                <div class="bg-white rounded-[10px] w-[48px] h-[48px] border border-[#e2e8f0] shadow-sm flex items-center justify-center">
                  <i data-lucide="building-2" class="w-[24px] h-[24px] text-[#1b5e20]"></i>
                </div>
                <div>
                  <h2 class="font-bold text-[#1d293d] text-[18px]">Data Institusi</h2>
                  <p class="text-[#62748e] text-[13px] mt-[2px]">Informasi detail mengenai perguruan tinggi</p>
                </div>
              </div>
              <div class="hidden md:flex bg-[#f1f5f9] px-[12px] py-[6px] rounded-full items-center gap-[6px]">
                <span class="w-[8px] h-[8px] rounded-full bg-[#1b5e20]"></span>
                <span class="text-[12px] font-semibold text-[#45556c]">Terverifikasi</span>
              </div>
            </div>

            <div class="p-[32px] grid grid-cols-1 md:grid-cols-2 gap-x-[32px] gap-y-[24px]">
              <div class="flex flex-col gap-[6px] md:col-span-2">
                <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Nama Perguruan Tinggi</label>
                <div class="relative group">
                  <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <i data-lucide="school" stroke-width="2" class="w-[18px] h-[18px]"></i>
                  </div>
                  <input type="text" value="Universitas Pembangunan Nasional Veteran Jawa Timur" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                </div>
              </div>
              <div class="flex flex-col gap-[6px]">
                <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Jenis Perguruan Tinggi</label>
                <div class="relative group">
                  <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <i data-lucide="building-2" stroke-width="2" class="w-[18px] h-[18px]"></i>
                  </div>
                  <input type="text" value="PTN" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                </div>
              </div>
              <div class="flex flex-col gap-[6px]">
                <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Jumlah Mahasiswa Aktif</label>
                <div class="relative group">
                  <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <i data-lucide="users" stroke-width="2" class="w-[18px] h-[18px]"></i>
                  </div>
                  <input type="number" value="21000" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                </div>
              </div>
              
              <div class="col-span-1 md:col-span-2 pt-[16px]">
                <div class="w-full h-[1px] bg-[#f1f5f9] mb-[24px]"></div>
                <h3 class="text-[15px] font-bold text-[#1d293d] mb-[20px] flex items-center gap-[8px]">
                  <i data-lucide="layout-grid" class="w-[18px] h-[18px] text-[#90a1b9]"></i> Statistik Akademik
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-[20px]">
                  <div class="flex flex-col gap-[6px]">
                    <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Fakultas</label>
                    <div class="relative group">
                      <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                        <i data-lucide="building-2" stroke-width="2" class="w-[18px] h-[18px]"></i>
                      </div>
                      <input type="number" value="7" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                    </div>
                  </div>
                  <div class="flex flex-col gap-[6px]">
                    <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Program Studi</label>
                    <div class="relative group">
                      <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                        <i data-lucide="school" stroke-width="2" class="w-[18px] h-[18px]"></i>
                      </div>
                      <input type="number" value="30" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                    </div>
                  </div>
                  <div class="flex flex-col gap-[6px]">
                    <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Dosen</label>
                    <div class="relative group">
                      <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                        <i data-lucide="graduation-cap" stroke-width="2" class="w-[18px] h-[18px]"></i>
                      </div>
                      <input type="number" value="750" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                    </div>
                  </div>
                  <div class="flex flex-col gap-[6px]">
                    <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Tenaga Kependidikan</label>
                    <div class="relative group">
                      <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                        <i data-lucide="briefcase" stroke-width="2" class="w-[18px] h-[18px]"></i>
                      </div>
                      <input type="number" value="420" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Data Penanggung Jawab Card --}}
          <div class="bg-white rounded-[16px] w-full border border-[#e2e8f0] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05),0_2px_4px_-1px_rgba(0,0,0,0.03)] overflow-hidden transition-all hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.05),0_4px_6px_-2px_rgba(0,0,0,0.02)]">
            <div class="bg-gradient-to-r from-[#f8fafc] to-white px-[32px] py-[24px] border-b border-[#e2e8f0] flex items-center gap-[16px]">
              <div class="bg-white rounded-[10px] w-[48px] h-[48px] border border-[#e2e8f0] shadow-sm flex items-center justify-center">
                <i data-lucide="user-circle-2" class="w-[24px] h-[24px] text-[#0ea5e9]"></i>
              </div>
              <div>
                <h2 class="font-bold text-[#1d293d] text-[18px]">Data Penanggung Jawab (PIC)</h2>
                <p class="text-[#62748e] text-[13px] mt-[2px]">Kontak utama yang dapat dihubungi</p>
              </div>
            </div>

            <div class="p-[32px] grid grid-cols-1 md:grid-cols-2 gap-x-[32px] gap-y-[24px]">
              <div class="flex flex-col gap-[6px]">
                <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Nama PIC Lengkap</label>
                <div class="relative group">
                  <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <i data-lucide="user-circle-2" stroke-width="2" class="w-[18px] h-[18px]"></i>
                  </div>
                  <input type="text" value="Euis Nurul Hidayah" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                </div>
              </div>
              <div class="flex flex-col gap-[6px]">
                <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Jabatan/Posisi</label>
                <div class="relative group">
                  <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <i data-lucide="briefcase" stroke-width="2" class="w-[18px] h-[18px]"></i>
                  </div>
                  <input type="text" value="Kepala LPPM" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                </div>
              </div>
              <div class="flex flex-col gap-[6px]">
                <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Nomor HP / WhatsApp</label>
                <div class="relative group">
                  <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <i data-lucide="phone" stroke-width="2" class="w-[18px] h-[18px]"></i>
                  </div>
                  <input type="tel" value="+6281234567890" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                </div>
              </div>
              <div class="flex flex-col gap-[6px]">
                <label class="font-medium text-[#45556c] text-[14px] leading-[20px] ml-[2px]">Alamat Email</label>
                <div class="relative group">
                  <div class="absolute left-[14px] top-1/2 -translate-y-1/2 text-[#90a1b9]">
                    <i data-lucide="mail" stroke-width="2" class="w-[18px] h-[18px]"></i>
                  </div>
                  <input type="email" value="euis.nurul@upnjatim.ac.id" readonly class="w-full border rounded-[10px] h-[48px] pl-[44px] pr-[16px] font-medium text-[15px] focus:outline-none transition-all duration-200 bg-[#f1f5f9] border-transparent text-[#62748e] cursor-default" />
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
</x-layouts.dashboard>
