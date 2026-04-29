<x-layouts.app :hideNav="true" :hideFooter="true">
  <div x-data="{ 
    activeSection: 1, 
    isSubmitting: false,
    errorMessage: '',
    successMessage: '',
    
    // Auth User Data
    user: JSON.parse(localStorage.getItem('auth_user')) || {},
    
    // Form Data
    formData: {
        nama_pt: '',
        jenis_pt: '',
        visi: '',
        misi: '',
        jumlah_fakultas: '',
        jumlah_prodi: '',
        jumlah_dosen: '',
        jumlah_tendik: '',
        jumlah_mahasiswa: '',
        jumlah_ormawa: '',
        jumlah_ukm: '',
        agama_islam: 0,
        agama_kristen: 0,
        agama_katolik: 0,
        agama_hindu: 0,
        agama_buddha: 0,
        agama_konghucu: 0,
        nama_pic: '',
        jabatan_pic: '',
        no_hp_pic: '',
        email_pic: ''
    },
    
    // File Data (store File objects)
    files: {
        surat_pernyataan: null,
        sk_pendirian: null,
        sk_akreditasi: null,
        profil_pt: null,
        logo_pt: null,
        struktur_organisasi: null,
        sk_tim: null
    },
    
    // File Previews
    previews: {
        surat_pernyataan: '',
        sk_pendirian: '',
        sk_akreditasi: '',
        profil_pt: '',
        logo_pt: '',
        struktur_organisasi: '',
        sk_tim: ''
    },

    init() {
        if (!localStorage.getItem('auth_token')) {
            window.location.href = '/masuk';
            return;
        }
        
        // Fetch user data if not in localStorage or to get fresh pengumpulan status
        this.fetchUserData();
    },
    
    async fetchUserData() {
        try {
            const response = await fetch('/api/auth/me', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            if (response.ok && result.success) {
                if (result.data.pengumpulan) {
                    const p = result.data.pengumpulan;
                    if (['IN_PROGRESS', 'SUBMITTED', 'GRADED'].includes(p.status)) {
                        window.location.href = '/dashboard';
                    }
                    if (p.institusi) {
                        this.formData.nama_pt = p.institusi.nama_institusi || '';
                        this.formData.jenis_pt = p.institusi.jenis_institusi || '';
                    }
                    this.formData.nama_pic = p.nama_pic || '';
                    this.formData.jabatan_pic = p.jabatan_pic || '';
                    this.formData.no_hp_pic = p.no_hp_pic || '';
                    this.formData.email_pic = p.email_pic || '';
                }
            } else {
                localStorage.removeItem('auth_token');
                window.location.href = '/masuk';
            }
        } catch (error) {
            console.error('Error fetching user data', error);
        }
    },

    handleFileChange(event, field, accept) {
        const file = event.target.files[0];
        if (!file) return;

        // Validasi max 5MB
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file maksimal 5MB');
            event.target.value = '';
            return;
        }

        // Simpan object file
        this.files[field] = file;

        // Preview
        if (accept.includes('image') && file.type.startsWith('image/')) {
            this.previews[field] = URL.createObjectURL(file);
        } else {
            this.previews[field] = file.name;
        }
    },

    removeFile(field) {
        this.files[field] = null;
        this.previews[field] = '';
        if (this.$refs[field]) {
            this.$refs[field].value = '';
        }
    },

    get isFormComplete() {
        const requiredFiles = ['surat_pernyataan', 'sk_pendirian', 'sk_akreditasi', 'profil_pt', 'logo_pt', 'struktur_organisasi', 'sk_tim'];
        const requiredData = ['nama_pt', 'jenis_pt', 'visi', 'misi', 'jumlah_fakultas', 'jumlah_prodi', 'jumlah_dosen', 'jumlah_tendik', 'jumlah_mahasiswa', 'jumlah_ormawa', 'jumlah_ukm', 'nama_pic', 'jabatan_pic', 'no_hp_pic', 'email_pic'];
        
        const filesComplete = requiredFiles.every(f => this.files[f] !== null);
        const dataComplete = requiredData.every(d => this.formData[d] !== '' && this.formData[d] !== null && this.formData[d] !== undefined);
        
        return filesComplete && dataComplete;
    },

    async submitForm() {
        if (!this.isFormComplete) {
            this.errorMessage = 'Mohon lengkapi semua field dan file yang diwajibkan.';
            return;
        }

        this.isSubmitting = true;
        this.errorMessage = '';
        
        const payload = new FormData();
        
        // Append form data
        for (const key in this.formData) {
            payload.append(key, this.formData[key]);
        }
        
        // Append files
        for (const key in this.files) {
            if (this.files[key]) {
                payload.append(key, this.files[key]);
            }
        }

        try {
            const response = await fetch('/api/auth/verification', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Accept': 'application/json'
                },
                body: payload
            });

            const result = await response.json();

            if (response.ok && result.success) {
                this.successMessage = result.message;
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1500);
            } else {
                if (result.errors) {
                    this.errorMessage = Object.values(result.errors)[0][0];
                } else {
                    this.errorMessage = result.message || 'Verifikasi gagal.';
                }
            }
        } catch (error) {
            this.errorMessage = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        } finally {
            this.isSubmitting = false;
        }
    }
  }" class="min-h-screen bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif] flex flex-col selection:bg-[#1b5e20] selection:text-white">
    {{-- Header Form --}}
    <header class="sticky top-0 z-50 bg-[rgba(255,255,255,0.85)] backdrop-blur-md border-b border-[rgba(255,255,255,0.2)] shadow-[0px_4px_30px_0px_rgba(27,94,32,0.05)]">
      <div class="max-w-[1536px] mx-auto flex items-center justify-between h-[65px] px-[24px]">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex gap-[7px] items-center shrink-0 -ml-[12px] md:-ml-0">
          <div class="h-[73px] w-[124px] relative shrink-0">
            <img alt="Patriot Metric" class="absolute inset-0 max-w-none object-contain pointer-events-none size-full" src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" />
          </div>
          <div class="flex flex-col h-[32px] items-start px-2">
            <div class="bg-[#cbd5e1] h-[32px] w-px"></div>
          </div>
          <div class="flex gap-[10px] items-center">
            <div class="relative size-[44px] shrink-0 hidden md:block">
              <img alt="UPN Veteran Jatim" class="absolute inset-0 max-w-none object-cover pointer-events-none size-full" src="{{ asset('assets/images/199dc2ebf1e9cecf5218f4b20951209708831231.png') }}" />
            </div>
            <div class="hidden sm:flex flex-col font-['Plus_Jakarta_Sans',sans-serif] font-bold h-[25px] justify-center leading-[12.5px] text-[#64748b] text-[10px] uppercase w-[237px]">
              <p>Universitas Pembangunan nasional "veteran" jawa timur</p>
            </div>
          </div>
        </a>

          {{-- Step Indicators --}}
          <div class="hidden md:flex items-center gap-[12px]">
            <div :class="activeSection === 1 ? 'text-[#1b5e20]' : 'text-[#94a3b8]'" class="flex items-center gap-[8px] transition-colors">
              <div :class="activeSection === 1 ? 'border-[#1b5e20] bg-[#f2fcf3]' : 'border-[#e2e8f0]'" class="w-[28px] h-[28px] rounded-full flex items-center justify-center font-bold text-[13px] border-2 transition-colors">1</div>
              <span class="font-semibold text-[14px]">Dokumen Legal</span>
            </div>
            <div class="w-[32px] h-[2px] bg-[#e2e8f0]"></div>
            <div :class="activeSection === 2 ? 'text-[#1b5e20]' : 'text-[#94a3b8]'" class="flex items-center gap-[8px] transition-colors">
              <div :class="activeSection === 2 ? 'border-[#1b5e20] bg-[#f2fcf3]' : 'border-[#e2e8f0]'" class="w-[28px] h-[28px] rounded-full flex items-center justify-center font-bold text-[13px] border-2 transition-colors">2</div>
              <span class="font-semibold text-[14px]">Berkas Profil</span>
            </div>
            <div class="w-[32px] h-[2px] bg-[#e2e8f0]"></div>
            <div :class="activeSection === 3 ? 'text-[#1b5e20]' : 'text-[#94a3b8]'" class="flex items-center gap-[8px] transition-colors">
              <div :class="activeSection === 3 ? 'border-[#1b5e20] bg-[#f2fcf3]' : 'border-[#e2e8f0]'" class="w-[28px] h-[28px] rounded-full flex items-center justify-center font-bold text-[13px] border-2 transition-colors">3</div>
              <span class="font-semibold text-[14px]">Data Profil</span>
            </div>
          </div>
        </div>
      </header>

      <main class="flex-1 py-[40px] px-[24px]">
        <div class="max-w-[800px] mx-auto">
          <div class="text-center mb-[40px]">
            <h1 class="text-[28px] md:text-[32px] font-bold text-[#1d293d] tracking-tight mb-[12px]">
              Selamat Datang, <span x-text="formData.nama_pt || user.email || 'Peserta'"></span>
            </h1>
            <p class="text-[#64748b] text-[15px] md:text-[16px] max-w-[600px] mx-auto">
              Lengkapi formulir di bawah ini untuk mengonfirmasi partisipasi institusi Anda dalam kegiatan 
              <strong class="text-[#1d293d] font-semibold"> Patriot Metric University Ranking 2026</strong>.
            </p>
          </div>

          <!-- Alert Messages -->
          <div x-show="errorMessage" style="display: none;" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mt-0.5"></i>
            <p class="text-red-700 text-[14px] font-medium" x-text="errorMessage"></p>
          </div>
          <div x-show="successMessage" style="display: none;" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mt-0.5"></i>
            <p class="text-green-700 text-[14px] font-medium" x-text="successMessage"></p>
          </div>

          <form @submit.prevent="submitForm" class="bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-[#e2e8f0] overflow-hidden">
            {{-- Progress Bar Mobile --}}
            <div class="md:hidden flex bg-[#f8fafc] border-b border-[#e2e8f0]">
              <button type="button" @click="activeSection = 1" :class="activeSection === 1 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'" class="flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors">1. Dokumen Legal</button>
              <button type="button" @click="activeSection = 2" :class="activeSection === 2 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'" class="flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors">2. Berkas</button>
              <button type="button" @click="activeSection = 3" :class="activeSection === 3 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'" class="flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors">3. Data</button>
            </div>

          <div class="p-[32px] md:p-[48px]">
            {{-- SECTION 1: Dokumen Legal --}}
            <div x-show="activeSection === 1" x-transition.opacity.duration.500ms class="space-y-[32px]">
              <div class="pb-[16px] border-b border-[#e2e8f0] mb-[32px]">
                <h2 class="text-[20px] font-bold text-[#1d293d]">Bagian 1: Dokumen Legalitas</h2>
                <p class="text-[#64748b] text-[14px] mt-[4px]">Unggah berkas-berkas legalitas perguruan tinggi Anda.</p>
              </div>

                {{-- Field 1 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    1. Surat Pernyataan Resmi <span class="text-red-500">*</span>
                  </label>
                  <p class="text-[#62748e] text-[13px] leading-relaxed">Unggah Surat Pernyataan resmi yang ditandatangani oleh pimpinan perguruan tinggi sebagai bentuk konfirmasi keikutsertaan.</p>
                  
                  <div class="mt-[4px] relative">
                      <div x-show="!previews.surat_pernyataan" class="border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden bg-white relative">
                        <input type="file" accept=".pdf" x-ref="surat_pernyataan" @change="handleFileChange($event, 'surat_pernyataan', '.pdf')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                          <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                        </div>
                        <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah PDF</p>
                        <p class="text-[#64748b] text-[12px]">Maks 5MB</p>
                      </div>
                      
                      <!-- Preview PDF -->
                      <div x-show="previews.surat_pernyataan" style="display: none;" class="border border-[#cbd5e1] rounded-[12px] p-[16px] bg-white flex items-center justify-between">
                          <div class="flex items-center gap-3 overflow-hidden">
                              <div class="bg-red-50 p-2 rounded-lg text-red-500 shrink-0">
                                  <i data-lucide="file-text" class="w-6 h-6"></i>
                              </div>
                              <div class="truncate">
                                  <p class="text-[14px] font-semibold text-[#1d293d] truncate" x-text="previews.surat_pernyataan"></p>
                                  <p class="text-[12px] text-[#64748b]" x-text="files.surat_pernyataan ? (files.surat_pernyataan.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                              </div>
                          </div>
                          <button type="button" @click="removeFile('surat_pernyataan')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors shrink-0">
                              <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                      </div>
                  </div>
                </div>

                {{-- Field 2 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    2. Surat Keputusan (SK) Pendirian Perguruan Tinggi <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] relative">
                      <div x-show="!previews.sk_pendirian" class="border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden bg-white relative">
                        <input type="file" accept=".pdf" x-ref="sk_pendirian" @change="handleFileChange($event, 'sk_pendirian', '.pdf')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                          <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                        </div>
                        <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah PDF</p>
                        <p class="text-[#64748b] text-[12px]">Maks 5MB</p>
                      </div>
                      
                      <!-- Preview PDF -->
                      <div x-show="previews.sk_pendirian" style="display: none;" class="border border-[#cbd5e1] rounded-[12px] p-[16px] bg-white flex items-center justify-between">
                          <div class="flex items-center gap-3 overflow-hidden">
                              <div class="bg-red-50 p-2 rounded-lg text-red-500 shrink-0">
                                  <i data-lucide="file-text" class="w-6 h-6"></i>
                              </div>
                              <div class="truncate">
                                  <p class="text-[14px] font-semibold text-[#1d293d] truncate" x-text="previews.sk_pendirian"></p>
                                  <p class="text-[12px] text-[#64748b]" x-text="files.sk_pendirian ? (files.sk_pendirian.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                              </div>
                          </div>
                          <button type="button" @click="removeFile('sk_pendirian')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors shrink-0">
                              <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                      </div>
                  </div>
                </div>

                {{-- Field 3 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    3. Surat Keputusan Akreditasi Institusi Perguruan Tinggi (AIPT) <span class="text-red-500">*</span>
                  </label>
                  <p class="text-[#62748e] text-[13px] leading-relaxed">Unggah SK AIPT yang masih berlaku.</p>
                  <div class="mt-[4px] relative">
                      <div x-show="!previews.sk_akreditasi" class="border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden bg-white relative">
                        <input type="file" accept=".pdf" x-ref="sk_akreditasi" @change="handleFileChange($event, 'sk_akreditasi', '.pdf')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                          <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                        </div>
                        <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah PDF</p>
                        <p class="text-[#64748b] text-[12px]">Maks 5MB</p>
                      </div>
                      
                      <!-- Preview PDF -->
                      <div x-show="previews.sk_akreditasi" style="display: none;" class="border border-[#cbd5e1] rounded-[12px] p-[16px] bg-white flex items-center justify-between">
                          <div class="flex items-center gap-3 overflow-hidden">
                              <div class="bg-red-50 p-2 rounded-lg text-red-500 shrink-0">
                                  <i data-lucide="file-text" class="w-6 h-6"></i>
                              </div>
                              <div class="truncate">
                                  <p class="text-[14px] font-semibold text-[#1d293d] truncate" x-text="previews.sk_akreditasi"></p>
                                  <p class="text-[12px] text-[#64748b]" x-text="files.sk_akreditasi ? (files.sk_akreditasi.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                              </div>
                          </div>
                          <button type="button" @click="removeFile('sk_akreditasi')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors shrink-0">
                              <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                      </div>
                  </div>
                </div>

                <div class="pt-[24px] mt-[16px] flex justify-end">
                  <button 
                    type="button" 
                    @click="activeSection = 2"
                    class="bg-[#1b5e20] hover:bg-[#15461c] text-white px-[24px] py-[12px] rounded-[10px] font-semibold flex items-center gap-[8px] transition-all shadow-sm"
                  >
                    Selanjutnya 
                    <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
                  </button>
                </div>
              </div>

              {{-- SECTION 2: Profil Institusi --}}
              <div x-show="activeSection === 2" style="display: none;" x-transition.opacity.duration.500ms class="space-y-[32px]">
                <div class="pb-[16px] border-b border-[#e2e8f0] mb-[32px]">
                  <h2 class="text-[20px] font-bold text-[#1d293d]">Bagian 2: Berkas Profil Institusi</h2>
                  <p class="text-[#64748b] text-[14px] mt-[4px]">Unggah dokumen profil, logo, struktur, dan SK Tim.</p>
                </div>

                {{-- Field 4 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    4. Profil Perguruan Tinggi <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] relative">
                      <div x-show="!previews.profil_pt" class="border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden bg-white relative">
                        <input type="file" accept=".pdf" x-ref="profil_pt" @change="handleFileChange($event, 'profil_pt', '.pdf')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                          <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                        </div>
                        <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah PDF</p>
                        <p class="text-[#64748b] text-[12px]">Maks 5MB</p>
                      </div>
                      <div x-show="previews.profil_pt" style="display: none;" class="border border-[#cbd5e1] rounded-[12px] p-[16px] bg-white flex items-center justify-between">
                          <div class="flex items-center gap-3 overflow-hidden">
                              <div class="bg-red-50 p-2 rounded-lg text-red-500 shrink-0">
                                  <i data-lucide="file-text" class="w-6 h-6"></i>
                              </div>
                              <div class="truncate">
                                  <p class="text-[14px] font-semibold text-[#1d293d] truncate" x-text="previews.profil_pt"></p>
                                  <p class="text-[12px] text-[#64748b]" x-text="files.profil_pt ? (files.profil_pt.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                              </div>
                          </div>
                          <button type="button" @click="removeFile('profil_pt')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors shrink-0">
                              <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                      </div>
                  </div>
                </div>

                {{-- Field 5 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    5. Logo Instansi <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] relative">
                      <div x-show="!previews.logo_pt" class="border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden bg-white relative">
                        <input type="file" accept="image/png, image/jpeg, image/jpg" x-ref="logo_pt" @change="handleFileChange($event, 'logo_pt', 'image/*')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                          <i data-lucide="image" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#0ea5e9]"></i>
                        </div>
                        <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah Gambar</p>
                        <p class="text-[#64748b] text-[12px]">Maks 5MB (JPG/PNG)</p>
                      </div>
                      
                      <!-- Preview Image -->
                      <div x-show="previews.logo_pt" style="display: none;" class="border border-[#cbd5e1] rounded-[12px] p-[16px] bg-white flex items-center justify-between">
                          <div class="flex items-center gap-4 overflow-hidden">
                              <img :src="previews.logo_pt" alt="Preview Logo" class="w-16 h-16 object-contain rounded-lg border border-[#e2e8f0] bg-[#f8fafc]" />
                              <div class="truncate">
                                  <p class="text-[14px] font-semibold text-[#1d293d] truncate" x-text="files.logo_pt ? files.logo_pt.name : ''"></p>
                                  <p class="text-[12px] text-[#64748b]" x-text="files.logo_pt ? (files.logo_pt.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                              </div>
                          </div>
                          <button type="button" @click="removeFile('logo_pt')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors shrink-0">
                              <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                      </div>
                  </div>
                </div>

                {{-- Field 6 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    6. Struktur Organisasi Perguruan Tinggi <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] relative">
                      <div x-show="!previews.struktur_organisasi" class="border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden bg-white relative">
                        <input type="file" accept=".pdf" x-ref="struktur_organisasi" @change="handleFileChange($event, 'struktur_organisasi', '.pdf')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                          <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                        </div>
                        <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah PDF</p>
                        <p class="text-[#64748b] text-[12px]">Maks 5MB</p>
                      </div>
                      <div x-show="previews.struktur_organisasi" style="display: none;" class="border border-[#cbd5e1] rounded-[12px] p-[16px] bg-white flex items-center justify-between">
                          <div class="flex items-center gap-3 overflow-hidden">
                              <div class="bg-red-50 p-2 rounded-lg text-red-500 shrink-0">
                                  <i data-lucide="file-text" class="w-6 h-6"></i>
                              </div>
                              <div class="truncate">
                                  <p class="text-[14px] font-semibold text-[#1d293d] truncate" x-text="previews.struktur_organisasi"></p>
                                  <p class="text-[12px] text-[#64748b]" x-text="files.struktur_organisasi ? (files.struktur_organisasi.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                              </div>
                          </div>
                          <button type="button" @click="removeFile('struktur_organisasi')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors shrink-0">
                              <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                      </div>
                  </div>
                </div>

                {{-- Field 7 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    7. SK Tim Pemeringkatan UPN Jatim Patriot Metric <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] relative">
                      <div x-show="!previews.sk_tim" class="border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer overflow-hidden bg-white relative">
                        <input type="file" accept=".pdf" x-ref="sk_tim" @change="handleFileChange($event, 'sk_tim', '.pdf')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                          <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                        </div>
                        <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah PDF</p>
                        <p class="text-[#64748b] text-[12px]">Maks 5MB</p>
                      </div>
                      <div x-show="previews.sk_tim" style="display: none;" class="border border-[#cbd5e1] rounded-[12px] p-[16px] bg-white flex items-center justify-between">
                          <div class="flex items-center gap-3 overflow-hidden">
                              <div class="bg-red-50 p-2 rounded-lg text-red-500 shrink-0">
                                  <i data-lucide="file-text" class="w-6 h-6"></i>
                              </div>
                              <div class="truncate">
                                  <p class="text-[14px] font-semibold text-[#1d293d] truncate" x-text="previews.sk_tim"></p>
                                  <p class="text-[12px] text-[#64748b]" x-text="files.sk_tim ? (files.sk_tim.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></p>
                              </div>
                          </div>
                          <button type="button" @click="removeFile('sk_tim')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors shrink-0">
                              <i data-lucide="trash-2" class="w-5 h-5"></i>
                          </button>
                      </div>
                  </div>
                </div>

                <div class="pt-[32px] mt-[16px] border-t border-[#e2e8f0] flex flex-col md:flex-row gap-[16px] items-center justify-between">
                  <button type="button" @click="activeSection = 1" class="w-full md:w-auto text-[#64748b] hover:text-[#1d293d] px-[24px] py-[12px] rounded-[10px] font-semibold transition-colors flex items-center justify-center">Kembali</button>
                  <button type="button" @click="activeSection = 3" class="w-full md:w-auto bg-[#1b5e20] hover:bg-[#15461c] text-white px-[32px] py-[14px] rounded-[10px] font-bold flex items-center justify-center gap-[10px] transition-all shadow-sm">
                    Selanjutnya <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
                  </button>
                </div>
              </div>

              {{-- SECTION 3: Data Profil Institusi --}}
              <div x-show="activeSection === 3" style="display: none;" x-transition.opacity.duration.500ms class="space-y-[32px]">
                <div class="pb-[16px] border-b border-[#e2e8f0] mb-[32px]">
                  <h2 class="text-[20px] font-bold text-[#1d293d]">Bagian 3: Data Profil Institusi</h2>
                  <p class="text-[#64748b] text-[14px] mt-[4px]">Lengkapi data identitas, akademik, kemahasiswaan, dan demografi.</p>
                </div>

                {{-- Group A: Identitas Institusi --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">A. Identitas Institusi</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">8. Nama Perguruan Tinggi <span class="text-red-500">*</span></label>
                      <input type="text" x-model="formData.nama_pt" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: UPN Veteran Jawa Timur" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">9. Jenis Perguruan Tinggi <span class="text-red-500">*</span></label>
                      <select x-model="formData.jenis_pt" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px] bg-white">
                        <option value="">Pilih Jenis...</option>
                        <option value="PTN">PTN</option>
                        <option value="PTS">PTS</option>
                        <option value="PTK">PTK</option>
                        <option value="Lainnya">Lainnya</option>
                      </select>
                    </div>
                  </div>
                  <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[#1d293d] text-[14px]">10. Visi Perguruan Tinggi <span class="text-red-500">*</span></label>
                    <textarea x-model="formData.visi" required rows="3" class="w-full border border-[#cbd5e1] rounded-[10px] p-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[14px] resize-y placeholder:text-[#94a3b8]"></textarea>
                  </div>
                  <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[#1d293d] text-[14px]">11. Misi Perguruan Tinggi <span class="text-red-500">*</span></label>
                    <textarea x-model="formData.misi" required rows="4" class="w-full border border-[#cbd5e1] rounded-[10px] p-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[14px] resize-y placeholder:text-[#94a3b8]"></textarea>
                  </div>
                </div>

                {{-- Group B: Akademik & SDM --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">B. Akademik & SDM</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">12. Jumlah Fakultas <span class="text-red-500">*</span></label>
                      <input type="number" x-model="formData.jumlah_fakultas" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">13. Jumlah Program Studi <span class="text-red-500">*</span></label>
                      <input type="number" x-model="formData.jumlah_prodi" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">14. Jumlah Dosen <span class="text-red-500">*</span></label>
                      <input type="number" x-model="formData.jumlah_dosen" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">15. Jumlah Tendik <span class="text-red-500">*</span></label>
                      <input type="number" x-model="formData.jumlah_tendik" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                  </div>
                </div>

                {{-- Group C: Kemahasiswaan --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">C. Kemahasiswaan</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">16. Jumlah Mahasiswa <span class="text-red-500">*</span></label>
                      <input type="number" x-model="formData.jumlah_mahasiswa" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">17. Jumlah Ormawa <span class="text-red-500">*</span></label>
                      <input type="number" x-model="formData.jumlah_ormawa" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">18. Jumlah UKM <span class="text-red-500">*</span></label>
                      <input type="number" x-model="formData.jumlah_ukm" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                  </div>
                </div>

                {{-- Group D: Demografi Agama --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">D. Demografi Agama Mahasiswa</h3>
                  <div class="grid grid-cols-2 md:grid-cols-3 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Islam</label>
                      <input type="number" x-model="formData.agama_islam" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Kristen</label>
                      <input type="number" x-model="formData.agama_kristen" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Katolik</label>
                      <input type="number" x-model="formData.agama_katolik" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Hindu</label>
                      <input type="number" x-model="formData.agama_hindu" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Buddha</label>
                      <input type="number" x-model="formData.agama_buddha" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Konghucu</label>
                      <input type="number" x-model="formData.agama_konghucu" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                  </div>
                </div>

                {{-- Group E: PIC --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">E. Kontak Penanggung Jawab (PIC)</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Nama PIC <span class="text-red-500">*</span></label>
                      <input type="text" x-model="formData.nama_pic" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Jabatan PIC <span class="text-red-500">*</span></label>
                      <input type="text" x-model="formData.jabatan_pic" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                      <input type="text" x-model="formData.no_hp_pic" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">Email PIC <span class="text-red-500">*</span></label>
                      <input type="email" x-model="formData.email_pic" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                  </div>
                </div>

                <div class="pt-[32px] mt-[16px] border-t border-[#e2e8f0] flex flex-col md:flex-row gap-[16px] items-center justify-between">
                  <button type="button" @click="activeSection = 2" class="w-full md:w-auto text-[#64748b] hover:text-[#1d293d] px-[24px] py-[12px] rounded-[10px] font-semibold transition-colors flex items-center justify-center">Kembali</button>
                  <button 
                    type="submit" 
                    :disabled="isSubmitting || !isFormComplete"
                    class="w-full md:w-auto bg-[#1b5e20] hover:bg-[#15461c] text-white px-[32px] py-[14px] rounded-[10px] font-bold flex items-center justify-center gap-[10px] transition-all shadow-[0_4px_14px_rgba(27,94,32,0.3)] hover:shadow-[0_6px_20px_rgba(27,94,32,0.4)] disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <template x-if="isSubmitting">
                      <div class="flex items-center gap-2">
                        <div class="w-[20px] h-[20px] border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        Memproses...
                      </div>
                    </template>
                    <template x-if="!isSubmitting">
                      <div class="flex items-center gap-2">
                        <i data-lucide="send" class="w-[18px] h-[18px]"></i>
                        Submit Pendaftaran
                      </div>
                    </template>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>
</x-layouts.app>