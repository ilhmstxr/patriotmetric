<x-layouts.app :hideNav="true" :hideFooter="true">
  <div x-data="{ activeSection: 1, isSubmitting: false }" class="min-h-screen bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif] flex flex-col selection:bg-[#1b5e20] selection:text-white">
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
            <h1 class="text-[28px] md:text-[32px] font-bold text-[#1d293d] tracking-tight mb-[12px]">Selamat Datang, Universitas Pembangunan Nasional "Veteran" Jawa Timur</h1>
            <p class="text-[#64748b] text-[15px] md:text-[16px] max-w-[600px] mx-auto">
              Lengkapi formulir di bawah ini untuk mengonfirmasi partisipasi institusi Anda dalam kegiatan 
              <strong class="text-[#1d293d] font-semibold"> Patriot Metric University Ranking 2026</strong>.
            </p>
          </div>

          <form @submit.prevent="isSubmitting = true; setTimeout(() => { window.location.href = '{{ route('dashboard.index') }}' }, 1500)" class="bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-[#e2e8f0] overflow-hidden">
            {{-- Progress Bar Mobile --}}
            <div class="md:hidden flex bg-[#f8fafc] border-b border-[#e2e8f0]">
              <button 
                type="button" 
                @click="activeSection = 1" 
                :class="activeSection === 1 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'"
                class="flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors"
                x-text="'1. Dokumen Legal'"
              ></button>
              <button 
                type="button" 
                @click="activeSection = 2" 
                :class="activeSection === 2 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'"
                class="flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors"
                x-text="'2. Berkas'"></button>
              <button 
                type="button" 
                @click="activeSection = 3" 
                :class="activeSection === 3 ? 'text-[#1b5e20] border-b-2 border-[#1b5e20]' : 'text-[#94a3b8]'"
                class="flex-1 py-[16px] text-center font-semibold text-[13px] transition-colors"
                x-text="'3. Data'"></button>
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
                  <a href="https://bit.ly/TemplateSuratPernyataanUPNJatimPatriotMetric" target="_blank" class="text-[#1b5e20] text-[13px] font-medium hover:underline flex items-center gap-[4px] w-fit">
                    <i data-lucide="file-text" class="w-[14px] h-[14px]"></i> 
                    Unduh Template Surat Pernyataan UPN Jatim Patriot Metric
                  </a>
                  <div class="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
                    <input type="file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
                    <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                      <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                    </div>
                    <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
                    <p class="text-[#64748b] text-[12px]">Maksimal 5MB dan Format PDF</p>
                  </div>
                  <div class="flex items-start gap-[6px] mt-[2px] bg-amber-50 p-[8px] rounded-[6px] border border-amber-100">
                    <i data-lucide="alert-circle" class="w-[14px] h-[14px] text-amber-600 mt-[2px] shrink-0"></i>
                    <p class="text-amber-800 text-[12px] leading-tight">
                      <span class="font-semibold">Format nama file:</span> SuratPernyataan_[NamaPerguruanTinggi]<br/>
                      <span class="text-amber-600/80">Contoh: SuratPernyataan_UPNVeteranJatim</span>
                    </p>
                  </div>
                </div>

                {{-- Field 2 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    2. Surat Keputusan (SK) Pendirian Perguruan Tinggi <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
                    <input type="file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
                    <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                      <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                    </div>
                    <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
                    <p class="text-[#64748b] text-[12px]">Maksimal 5MB dan Format PDF</p>
                  </div>
                  <div class="flex items-start gap-[6px] mt-[2px] bg-amber-50 p-[8px] rounded-[6px] border border-amber-100">
                    <i data-lucide="alert-circle" class="w-[14px] h-[14px] text-amber-600 mt-[2px] shrink-0"></i>
                    <p class="text-amber-800 text-[12px] leading-tight">
                      <span class="font-semibold">Format nama file:</span> SKPendirian_[NamaPerguruanTinggi]<br/>
                      <span class="text-amber-600/80">Contoh: SKPendirian_UPNVeteranJatim</span>
                    </p>
                  </div>
                </div>

                {{-- Field 3 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    3. Surat Keputusan Akreditasi Institusi Perguruan Tinggi (AIPT) <span class="text-red-500">*</span>
                  </label>
                  <p class="text-[#62748e] text-[13px] leading-relaxed">Unggah SK AIPT yang masih berlaku.</p>
                  <div class="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
                    <input type="file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
                    <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                      <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                    </div>
                    <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
                    <p class="text-[#64748b] text-[12px]">Maksimal 5MB dan Format PDF</p>
                  </div>
                  <div class="flex items-start gap-[6px] mt-[2px] bg-amber-50 p-[8px] rounded-[6px] border border-amber-100">
                    <i data-lucide="alert-circle" class="w-[14px] h-[14px] text-amber-600 mt-[2px] shrink-0"></i>
                    <p class="text-amber-800 text-[12px] leading-tight">
                      <span class="font-semibold">Format nama file:</span> SKAkreditasi_[NamaPerguruanTinggi]<br/>
                      <span class="text-amber-600/80">Contoh: SKAkreditasi_UPNVeteranJatim</span>
                    </p>
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
                  <p class="text-[#62748e] text-[13px] leading-relaxed">Unggah profil Perguruan Tinggi Peserta Pemeringkatan UPN Jatim Patriot Metric.</p>
                  <a href="https://docs.google.com/document/d/14qJSdTvFKcjrlzMAiE-aqOR72YpzNPAa/edit?usp=sharing&ouid=114349104875977587212&rtpof=true&sd=true" target="_blank" class="text-[#1b5e20] text-[13px] font-medium hover:underline flex items-center gap-[4px] w-fit">
                    <i data-lucide="file-text" class="w-[14px] h-[14px]"></i> 
                    Unduh Template pengisian profil Perguruan Tinggi
                  </a>
                  <div class="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
                    <input type="file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
                    <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                      <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                    </div>
                    <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
                    <p class="text-[#64748b] text-[12px]">Maksimal 5MB dan Format PDF</p>
                  </div>
                  <div class="flex items-start gap-[6px] mt-[2px] bg-amber-50 p-[8px] rounded-[6px] border border-amber-100">
                    <i data-lucide="alert-circle" class="w-[14px] h-[14px] text-amber-600 mt-[2px] shrink-0"></i>
                    <p class="text-amber-800 text-[12px] leading-tight">
                      <span class="font-semibold">Format nama file:</span> Profil PT_[NamaPerguruanTinggi]<br/>
                      <span class="text-amber-600/80">Contoh: Profil PT_UPNVeteranJatim</span>
                    </p>
                  </div>
                </div>

                {{-- Field 5 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    5. Logo Instansi <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
                    <input type="file" accept="image/png, image/jpeg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
                    <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                      <i data-lucide="image" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#0ea5e9]"></i>
                    </div>
                    <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
                    <p class="text-[#64748b] text-[12px]">Maksimal 5MB dan Format Image (JPG/PNG)</p>
                  </div>
                </div>

                {{-- Field 6 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    6. Struktur Organisasi Perguruan Tinggi <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
                    <input type="file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
                    <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                      <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                    </div>
                    <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
                    <p class="text-[#64748b] text-[12px]">Maksimal 5MB dan Format PDF</p>
                  </div>
                  <div class="flex items-start gap-[6px] mt-[2px] bg-amber-50 p-[8px] rounded-[6px] border border-amber-100">
                    <i data-lucide="alert-circle" class="w-[14px] h-[14px] text-amber-600 mt-[2px] shrink-0"></i>
                    <p class="text-amber-800 text-[12px] leading-tight">
                      <span class="font-semibold">Format nama file:</span> StrukturOrganisasi_[NamaPerguruanTinggi]<br/>
                      <span class="text-amber-600/80">Contoh: StrukturOrganisasi_UPNVeteranJatim</span>
                    </p>
                  </div>
                </div>

                {{-- Field 7 --}}
                <div class="flex flex-col gap-[8px]">
                  <label class="font-semibold text-[#1d293d] text-[14px]">
                    7. SK Tim Pemeringkatan UPN Jatim Patriot Metric <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-[4px] border-2 border-dashed border-[#cbd5e1] rounded-[12px] p-[24px] hover:border-[#1b5e20] hover:bg-[#f8fafc] transition-all group flex flex-col items-center justify-center text-center cursor-pointer relative overflow-hidden bg-white">
                    <input type="file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required />
                    <div class="bg-[#f1f5f9] p-[12px] rounded-full group-hover:bg-[#e0f2fe] transition-colors mb-[12px]">
                      <i data-lucide="upload" class="w-[24px] h-[24px] text-[#64748b] group-hover:text-[#1b5e20]"></i>
                    </div>
                    <p class="font-medium text-[#1d293d] text-[14px] mb-[4px]">Klik untuk mengunggah atau seret file ke sini</p>
                    <p class="text-[#64748b] text-[12px]">Maksimal 5MB dan Format PDF</p>
                  </div>
                  <div class="flex items-start gap-[6px] mt-[2px] bg-amber-50 p-[8px] rounded-[6px] border border-amber-100">
                    <i data-lucide="alert-circle" class="w-[14px] h-[14px] text-amber-600 mt-[2px] shrink-0"></i>
                    <p class="text-amber-800 text-[12px] leading-tight">
                      <span class="font-semibold">Format nama file:</span> SKTimPatriotMetric_[NamaPerguruanTinggi]<br/>
                      <span class="text-amber-600/80">Contoh: SKTimPatriotMetric_UPNVeteranJatim</span>
                    </p>
                  </div>
                </div>

                <div class="pt-[32px] mt-[16px] border-t border-[#e2e8f0] flex flex-col md:flex-row gap-[16px] items-center justify-between">
                  <button 
                    type="button" 
                    @click="activeSection = 1"
                    class="w-full md:w-auto text-[#64748b] hover:text-[#1d293d] px-[24px] py-[12px] rounded-[10px] font-semibold transition-colors flex items-center justify-center"
                  >
                    Kembali
                  </button>
                  <button 
                    type="button" 
                    @click="activeSection = 3"
                    class="w-full md:w-auto bg-[#1b5e20] hover:bg-[#15461c] text-white px-[32px] py-[14px] rounded-[10px] font-bold flex items-center justify-center gap-[10px] transition-all shadow-sm"
                  >
                    Selanjutnya 
                    <i data-lucide="arrow-right" class="w-[18px] h-[18px]"></i>
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
                      <input type="text" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: UPN Veteran Jawa Timur" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">9. Jenis Perguruan Tinggi <span class="text-red-500">*</span></label>
                      <select required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px] bg-white">
                        <option value="">Pilih Jenis...</option>
                        <option value="Negeri">Negeri</option>
                        <option value="Swasta">Swasta</option>
                        <option value="Kedinasan">Kedinasan</option>
                        <option value="Lainnya">Lainnya</option>
                      </select>
                    </div>
                  </div>

                  <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[#1d293d] text-[14px]">10. Visi Perguruan Tinggi <span class="text-red-500">*</span></label>
                    <textarea required rows="3" class="w-full border border-[#cbd5e1] rounded-[10px] p-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[14px] resize-y placeholder:text-[#94a3b8]" placeholder="Tuliskan Visi Perguruan Tinggi..."></textarea>
                  </div>

                  <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[#1d293d] text-[14px]">11. Misi Perguruan Tinggi <span class="text-red-500">*</span></label>
                    <textarea required rows="4" class="w-full border border-[#cbd5e1] rounded-[10px] p-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[14px] resize-y placeholder:text-[#94a3b8]" placeholder="Tuliskan Misi Perguruan Tinggi..."></textarea>
                  </div>
                </div>

                {{-- Group B: Akademik & SDM --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">B. Akademik & SDM</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">12. Jumlah Fakultas <span class="text-red-500">*</span></label>
                      <input type="number" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 7" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">13. Jumlah Program Studi <span class="text-red-500">*</span></label>
                      <input type="number" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 30" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">14. Jumlah Dosen <span class="text-red-500">*</span></label>
                      <input type="number" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 750" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">15. Jumlah Tenaga Akademik (Tendik) <span class="text-red-500">*</span></label>
                      <input type="number" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 420" />
                    </div>
                  </div>
                </div>

                {{-- Group C: Kemahasiswaan --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">C. Kemahasiswaan</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">16. Jumlah Mahasiswa Aktif <span class="text-red-500">*</span></label>
                      <input type="number" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 21000" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">17. Jumlah Ormawa <span class="text-red-500">*</span></label>
                      <input type="number" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 45" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">18. Jumlah UKM <span class="text-red-500">*</span></label>
                      <input type="number" required min="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: 28" />
                    </div>
                  </div>
                </div>

                {{-- Group D: Demografi Agama --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">D. Demografi Agama Mahasiswa</h3>
                  <div class="grid grid-cols-2 md:grid-cols-3 gap-[24px]">
                    @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $index => $agama)
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">19.{{ ['a','b','c','d','e','f'][$index] }} {{ $agama }}</label>
                      <input type="number" required min="0" value="0" class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" />
                    </div>
                    @endforeach
                  </div>
                </div>

                {{-- Group E: PIC --}}
                <div class="bg-[#f8fafc] p-6 rounded-xl border border-[#cbd5e1] space-y-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-2 border-b border-[#e2e8f0] pb-2">E. Kontak Penanggung Jawab (PIC)</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px]">
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">20. Nama PIC <span class="text-red-500">*</span></label>
                      <input type="text" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Nama lengkap beserta gelar" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">21. Jabatan PIC <span class="text-red-500">*</span></label>
                      <input type="text" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="Misal: Kepala LPPM" />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">22. No. HP / WhatsApp <span class="text-red-500">*</span></label>
                      <input type="text" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="+62..." />
                    </div>
                    <div class="flex flex-col gap-[8px]">
                      <label class="font-semibold text-[#1d293d] text-[14px]">23. Email PIC <span class="text-red-500">*</span></label>
                      <input type="email" required class="w-full border border-[#cbd5e1] rounded-[10px] h-[48px] px-[16px] focus:outline-none focus:border-[#1b5e20] focus:ring-4 focus:ring-[#1b5e20]/10 text-[15px]" placeholder="alamat@email.ac.id" />
                    </div>
                  </div>
                </div>

                <div class="pt-[32px] mt-[16px] border-t border-[#e2e8f0] flex flex-col md:flex-row gap-[16px] items-center justify-between">
                  <button 
                    type="button" 
                    @click="activeSection = 2"
                    class="w-full md:w-auto text-[#64748b] hover:text-[#1d293d] px-[24px] py-[12px] rounded-[10px] font-semibold transition-colors flex items-center justify-center"
                  >
                    Kembali
                  </button>
                  <button 
                    type="submit" 
                    :disabled="isSubmitting"
                    class="w-full md:w-auto bg-[#1b5e20] hover:bg-[#15461c] text-white px-[32px] py-[14px] rounded-[10px] font-bold flex items-center justify-center gap-[10px] transition-all shadow-[0_4px_14px_rgba(27,94,32,0.3)] hover:shadow-[0_6px_20px_rgba(27,94,32,0.4)] disabled:opacity-70 disabled:cursor-not-allowed"
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