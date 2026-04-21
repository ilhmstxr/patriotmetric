<x-layouts.reviewer>
    @php 
        $isDone = request('status') === 'done'; 
        // Mock nama peserta based on ID
        $reqId = request('id', '1');
        $namaPeserta = $reqId == 2 ? 'Universitas Gadjah Mada' : ($reqId == 3 ? 'Universitas Airlangga' : 'Universitas Indonesia');
    @endphp
    <x-slot:title>DETAIL PENILAIAN - {{ $namaPeserta }}</x-slot:title>
    <div x-data="{
        activeTab: 'penilaian',
        isSaving: false,
        lastSaved: '',
        isDone: {{ $isDone ? 'true' : 'false' }},
        
        {{-- MOCK DATA JAWABAN PESERTA --}}
        answers: {
            'kebijakan_1': 'Dibutuhkan dan diimplementasikan dalam dua kegiatan dari Tridharma',
            'kebijakan_2': 'Ada kebijakan, pedoman, sosialisasi, Satgas Pencegahan dan Penanganan Kekerasan',
            'kebijakan_3': 'Kebijakan penggunaan produk lokal diterapkan di terapkan secara konsisten dan mendapat dukungan penuh',
            'kelembagaan_1': 'Ada unit kerja, program kerja, kegiatan implementasi, evaluasi program',
            'kelembagaan_2': '15',
            'patriotisme_1': '2 mahasiswa aktif',
            'patriotisme_2': 'Terdapat 11 - 15 UKM'
        },
        links: {
            'kebijakan_1': 'https://drive.google.com/file/d/dummy-link-1/view',
            'kebijakan_2': 'https://drive.google.com/file/d/dummy-link-2/view',
            'kebijakan_3': 'https://drive.google.com/file/d/dummy-link-3/view',
            'kelembagaan_1': 'https://drive.google.com/file/d/dummy-link-4/view',
            'kelembagaan_2': 'https://drive.google.com/file/d/dummy-link-5/view',
            'patriotisme_1': 'https://drive.google.com/file/d/dummy-link-6/view',
            'patriotisme_2': 'https://drive.google.com/file/d/dummy-link-7/view'
        },
        
        {{-- DATA PENILAIAN REVIEWER (Dengam Auto-Save LocalStorage) --}}
        reviewerScores: JSON.parse(localStorage.getItem('reviewerScores_{{ request('id', '1') }}')) || {},
        reviewerNotes: JSON.parse(localStorage.getItem('reviewerNotes_{{ request('id', '1') }}')) || {},
        
        init() {
            let timeout = null;
            
            const autoSave = () => {
                this.isSaving = true;
                clearTimeout(timeout);
                
                // Menggunakan Debounce 1 Detik
                timeout = setTimeout(() => {
                    // Simpan ke local storage
                    localStorage.setItem('reviewerScores_{{ request('id', '1') }}', JSON.stringify(this.reviewerScores));
                    localStorage.setItem('reviewerNotes_{{ request('id', '1') }}', JSON.stringify(this.reviewerNotes));
                    
                    // TODO: Ganti dengan Fetch API sebenarnya
                    console.log('API Target: POST /api/reviewer/save-draft (MOCK)');
                    console.log('Payload:', { scores: this.reviewerScores, notes: this.reviewerNotes });
                    
                    // Simulasi delay HTTP Request
                    setTimeout(() => {
                        this.isSaving = false;
                        let d = new Date();
                        this.lastSaved = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                    }, 600);
                }, 1000);
            };

            this.$watch('reviewerScores', value => autoSave());
            this.$watch('reviewerNotes', value => autoSave());
        },

        {{-- MOCK DATABASE RUBRIK (Bisa diganti data backend) --}}
        mockDatabase: [
          {
            category: 'KEBIJAKAN (01)',
            weight: '25%',
            questions: [
              {
                id: 'kebijakan_1',
                code: '01',
                title: 'Kebijakan/implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma',
                description: 'Berdasarkan pedoman dan/atau implementasi terdapat unsur bela negara di tingkat akademik.',
                evidenceRequirements: [
                  '1. Dokumen Kebijakan berupa SK',
                  '2. Bukti implementasi (disertasi, tesis, penelitian, atau pengabdian)'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada kebijakan namun tetap belum diimplementasikan',
                  'Dibutuhkan dan diimplementasikan dalam mana kegiatan dari Tridharma',
                  'Dibutuhkan dan diimplementasikan dalam dua kegiatan dari Tridharma',
                  'Dibutuhkan dan diimplementasikan dalam seluruh kegiatan Tridharma serta kegiatan penunjang',
                  'Dibutuhkan dan diimplementasikan dalam seluruh kegiatan Tridharma dan kegiatan penunjang'
                ]
              },
              {
                id: 'kebijakan_2',
                code: '02',
                title: 'Kebijakan pencegahan dan penanganan kekerasan',
                description: 'Meliputi kelengkapan instrumen pencegahan dan penanganan kekerasan di kampus.',
                evidenceRequirements: [
                  '1. Dokumen Kebijakan / Pedoman',
                  '2. SK Satgas PPKS',
                  '3. Dokumentasi Sosialisasi',
                  '4. Laporan/jurnal tindak lanjut'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada kebijakan, pedoman pencegahan dan penanganan kekerasan tetapi belum diimplementasikan',
                  'Ada kebijakan, pedoman, sosialisasi, langkah pencegahan dan penanganan kekerasan',
                  'Ada kebijakan, pedoman, sosialisasi, Satgas Pencegahan dan Penanganan Kekerasan',
                  'Ada kebijakan, pedoman, sosialisasi, Satgas, jurnal pelaporan/tindak lanjut',
                  'Lengkap beserta tindak lanjut laporan (termasuk pendampingan, perlindungan, pemulihan korban dan sanksi)'
                ]
              }
            ]
          },
          {
            category: 'KELEMBAGAAN (02)',
            weight: '25%',
            questions: [
              {
                id: 'kelembagaan_1',
                code: '01',
                title: 'Unit kerja yang berfokus pada pengembangan karakter bela negara',
                description: 'Unit khusus yang berdedikasi membangun karakter kepemimpinan dan patriotisme sivitas.',
                evidenceRequirements: [
                  '1. SK Pembentukan Unit Kerja',
                  '2. Dokumen Program Kerja',
                  '3. Laporan Pelaksanaan Program'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada unit kerja',
                  'Ada unit kerja, program kerja',
                  'Ada unit kerja, program kerja, kegiatan implementasi program kerja',
                  'Ada unit kerja, program kerja, kegiatan implementasi, evaluasi program',
                  'Ada unit kerja, program kerja, kegiatan implementasi, evaluasi, perencanaan tahun berikutnya'
                ]
              }
            ]
          }
        ]
    }" class="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
        
      {{-- Page Header --}}
      <div class="bg-white border-b border-[#e2e8f0] px-[20px] md:px-[40px] pt-[20px] md:pt-[28px] shadow-sm">
        <a href="{{ route('reviewer.index') }}" class="inline-flex items-center gap-[6px] text-[#62748e] hover:text-[#1b5e20] text-[13px] font-semibold mb-[8px] transition-colors">
          <i data-lucide="arrow-left" class="w-[14px] h-[14px]"></i> Kembali ke Daftar Plotting
        </a>
        
        <div class="flex flex-col md:flex-row items-start justify-between md:items-end mb-[20px]">
            <div>
                <h1 class="font-bold text-[#1d293d] text-[20px] md:text-[26px] tracking-tight flex flex-col md:flex-row items-start md:items-center gap-[8px] md:gap-[12px]">
                    Menu Penilaian - {{ $namaPeserta }}
                    @if($isDone)
                    <span class="inline-flex items-center gap-[6px] bg-green-100 text-green-700 px-[12px] py-[4px] rounded-full text-[13px] md:text-[14px] font-bold mt-1 md:mt-0">
                        <i data-lucide="check-circle-2" class="w-[16px] h-[16px]"></i> Selesai Dinilai
                    </span>
                    @endif
                </h1>
                <p class="text-[#62748e] text-[13px] md:text-[15px] mt-[6px]">Review isian data, periksa dokumen, dan berikan skor final (1-5).</p>
            </div>
            
            {{-- Auto Saving Indicator --}}
            <div class="mt-4 md:mt-0 px-[16px] py-[8px] bg-slate-50 border border-slate-200 rounded-[8px] inline-flex items-center gap-[8px] min-w-[180px]">
                <template x-if="isSaving">
                    <span class="flex items-center gap-[6px] text-[13px] font-semibold text-amber-600">
                        <i data-lucide="loader-2" class="w-[14px] h-[14px] animate-spin"></i> Menyimpan draft...
                    </span>
                </template>
                <template x-if="!isSaving && lastSaved">
                    <span class="flex items-center gap-[6px] text-[13px] font-semibold text-green-600">
                        <i data-lucide="check" class="w-[14px] h-[14px]"></i> Draft tersimpan <span x-text="lastSaved"></span>
                    </span>
                </template>
                <template x-if="!isSaving && !lastSaved">
                    <span class="text-[13px] font-medium text-slate-500">Draft siap auto-save.</span>
                </template>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-[24px]">
            <button @click="activeTab = 'profil'" 
                    class="py-[16px] text-[14px] font-bold transition-colors border-b-[3px]" 
                    :class="activeTab === 'profil' ? 'text-[#1b5e20] border-[#1b5e20]' : 'text-[#64748b] border-transparent hover:text-[#1b5e20]'">
                <i data-lucide="user" class="w-[16px] h-[16px] inline-block mr-1 mb-0.5"></i> Data Profil Peserta
            </button>
            <button @click="activeTab = 'penilaian'" 
                    class="py-[16px] text-[14px] font-bold transition-colors border-b-[3px]" 
                    :class="activeTab === 'penilaian' ? 'text-[#1b5e20] border-[#1b5e20]' : 'text-[#64748b] border-transparent hover:text-[#1b5e20]'">
                <i data-lucide="check-square" class="w-[16px] h-[16px] inline-block mr-1 mb-0.5"></i> Form Penilaian Rubrik
            </button>
        </div>
      </div>

      {{-- Content Area --}}
      <div class="flex-1 overflow-y-auto px-[20px] md:px-[40px] py-[32px]">
        <div class="max-w-[1000px] mx-auto">
            
            {{-- TAB: DATA PROFIL PESERTA --}}
            <div x-show="activeTab === 'profil'" x-transition.opacity.duration.300ms style="display: none;">
                
                {{-- Group A: Identitas Institusi --}}
                <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1] mb-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">A. Identitas Institusi</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] mb-6">
                    <div>
                      <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Nama Perguruan Tinggi</p>
                      <p class="text-[15px] font-semibold text-[#1d293d]">{{ $namaPeserta }}</p>
                    </div>
                    <div>
                      <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Jenis Perguruan Tinggi</p>
                      <p class="text-[15px] font-semibold text-[#1d293d]">Negeri</p>
                    </div>
                  </div>
                  <div class="mb-6">
                    <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Visi</p>
                    <p class="text-[15px] font-medium text-[#45556c] leading-relaxed bg-[#f8fafc] p-4 rounded-[8px] border border-[#e2e8f0]">Menjadi pusat ilmu pengetahuan, teknologi, dan kebudayaan yang unggul dan berdaya saing...</p>
                  </div>
                  <div>
                    <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Misi</p>
                    <p class="text-[15px] font-medium text-[#45556c] leading-relaxed bg-[#f8fafc] p-4 rounded-[8px] border border-[#e2e8f0]">1. Menyelenggarakan pendidikan tinggi yang berkualitas.<br>2. Mengembangkan riset inovatif.</p>
                  </div>
                </div>

                {{-- Group B & C: Akademik & Mahasiswa --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1]">
                        <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">B. Akademik & SDM</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Jumlah Fakultas</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">14</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Program Studi</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">182</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Dosen</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">2,104</span>
                            </div>
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Tenaga Akademik</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">1,850</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1]">
                        <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">C. Kemahasiswaan & PIC</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Mahasiswa Aktif</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">48,000</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Ormawa & UKM</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">88</span>
                            </div>
                            <div class="mt-4 pt-2">
                                <span class="text-[12px] font-bold text-[#64748b] uppercase tracking-wider block mb-1">Kontak PIC</span>
                                <p class="text-[14px] font-semibold text-[#1d293d]">Budi Santoso, S.Kom., M.T. <span class="text-[#64748b] font-medium">(Kepala Pusat Data)</span></p>
                                <p class="text-[14px] text-blue-600 font-medium mt-0.5">budi.santoso@ui.ac.id | +62 812-3456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Group D & E: Demografi & Berkas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1]">
                        <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">D. Demografi Agama Mahasiswa</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Islam</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">42,000</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Kristen Protestan</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">3,500</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Katolik</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">1,200</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Hindu</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">800</span>
                            </div>
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Buddha & Konghucu</span>
                                <span class="text-[15px] text-[#1d293d] font-bold">500</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1]">
                        <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">E. Berkas Profil Pendukung</h3>
                        <div class="space-y-4">
                            <a href="#" class="flex items-center justify-between p-3 border border-[#cbd5e1] rounded-lg hover:border-[#1b5e20] hover:bg-[#f2fcf3] transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="image" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-[14px] font-semibold text-[#1d293d] group-hover:text-[#1b5e20]">Logo Institusi</span>
                                </div>
                                <i data-lucide="external-link" class="w-4 h-4 text-[#90a1b9] group-hover:text-[#1b5e20]"></i>
                            </a>
                            <a href="#" class="flex items-center justify-between p-3 border border-[#cbd5e1] rounded-lg hover:border-[#1b5e20] hover:bg-[#f2fcf3] transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-[14px] font-semibold text-[#1d293d] group-hover:text-[#1b5e20]">Profil Institusi (PDF)</span>
                                </div>
                                <i data-lucide="external-link" class="w-4 h-4 text-[#90a1b9] group-hover:text-[#1b5e20]"></i>
                            </a>
                            <a href="#" class="flex items-center justify-between p-3 border border-[#cbd5e1] rounded-lg hover:border-[#1b5e20] hover:bg-[#f2fcf3] transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-[14px] font-semibold text-[#1d293d] group-hover:text-[#1b5e20]">Struktur Organisasi (PDF)</span>
                                </div>
                                <i data-lucide="external-link" class="w-4 h-4 text-[#90a1b9] group-hover:text-[#1b5e20]"></i>
                            </a>
                            <a href="#" class="flex items-center justify-between p-3 border border-[#cbd5e1] rounded-lg hover:border-[#1b5e20] hover:bg-[#f2fcf3] transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-[14px] font-semibold text-[#1d293d] group-hover:text-[#1b5e20]">SK Tim Pemeringkatan (PDF)</span>
                                </div>
                                <i data-lucide="external-link" class="w-4 h-4 text-[#90a1b9] group-hover:text-[#1b5e20]"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- TAB: FORM PENILAIAN RUBRIK --}}
            <div x-show="activeTab === 'penilaian'" x-transition.opacity.duration.300ms class="space-y-[24px] md:space-y-[32px]">
              <template x-for="(categoryData, cIdx) in mockDatabase" :key="cIdx">
                <div class="space-y-[16px]">
                  {{-- Category Header --}}
                  <div class="flex items-center justify-between border-b-[2px] border-[#e2e8f0] pb-[8px] mb-[16px]">
                    <h2 class="text-[18px] font-bold text-[#1b5e20] uppercase" x-text="categoryData.category"></h2>
                    <span class="text-[14px] font-bold text-[#62748e] bg-white border border-[#e2e8f0] px-[12px] py-[4px] rounded-full shadow-sm" x-text="'Bobot: ' + categoryData.weight"></span>
                  </div>

                  {{-- Questions --}}
                  <template x-for="q in categoryData.questions" :key="q.id">
                    <div class="bg-white border border-[#cbd5e1] rounded-[12px] p-[20px] md:p-[28px] flex flex-col md:flex-row gap-[20px] md:gap-[32px] overflow-hidden mb-[16px]">
                      
                      {{-- Left Column: Question & Evidence --}}
                      <div class="flex-1 space-y-[16px]">
                        <div class="flex gap-[16px]">
                          <div class="w-[36px] h-[36px] rounded-full bg-[#f2fcf3] border border-[#1b5e20]/30 flex items-center justify-center font-bold text-[#1b5e20] text-[15px] shrink-0" x-text="q.code">
                          </div>
                          <div>
                            <h3 class="font-bold text-[#1d293d] text-[16px] leading-[24px]" x-text="q.title"></h3>
                            <p class="text-[14px] font-medium text-[#45556c] mt-[6px] leading-[22px]" x-text="q.description"></p>
                          </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-[8px] p-[16px] ml-[52px]">
                          <h4 class="text-[12px] font-bold text-amber-800 mb-[8px] uppercase tracking-[0.4px]">Syarat Bukti Valid:</h4>
                          <ul class="text-[13px] font-semibold text-amber-900/80 space-y-[6px]">
                            <template x-for="(req, rIdx) in q.evidenceRequirements" :key="rIdx">
                              <li class="flex gap-[6px] items-start">
                                <span class="mt-[2px] w-[4px] h-[4px] bg-amber-500 rounded-full shrink-0"></span>
                                <span class="leading-[18px]" x-text="req.replace(/^\d+\.\s*/, '')"></span>
                              </li>
                            </template>
                          </ul>
                          
                          <template x-if="q.type === 'multiple-choice'">
                            <div class="mt-[16px] pt-[12px] border-t border-amber-200/50">
                                <h4 class="text-[12px] font-bold text-amber-800 mb-[8px] uppercase tracking-[0.4px]">Daftar Pilihan Jawaban yang Tersedia:</h4>
                                <ul class="text-[13px] font-medium text-amber-900/80 space-y-[4px] list-decimal pl-[16px]">
                                  <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                    <li class="leading-[18px]" x-text="opt"></li>
                                  </template>
                                </ul>
                            </div>
                          </template>
                        </div>
                      </div>

                      {{-- Right Column: Answers & Scoring Form --}}
                      <div class="flex-1 flex flex-col space-y-[20px] bg-[#f8fafc] border border-[#e2e8f0] rounded-[8px] p-[20px]">
                        
                        {{-- Peserta's Answer --}}
                        <div>
                            <h4 class="text-[12px] font-bold text-[#64748b] uppercase tracking-[0.5px] mb-[6px]">Jawaban Klaim Peserta:</h4>
                            <div class="bg-white border border-[#cbd5e1] rounded-[8px] p-[12px] text-[#1d293d] font-bold text-[14px] flex items-start gap-2 shadow-sm">
                                <i data-lucide="check-circle-2" class="w-[18px] h-[18px] text-blue-600 shrink-0 mt-0.5"></i>
                                <span x-text="answers[q.id] || 'Belum diisi'"></span>
                            </div>
                        </div>
                        
                        {{-- Peserta's Link Evidence --}}
                        <div>
                            <h4 class="text-[12px] font-bold text-[#64748b] uppercase tracking-[0.5px] mb-[6px]">Tautan BUKTI / Dokumen:</h4>
                            <a :href="links[q.id]" target="_blank" class="inline-flex items-center justify-between w-full bg-white border border-[#cbd5e1] text-[#1b5e20] hover:border-[#1b5e20] hover:bg-[#f2fcf3] px-[16px] py-[12px] rounded-[8px] transition-colors group shadow-sm">
                                <span class="text-[14px] font-bold truncate flex-1" x-text="links[q.id]"></span>
                                <i data-lucide="external-link" class="w-[16px] h-[16px] text-[#90a1b9] group-hover:text-[#1b5e20] shrink-0 ml-[12px]"></i>
                            </a>
                        </div>

                        {{-- Reviewer Form Input --}}
                        <div class="pt-[16px] border-t border-[#cbd5e1] mt-auto relative">
                            
                            {{-- Input Score --}}
                            <div class="mb-[16px]">
                                <h4 class="text-[13px] font-bold text-[#1d293d] mb-[8px] flex items-center gap-[6px]">
                                    Berikan Score Final <span class="text-red-500">*</span>
                                </h4>
                                <div class="flex items-center gap-[12px]">
                                    <input
                                      type="number"
                                      min="0"
                                      max="5"
                                      placeholder="0-5"
                                      class="w-[80px] px-[12px] py-[10px] rounded-[8px] border-2 text-[18px] font-bold focus:outline-none focus:border-[#1b5e20] hover:border-[#1b5e20]/60 transition-colors text-center"
                                      :class="isDone ? 'bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8]' : 'border-[#cbd5e1] text-[#1b5e20] bg-white'"
                                      :disabled="isDone"
                                      x-model="reviewerScores[q.id]"
                                    />
                                    <span class="text-[13px] text-[#64748b] font-medium leading-tight max-w-[200px]">Skala 1 - 5. Ketik 0 jika jawaban/bukti tidak valid.</span>
                                </div>
                            </div>

                            {{-- Input Catatan --}}
                            <div>
                                <h4 class="text-[13px] font-bold text-[#1d293d] mb-[8px]">
                                    Catatan Penilai / Alasan:
                                </h4>
                                <textarea 
                                    rows="3"
                                    placeholder="Tuliskan alasan mengapa skor diberikan, atau hal yang kurang dari bukti validasi..."
                                    class="w-full text-[14px] p-[12px] rounded-[8px] border-2 focus:outline-none focus:border-[#1b5e20] hover:border-[#1b5e20]/60 transition-colors resize-y"
                                    :class="isDone ? 'bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8]' : 'border-[#cbd5e1] text-[#1d293d] bg-white'"
                                    :disabled="isDone"
                                    x-model="reviewerNotes[q.id]"
                                ></textarea>
                            </div>
                        </div>
                      </div>

                    </div>
                  </template>
                </div>
              </template>
            </div>
            
        </div>
      </div>

      {{-- Footer sticky area --}}
      <template x-if="!isDone">
          <div class="bg-white border-t border-[#e2e8f0] px-[20px] md:px-[40px] py-[16px] flex flex-col sm:flex-row justify-between items-center gap-[16px] shrink-0 z-10 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-[4px] sm:gap-[12px]">
                <div class="flex items-center gap-[8px] text-[13px] font-bold text-[#1d293d]">
                  <span class="w-[8px] h-[8px] rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                  Pastikan Seluruh Nilai Sudah Terisi
                </div>
                <span class="text-[12px] font-medium text-[#64748b]">Sistem auto-save (debounce) berlaku. Draft tersimpan otomatis.</span>
            </div>
            <button class="w-full sm:w-auto bg-[#1b5e20] hover:bg-[#15461c] text-white px-[32px] py-[12px] rounded-[8px] text-[14px] font-bold flex items-center justify-center gap-[8px] transition-colors shadow-sm focus:ring-4 focus:ring-[#1b5e20]/30 outline-none block">
              <i data-lucide="check-circle" class="w-[18px] h-[18px]"></i>
              Selesaikan Penilaian
            </button>
          </div>
      </template>
    </div>
</x-layouts.reviewer>
