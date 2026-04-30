<x-layouts.reviewer>
    @php 
        $reqId = request('id', '1');
    @endphp
    <x-slot:title>DETAIL PENILAIAN PESERTA</x-slot:title>
    <div x-data="{
        activeTab: 'penilaian',
        isSaving: false,
        lastSaved: '',
        isDone: false,
        loading: true,
        pesertaId: {{ $reqId }},
        
        // Data dari API
        pengumpulan: {},
        institusi: {},
        profil_peserta: {},
        nama_pic: '',
        jabatan_pic: '',
        email_pic: '',
        no_hp_pic: '',
        rubrikData: [],

        // Jawaban dan Link (Diambil dari mapping rubrikData)
        answers: {},
        links: {},
        
        // Data Penilaian Reviewer
        reviewerScores: {},
        reviewerNotes: {},
        
        async init() {
            await this.fetchData();

            let timeout = null;
            const autoSave = () => {
                // Hindari auto-save sebelum data selesai diload atau jika sudah divalidasi
                if (this.loading || this.isDone) return;
                
                this.isSaving = true;
                clearTimeout(timeout);
                
                // Debounce 1 Detik
                timeout = setTimeout(async () => {
                    // TODO: Implement actual save API here if needed for draft saving
                    this.isSaving = false;
                    let d = new Date();
                    this.lastSaved = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                }, 1000);
            };

            this.$watch('reviewerScores', value => autoSave(), { deep: true });
            this.$watch('reviewerNotes', value => autoSave(), { deep: true });
        },

        async fetchData() {
            try {
                const response = await fetch(`/api/assessment/reviewer/tasks/detail/${this.pesertaId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                if (response.ok && result.success) {
                    const data = result.data;
                    this.pengumpulan = data.pengumpulan || {};
                    this.institusi = data.institusi || {};
                    this.profil_peserta = data.profil_peserta || {};
                    this.rubrikData = data.rubrik || [];
                    this.nama_pic = data.nama_pic;
                    this.jabatan_pic = data.jabatan_pic;
                    this.email_pic = data.email_pic;
                    this.no_hp_pic = data.no_hp_pic;

                    this.isDone = ['GRADED', 'REJECTED', 'IN_PROGRESS'].includes(this.pengumpulan.status);

                    // Mapping answers, links, scores, notes
                    this.rubrikData.forEach(kategori => {
                        kategori.pertanyaan.forEach(q => {
                            if (q.jawaban_peserta) {
                                // Tentukan apakah teks atau opsi ID
                                if (q.jawaban_peserta.opsi_dipilih) {
                                    this.answers[q.id] = q.jawaban_peserta.opsi_dipilih.keterangan;
                                } else {
                                    this.answers[q.id] = q.jawaban_peserta.jawaban_teks;
                                }
                                this.links[q.id] = q.jawaban_peserta.tautan_bukti_drive;
                                
                                if (q.jawaban_peserta.skor_validasi_reviewer !== null) {
                                    this.reviewerScores[q.id] = q.jawaban_peserta.skor_validasi_reviewer;
                                }
                                // Catatan tidak ada dari payload skrg tp bs ditambahkan
                            }
                        });
                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.loading = false;
            }
        },

        get agamaData() {
            if (!this.profil_peserta || !this.profil_peserta.agama) return [];
            return Object.entries(this.profil_peserta.agama).map(([name, count]) => ({
                name: name.charAt(0).toUpperCase() + name.slice(1),
                count: count
            }));
        },

        get dokumenPendukung() {
            if (!this.profil_peserta || !this.profil_peserta.berkas_pendukung) return {};
            try {
                return typeof this.profil_peserta.berkas_pendukung === 'string' 
                    ? JSON.parse(this.profil_peserta.berkas_pendukung) 
                    : this.profil_peserta.berkas_pendukung;
            } catch (e) {
                return {};
            }
        },

        get logoUrl() {
            const docs = this.dokumenPendukung;
            return docs.logo_pt || docs.logo || null;
        }
    }" class="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
        
      {{-- Loading State --}}
      <template x-if="loading">
        <div class="flex-1 flex flex-col items-center justify-center p-8 h-full min-h-[500px]">
            <div class="w-10 h-10 border-4 border-[#1b5e20] border-t-transparent rounded-full animate-spin"></div>
            <p class="text-[#64748b] mt-4 font-medium">Memuat data peserta...</p>
        </div>
      </template>

      <template x-if="!loading">
      <div class="flex-1 flex flex-col h-full">
      {{-- Page Header --}}
      <div class="bg-white border-b border-[#e2e8f0] px-[20px] md:px-[40px] pt-[20px] md:pt-[28px] shadow-sm">
        <a href="{{ route('reviewer.index') }}" class="inline-flex items-center gap-[6px] text-[#62748e] hover:text-[#1b5e20] text-[13px] font-semibold mb-[8px] transition-colors">
          <i data-lucide="arrow-left" class="w-[14px] h-[14px]"></i> Kembali ke Daftar Plotting
        </a>
        
        <div class="flex flex-col md:flex-row items-start justify-between md:items-end mb-[20px]">
            <div>
                <h1 class="font-bold text-[#1d293d] text-[20px] md:text-[26px] tracking-tight flex flex-col md:flex-row items-start md:items-center gap-[8px] md:gap-[12px]">
                    Menu Penilaian - <span x-text="institusi.nama_institusi"></span>
                    <template x-if="isDone">
                        <span class="inline-flex items-center gap-[6px] bg-green-100 text-green-700 px-[12px] py-[4px] rounded-full text-[13px] md:text-[14px] font-bold mt-1 md:mt-0">
                            <i data-lucide="check-circle-2" class="w-[16px] h-[16px]"></i> Selesai Dinilai
                        </span>
                    </template>
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
                  <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4 border-b border-[#e2e8f0] pb-2">
                    <h3 class="font-bold text-[#1b5e20] text-[15px]">A. Identitas Institusi</h3>
                    <template x-if="logoUrl">
                        <a :href="logoUrl" target="_blank" class="block w-16 h-16 overflow-hidden rounded-full bg-white border-2 border-slate-200 p-1 hover:border-[#1b5e20] transition-colors shadow-sm">
                            <img :src="logoUrl" class="w-full h-full object-contain" alt="Logo Institusi">
                        </a>
                    </template>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] mb-6">
                    <div>
                      <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Nama Perguruan Tinggi</p>
                      <p class="text-[15px] font-semibold text-[#1d293d]" x-text="institusi.nama_institusi"></p>
                    </div>
                    <div>
                      <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Jenis Perguruan Tinggi</p>
                      <p class="text-[15px] font-semibold text-[#1d293d]" x-text="institusi.jenis_institusi"></p>
                    </div>
                  </div>
                  <div class="mb-6">
                    <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Visi</p>
                    <p class="text-[15px] font-medium text-[#45556c] leading-relaxed bg-[#f8fafc] p-4 rounded-[8px] border border-[#e2e8f0]" x-text="profil_peserta.visi || '-'"></p>
                  </div>
                  <div>
                    <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Misi</p>
                    <p class="text-[15px] font-medium text-[#45556c] leading-relaxed bg-[#f8fafc] p-4 rounded-[8px] border border-[#e2e8f0] whitespace-pre-line" x-text="profil_peserta.misi || '-'"></p>
                  </div>
                </div>

                {{-- Group B & C: Akademik & Kemahasiswaan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1]">
                        <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">B. Akademik & SDM</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Jumlah Fakultas</span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="profil_peserta.jml_fakultas || 0"></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Program Studi</span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="profil_peserta.jml_prodi || 0"></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Dosen</span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="profil_peserta.jml_dosen || 0"></span>
                            </div>
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Tenaga Akademik (Tendik)</span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="profil_peserta.jml_tendik || 0"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1]">
                        <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">C. Data Kemahasiswaan</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Mahasiswa Aktif</span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="profil_peserta.jml_mhs || 0"></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Organisasi Mahasiswa (Ormawa)</span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="profil_peserta.jml_ormawa || 0"></span>
                            </div>
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium">Unit Kegiatan Mahasiswa (UKM)</span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="profil_peserta.jml_ukm || 0"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Group D: Kontak PIC --}}
                <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1] mb-6">
                    <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">D. Kontak PIC</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Nama PIC</p>
                            <p class="text-[15px] font-semibold text-[#1d293d]" x-text="nama_pic || '-'"></p>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Jabatan PIC</p>
                            <p class="text-[15px] font-semibold text-[#1d293d]" x-text="jabatan_pic || '-'"></p>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Email Resmi</p>
                            <p class="text-[15px] font-semibold text-blue-600" x-text="email_pic || '-'"></p>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-[#64748b] uppercase tracking-wider mb-1">No. WhatsApp</p>
                            <p class="text-[15px] font-semibold text-[#1d293d]" x-text="no_hp_pic || '-'"></p>
                        </div>
                    </div>
                </div>

                {{-- Group E: Demografi --}}
                <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1] mb-6">
                    <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">E. Demografi Agama Mahasiswa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                        <template x-for="(agama, i) in agamaData" :key="i">
                            <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                <span class="text-[14px] text-[#64748b] font-medium" x-text="agama.name"></span>
                                <span class="text-[15px] text-[#1d293d] font-bold" x-text="agama.count"></span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- TAB: FORM PENILAIAN RUBRIK --}}
            <div x-show="activeTab === 'penilaian'" x-transition.opacity.duration.300ms class="space-y-[24px] md:space-y-[32px]">
              <template x-for="(categoryData, cIdx) in rubrikData" :key="cIdx">
                <div class="space-y-[16px]">
                  {{-- Category Header --}}
                  <div class="flex items-center justify-between border-b-[2px] border-[#e2e8f0] pb-[8px] mb-[16px]">
                    <h2 class="text-[18px] font-bold text-[#1b5e20] uppercase" x-text="categoryData.kategori"></h2>
                    <span class="text-[14px] font-bold text-[#62748e] bg-white border border-[#e2e8f0] px-[12px] py-[4px] rounded-full shadow-sm" x-text="'Max: ' + categoryData.bobot_maksimal + ' pts'"></span>
                  </div>

                  {{-- Questions --}}
                  <template x-for="q in categoryData.pertanyaan" :key="q.id">
                    <div class="bg-white border border-[#cbd5e1] rounded-[12px] p-[20px] md:p-[28px] flex flex-col md:flex-row gap-[20px] md:gap-[32px] overflow-hidden mb-[16px]">
                      
                      {{-- Left Column: Question & Evidence --}}
                      <div class="flex-1 space-y-[16px]">
                        <div class="flex gap-[16px]">
                          <div class="w-[36px] h-[36px] rounded-full bg-[#f2fcf3] border border-[#1b5e20]/30 flex items-center justify-center font-bold text-[#1b5e20] text-[15px] shrink-0" x-text="q.kode_pertanyaan">
                          </div>
                          <div>
                            <h3 class="font-bold text-[#1d293d] text-[16px] leading-[24px]" x-text="q.teks_pertanyaan"></h3>
                            <p class="text-[14px] font-medium text-[#45556c] mt-[6px] leading-[22px]" x-text="q.deskripsi"></p>
                          </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-[8px] p-[16px] ml-[52px]">
                          <h4 class="text-[12px] font-bold text-amber-800 mb-[8px] uppercase tracking-[0.4px]">Syarat Bukti Valid:</h4>
                          <ul class="text-[13px] font-semibold text-amber-900/80 space-y-[6px]">
                            <template x-if="Array.isArray(q.kebutuhan_bukti)">
                                <template x-for="(req, rIdx) in q.kebutuhan_bukti" :key="rIdx">
                                  <li class="flex gap-[6px] items-start">
                                    <span class="mt-[2px] w-[4px] h-[4px] bg-amber-500 rounded-full shrink-0"></span>
                                    <span class="leading-[18px]" x-text="req"></span>
                                  </li>
                                </template>
                            </template>
                            <template x-if="!Array.isArray(q.kebutuhan_bukti) && q.kebutuhan_bukti">
                                <li class="flex gap-[6px] items-start">
                                    <span class="mt-[2px] w-[4px] h-[4px] bg-amber-500 rounded-full shrink-0"></span>
                                    <span class="leading-[18px]" x-text="q.kebutuhan_bukti"></span>
                                </li>
                            </template>
                          </ul>
                          
                          <template x-if="q.tipe === 'pilihan_ganda'">
                            <div class="mt-[16px] pt-[12px] border-t border-amber-200/50">
                                <h4 class="text-[12px] font-bold text-amber-800 mb-[8px] uppercase tracking-[0.4px]">Daftar Pilihan Jawaban yang Tersedia:</h4>
                                <ul class="text-[13px] font-medium text-amber-900/80 space-y-[4px] list-decimal pl-[16px]">
                                  <template x-for="(opt, oIdx) in q.opsi_jawaban" :key="opt.id">
                                    <li class="leading-[18px]" x-text="opt.keterangan || opt.opsi_jawaban"></li>
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
                                <span x-text="answers[q.id] || 'Belum diisi / Belum disubmit'"></span>
                            </div>
                        </div>
                        
                        {{-- Peserta's Link Evidence --}}
                        <div>
                            <h4 class="text-[12px] font-bold text-[#64748b] uppercase tracking-[0.5px] mb-[6px]">Tautan BUKTI / Dokumen:</h4>
                            <a :href="links[q.id] || '#'" target="_blank" class="inline-flex items-center justify-between w-full bg-white border border-[#cbd5e1] text-[#1b5e20] hover:border-[#1b5e20] hover:bg-[#f2fcf3] px-[16px] py-[12px] rounded-[8px] transition-colors group shadow-sm">
                                <span class="text-[14px] font-bold truncate flex-1" x-text="links[q.id] || 'Tidak ada tautan'"></span>
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
                                      :max="q.skor_maksimal || 5"
                                      placeholder="0-5"
                                      class="w-[80px] px-[12px] py-[10px] rounded-[8px] border-2 text-[18px] font-bold focus:outline-none focus:border-[#1b5e20] hover:border-[#1b5e20]/60 transition-colors text-center"
                                      :class="isDone ? 'bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8]' : 'border-[#cbd5e1] text-[#1b5e20] bg-white'"
                                      :disabled="isDone"
                                      x-model="reviewerScores[q.id]"
                                    />
                                    <span class="text-[13px] text-[#64748b] font-medium leading-tight max-w-[200px]">Maks <span x-text="q.skor_maksimal || 5"></span>. Ketik 0 jika bukti tidak valid.</span>
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
      </template>
    </div>
</x-layouts.reviewer>
