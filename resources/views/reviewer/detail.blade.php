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
        isLocking: false,
        showLockConfirm: false,
        pesertaId: {{ $reqId }},
        
        // Data dari API
        Assessment: {},
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

        // Floating Drawer & Flags
        drawerOpen: false,
        flags: {},
        toggleFlag(questionId) {
            if (this.isDone) return;
            this.flags[questionId] = !this.flags[questionId];
            try { sessionStorage.setItem('reviewer_flags_' + this.pesertaId, JSON.stringify(this.flags)); } catch(e) {}
        },
        isFlagged(questionId) {
            return !!this.flags[questionId];
        },
        scrollToQuestion(qId) {
            const el = document.getElementById('q-' + qId);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                el.classList.add('ring-2', 'ring-[#1b5e20]', 'ring-offset-2');
                setTimeout(() => el.classList.remove('ring-2', 'ring-[#1b5e20]', 'ring-offset-2'), 1500);
            }
            this.drawerOpen = false;
        },
        fillStatus(qId) {
            const score = this.reviewerScores[qId];
            const note  = this.reviewerNotes[qId];
            const hasScore = (score !== null && score !== undefined && score !== '');
            const hasNote  = !!(note && String(note).trim() !== '');
            if (hasScore) return 2;       // hijau: skor sudah diisi
            if (hasNote)  return 1;       // kuning: baru catatan, belum skor
            return 0;                     // kosong: belum ada apa-apa
        },
        get allQuestions() {
            return this.rubrikData.flatMap(c => c.pertanyaan);
        },
        get totalAnswered() {
            return this.allQuestions.filter(q => this.fillStatus(q.id) === 2).length;
        },
        get totalFlagged() {
            return this.allQuestions.filter(q => this.isFlagged(q.id)).length;
        },
        
        async init() {
            try {
                const savedFlags = sessionStorage.getItem('reviewer_flags_' + this.pesertaId);
                if (savedFlags) this.flags = JSON.parse(savedFlags);
            } catch(e) {}
            const cacheKey = 'reviewer_detail_cache_' + this.pesertaId;
            try {
                const cached = sessionStorage.getItem(cacheKey);
                if (cached) {
                    this.applyData(JSON.parse(cached));
                    this.loading = false;
                    this.fetchData(); // background refresh
                } else {
                    await this.fetchData();
                }
            } catch(e) {
                await this.fetchData();
            }

            let timeout = null;
            const autoSave = () => {
                if (this.loading || this.isDone) return;
                this.isSaving = true;
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    await this.saveScores();
                    this.isSaving = false;
                    let d = new Date();
                    this.lastSaved = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                }, 1000);
            };

            this.$watch('reviewerScores', value => autoSave(), { deep: true });
            this.$watch('reviewerNotes', value => autoSave(), { deep: true });
        },

        async saveScores() {
            const scores = {};
            const notes  = {};
            for (const [qId, skor] of Object.entries(this.reviewerScores)) {
                if (skor !== null && skor !== '') scores[qId] = skor;
            }
            for (const [qId, note] of Object.entries(this.reviewerNotes)) {
                if (note && note.trim() !== '') notes[qId] = note;
            }
            try {
                await fetch(`/api/assessment/reviewer/tasks/${this.pesertaId}/save-scores`, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ scores, notes })
                });
            } catch (e) {
                console.error('Gagal menyimpan skor reviewer:', e);
            }
        },

        get submissionStatus() {
            const s = this.Assessment.status;
            const map = {
                'ACTIVE'      : { label: 'Belum Submit',     color: 'bg-slate-100 text-slate-600',  icon: 'clock' },
                'IN_PROGRESS' : { label: 'Dalam Pengerjaan', color: 'bg-blue-100 text-blue-700',    icon: 'pen-line' },
                'SUBMITTED'   : { label: 'Menunggu Review',  color: 'bg-amber-100 text-amber-700',  icon: 'hourglass' },
                'GRADED'      : { label: 'Sudah Dinilai',    color: 'bg-green-100 text-green-700',  icon: 'check-circle-2' },
                'REJECTED'    : { label: 'Ditolak',          color: 'bg-red-100 text-red-700',      icon: 'x-circle' },
            };
            return map[s] || { label: s || '...', color: 'bg-slate-100 text-slate-500', icon: 'info' };
        },

        async lockReview() {
            this.isLocking = true;
            this.showLockConfirm = false;
            try {
                await this.saveScores();
                const res = await fetch(`/api/assessment/reviewer/tasks/${this.pesertaId}/finalize`, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                const result = await res.json();
                if (res.ok && result.success) {
                    sessionStorage.removeItem('reviewer_detail_cache_' + this.pesertaId);
                    sessionStorage.removeItem('reviewer_tasks_cache');
                    sessionStorage.removeItem('reviewer_flags_' + this.pesertaId);
                    await this.fetchData();
                } else {
                    alert(result.message || 'Gagal memfinalisasi penilaian.');
                }
            } catch(e) {
                console.error(e);
                alert('Terjadi kesalahan jaringan.');
            } finally {
                this.isLocking = false;
            }
        },

        applyData(data) {
            this.Assessment    = data.Assessment    || {};
            this.institusi      = data.institusi      || {};
            this.profil_peserta = data.profil_peserta || {};
            this.rubrikData     = data.rubrik         || [];
            this.nama_pic       = data.nama_pic;
            this.jabatan_pic    = data.jabatan_pic;
            this.email_pic      = data.email_pic;
            this.no_hp_pic      = data.no_hp_pic;
            // isDone = true hanya jika sudah di-GRADED (reviewer sudah finalisasi)
            this.isDone = ['GRADED', 'REJECTED'].includes(this.Assessment.status);
            this.rubrikData.forEach(kategori => {
                kategori.pertanyaan.forEach(q => {
                    if (q.jawaban_peserta) {
                        this.answers[q.id] = q.jawaban_peserta.opsi_dipilih
                            ? q.jawaban_peserta.opsi_dipilih.keterangan
                            : q.jawaban_peserta.jawaban_teks;
                        this.links[q.id] = q.jawaban_peserta.tautan_bukti_drive;
                        if (q.jawaban_peserta.skor_validasi_reviewer !== null) {
                            this.reviewerScores[q.id] = q.jawaban_peserta.skor_validasi_reviewer;
                        }
                    }
                });
            });
        },

        async fetchData() {
            const cacheKey = 'reviewer_detail_cache_' + this.pesertaId;
            try {
                const response = await fetch(`/api/assessment/reviewer/tasks/detail/${this.pesertaId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (response.ok && result.success) {
                    try { sessionStorage.setItem(cacheKey, JSON.stringify(result.data)); } catch(e) {}
                    this.applyData(result.data);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.loading = false;
                this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
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
            return docs.logo_pt || docs.logo || this.institusi.logo_url || null;
        },

        parseEvidence(val) {
            if (Array.isArray(val)) return val;
            if (typeof val === 'string') return val.split(',').map(s => s.trim()).filter(s => s !== '');
            return val ? [val] : [];
        }
    }" class="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
        
      {{-- Loading State — konsisten dengan dashboard peserta --}}
      <div x-show="loading">
          <x-dashboard.loading
              title="Memuat Data Peserta..."
              caption="Sistem sedang menyiapkan data profil dan rubrik penilaian." />
      </div>

      {{-- Konten — pakai x-show agar tab switch instan tanpa re-render DOM --}}
      <div x-show="!loading" x-cloak class="flex-1 flex flex-col h-full">
      {{-- Page Header --}}
      <div class="bg-white border-b border-[#e2e8f0] px-[20px] md:px-[40px] pt-[20px] md:pt-[28px] shadow-sm">
        <a href="{{ route('reviewer.index') }}" class="inline-flex items-center gap-[6px] text-[#62748e] hover:text-[#1b5e20] text-[13px] font-semibold mb-[8px] transition-colors">
          <i data-lucide="arrow-left" class="w-[14px] h-[14px]"></i> Kembali ke Daftar Plotting
        </a>
        
        <div class="flex flex-col md:flex-row items-start justify-between md:items-end mb-[20px]">
            <div>
                <h1 class="font-bold text-[#1d293d] text-[20px] md:text-[26px] tracking-tight flex flex-col md:flex-row items-start md:items-center gap-[8px] md:gap-[12px]">
                    Menu Penilaian - <span x-text="institusi.nama_institusi"></span>
                    {{-- Dynamic Status Badge --}}
                    <span class="inline-flex items-center gap-[6px] px-[12px] py-[4px] rounded-full text-[13px] md:text-[14px] font-bold mt-1 md:mt-0"
                          :class="submissionStatus.color">
                        <i :data-lucide="submissionStatus.icon" class="w-[16px] h-[16px]"
                           x-init="$watch('submissionStatus', () => $nextTick(() => { if(typeof lucide !== 'undefined') lucide.createIcons(); }))"></i>
                        <span x-text="submissionStatus.label"></span>
                    </span>
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
                    <div :id="'q-' + q.id" class="relative bg-white border rounded-[12px] p-[20px] md:p-[28px] flex flex-col md:flex-row gap-[20px] md:gap-[32px] overflow-hidden mb-[16px] transition-all duration-300" :class="isFlagged(q.id) ? 'border-red-500 ring-1 ring-red-100' : 'border-[#cbd5e1]'">
                      
                      {{-- ===== 🔖 Bookmark Flag ===== --}}
                      <button type="button"
                          @click.stop="toggleFlag(q.id)"
                          :disabled="isDone"
                          :title="isDone ? 'Tidak dapat diubah' : (isFlagged(q.id) ? 'Hapus flag' : 'Tandai pertanyaan ini')"
                          class="absolute top-0 right-4 z-10 focus:outline-none"
                          :class="isDone ? 'cursor-not-allowed opacity-60' : ''"
                          style="width: 24px;">
                          <span class="block w-full transition-all duration-300"
                              :style="isFlagged(q.id)
                                  ? 'height:35px; background:#ef4444; clip-path:polygon(0 0,100% 0,100% 100%,50% 80%,0 100%); box-shadow:0 6px 14px rgba(239,68,68,0.45);'
                                  : 'height:20px; background:#cbd5e1; clip-path:polygon(0 0,100% 0,100% 100%,50% 75%,0 100%); box-shadow:none;'">
                          </span>
                      </button>

                      {{-- Left Column: Question & Evidence --}}
                      <div class="flex-1 space-y-[16px]">
                        <div class="flex gap-[16px]">
                          <div class="w-[36px] h-[36px] rounded-full bg-[#f2fcf3] border border-[#1b5e20]/30 flex items-center justify-center font-bold text-[#1b5e20] text-[15px] shrink-0" x-text="q.kode_pertanyaan">
                          </div>
                          <div>
                            <h3 class="font-bold text-[#1d293d] text-[16px] leading-[24px]" x-text="q.teks_pertanyaan"></h3>
                          </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-[8px] p-[16px] ml-[52px]">
                          <h4 class="text-[12px] font-bold text-amber-800 mb-[8px] uppercase tracking-[0.4px]">Syarat Bukti Valid:</h4>
                          <ul class="text-[13px] font-semibold text-amber-900/80 space-y-[6px]">
                            <template x-for="(req, rIdx) in parseEvidence(q.kebutuhan_bukti)" :key="rIdx">
                              <li class="flex gap-[6px] items-start">
                                <span class="mt-[2px] w-[4px] h-[4px] bg-amber-500 rounded-full shrink-0"></span>
                                <span class="leading-[18px]" x-text="req"></span>
                              </li>
                            </template>
                          </ul>
                          
                          <template x-if="q.opsi_jawaban && q.opsi_jawaban.length > 0">
                            <div class="mt-[16px] pt-[12px] border-t border-amber-200/50">
                                <h4 class="text-[12px] font-bold text-amber-800 mb-[8px] uppercase tracking-[0.4px]">Daftar Panduan / Skor:</h4>
                                <ul class="text-[13px] font-medium text-amber-900/80 space-y-[4px]">
                                  <template x-for="(opt, oIdx) in q.opsi_jawaban" :key="oIdx">
                                    <li class="leading-[18px] flex gap-[6px] items-start">
                                      <span class="shrink-0 mt-[2px] inline-flex items-center justify-center min-w-[44px] px-[6px] py-[1px] rounded-full text-[11px] font-bold bg-amber-200 text-amber-900"
                                            x-text="(opt.value !== null && opt.value !== undefined ? opt.value : opt.opsi_jawaban) + ' Poin'"></span>
                                      <span x-text="opt.keterangan || opt.opsi_jawaban || '-'"></span>
                                    </li>
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
                            <a :href="links[q.id] || '#'" target="_blank"
                               :title="links[q.id] || 'Tidak ada tautan'"
                               class="inline-flex items-center gap-[10px] w-full min-w-0 bg-white border border-[#cbd5e1] text-[#1b5e20] hover:border-[#1b5e20] hover:bg-[#f2fcf3] px-[16px] py-[12px] rounded-[8px] transition-colors group shadow-sm overflow-hidden">
                                <i data-lucide="link-2" class="w-[15px] h-[15px] text-[#90a1b9] group-hover:text-[#1b5e20] shrink-0"></i>
                                <span class="text-[13px] font-semibold truncate flex-1 min-w-0"
                                      x-text="links[q.id] ? (links[q.id].length > 55 ? links[q.id].substring(0, 55) + '...' : links[q.id]) : 'Tidak ada tautan'"></span>
                                <i data-lucide="external-link" class="w-[14px] h-[14px] text-[#90a1b9] group-hover:text-[#1b5e20] shrink-0"></i>
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
                                      :max="5"
                                      placeholder="0-5"
                                      class="w-[80px] px-[12px] py-[10px] rounded-[8px] border-2 text-[18px] font-bold focus:outline-none focus:border-[#1b5e20] hover:border-[#1b5e20]/60 transition-colors text-center"
                                      :class="isDone ? 'bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8]' : 'border-[#cbd5e1] text-[#1b5e20] bg-white'"
                                      :disabled="isDone"
                                      x-model="reviewerScores[q.id]"
                                      @input="if(reviewerScores[q.id] < 0) reviewerScores[q.id] = 0; if(reviewerScores[q.id] > 5) reviewerScores[q.id] = 5;"
                                    />
                                    <span class="text-[13px] text-[#64748b] font-medium leading-tight max-w-[200px]">Maks 5. Ketik 0 jika bukti tidak valid.</span>
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
      <div x-show="!isDone" class="bg-white border-t border-[#e2e8f0] px-[20px] md:px-[40px] py-[16px] flex flex-col sm:flex-row justify-between items-center gap-[16px] shrink-0 z-10 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-[4px] sm:gap-[12px]">
                <div class="flex items-center gap-[8px] text-[13px] font-bold text-[#1d293d]">
                  <span class="w-[8px] h-[8px] rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                  Pastikan Seluruh Nilai Sudah Terisi Sebelum Finalisasi
                </div>
                <span class="text-[12px] font-medium text-[#64748b]">Klik Simpan Draft dulu, lalu Finalisasi jika sudah yakin.</span>
            </div>
            <div class="flex items-center gap-[10px]">
                {{-- Simpan Draft --}}
                <button
                  @click="await saveScores(); lastSaved = (new Date()).getHours().toString().padStart(2,'0') + ':' + (new Date()).getMinutes().toString().padStart(2,'0');"
                  class="w-full sm:w-auto border-2 border-[#1b5e20] text-[#1b5e20] hover:bg-[#f0fdf4] px-[20px] py-[10px] rounded-[8px] text-[14px] font-bold flex items-center justify-center gap-[8px] transition-colors focus:outline-none">
                  <i data-lucide="save" class="w-[16px] h-[16px]"></i>
                  Simpan Draft
                </button>
                {{-- Finalisasi Penilaian --}}
                <button
                  @click="showLockConfirm = true"
                  :disabled="isLocking"
                  class="w-full sm:w-auto bg-[#1b5e20] hover:bg-[#15461c] disabled:opacity-60 text-white px-[24px] py-[10px] rounded-[8px] text-[14px] font-bold flex items-center justify-center gap-[8px] transition-colors shadow-sm focus:ring-4 focus:ring-[#1b5e20]/30 outline-none">
                  <i data-lucide="lock" class="w-[16px] h-[16px]"></i>
                  <span x-text="isLocking ? 'Menyimpan...' : 'Finalisasi Penilaian'"></span>
                </button>
            </div>
      </div>

      {{-- Lock Confirmation Modal --}}
      <div x-show="showLockConfirm" x-cloak
           class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 px-4"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0">
          <div class="bg-white rounded-[16px] shadow-2xl w-full max-w-[420px] p-[28px] border border-[#e2e8f0]">
              <div class="flex items-center gap-3 mb-[16px]">
                  <div class="w-[44px] h-[44px] rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                      <i data-lucide="alert-triangle" class="w-[22px] h-[22px] text-amber-600"></i>
                  </div>
                  <div>
                      <h3 class="font-bold text-[#1d293d] text-[16px]">Finalisasi Penilaian?</h3>
                      <p class="text-[13px] text-[#64748b] mt-[2px]">Tindakan ini tidak dapat dibatalkan.</p>
                  </div>
              </div>
              <p class="text-[14px] text-[#45556c] leading-relaxed mb-[20px]">
                  Setelah Anda mengkonfirmasi, status peserta akan berubah menjadi <strong class="text-green-700">GRADED</strong> dan peserta dapat melihat hasil penilaian final dari Anda. Pastikan semua skor sudah benar.
              </p>
              <div class="flex gap-[10px] justify-end">
                  <button @click="showLockConfirm = false"
                          class="px-[20px] py-[10px] rounded-[8px] border-2 border-slate-200 text-[14px] font-semibold text-slate-600 hover:border-slate-300 transition-colors">
                      Batal
                  </button>
                  <button @click="lockReview()"
                          class="px-[20px] py-[10px] rounded-[8px] bg-[#1b5e20] text-white text-[14px] font-bold hover:bg-[#15461c] transition-colors flex items-center gap-2">
                      <i data-lucide="check" class="w-[16px] h-[16px]"></i>
                      Ya, Finalisasi
                  </button>
              </div>
          </div>
      </div>

      {{-- ================================================================== --}}
      {{-- 🧭 FLOATING QUIZ NAVIGATION DRAWER                                --}}
      {{-- ================================================================== --}}
      <div x-show="!loading" style="display:none;" x-cloak>
          {{-- Toggle Arrow Button --}}
          <button
              type="button"
              @click="drawerOpen = !drawerOpen"
              class="fixed right-0 z-40 bg-[#1b5e20] text-white shadow-lg flex items-center justify-center"
              :style="{
                  top: (typeof showBar !== 'undefined' && showBar) ? 'calc(120px + ((100vh - 120px) / 2) - 32px)' : 'calc(50vh - 32px)',
                  width: '28px',
                  height: '64px',
                  borderRadius: '8px 0 0 8px',
                  transition: 'top 0.3s ease'
              }"
              :title="drawerOpen ? 'Tutup Navigator' : 'Buka Navigator Soal'">
              <svg xmlns="http://www.w3.org/2000/svg"
                   class="w-4 h-4 transition-transform duration-300"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="15 18 9 12 15 6"/>
              </svg>
          </button>

          {{-- Drawer Backdrop --}}
          <div
              x-show="drawerOpen"
              x-transition.opacity.duration.200ms
              @click="drawerOpen = false"
              class="fixed inset-0 bg-black/20 z-30"
              style="display:none;">
          </div>

          {{-- Drawer Panel --}}
          <div
              x-show="drawerOpen"
              x-transition:enter="transition ease-out duration-250"
              x-transition:enter-start="translate-x-full"
              x-transition:enter-end="translate-x-0"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="translate-x-0"
              x-transition:leave-end="translate-x-full"
              class="fixed right-0 w-[280px] bg-white shadow-2xl z-40 flex flex-col"
              :style="{
                  top: (typeof showBar !== 'undefined' && showBar) ? '120px' : '0px',
                  height: (typeof showBar !== 'undefined' && showBar) ? 'calc(100vh - 120px)' : '100vh',
                  transition: 'top 0.3s ease, height 0.3s ease'
              }">
              
              {{-- Drawer Header --}}
              <div class="px-4 py-4 border-b border-[#e0e0e0] flex items-center justify-between shrink-0">
                  <div>
                      <h3 class="font-bold text-[#1d293d] text-[13px] uppercase tracking-wide">Navigator Penilaian</h3>
                      <p class="text-[11px] text-[#62748e] mt-0.5">
                          <span class="font-semibold text-[#1b5e20]" x-text="totalAnswered"></span>
                          / <span x-text="allQuestions.length"></span> dinilai
                          <template x-if="totalFlagged > 0">
                              <span class="ml-2 text-red-500 font-semibold">
                                  · <span x-text="totalFlagged"></span> flag
                              </span>
                          </template>
                      </p>
                  </div>
                  <button type="button" @click="drawerOpen = false" class="text-[#90a1b9] hover:text-[#45556c] p-1 rounded">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                      </svg>
                  </button>
              </div>

              {{-- Legend --}}
              <div class="px-4 pt-3 pb-2 flex flex-wrap items-center gap-3 text-[10px] font-medium text-[#62748e] shrink-0">
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#1b5e20]"></span> Dinilai</span>
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-yellow-400"></span> Sebagian</span>
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#e0e0e0]"></span> Kosong</span>
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-red-500"></span> Flag</span>
              </div>

              {{-- Question Grid --}}
              <div class="flex-1 overflow-y-auto px-4 py-2 space-y-5 pb-8">
                  <template x-for="(catData, ci) in rubrikData" :key="ci">
                      <div>
                          <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider mb-2" x-text="catData.kategori"></p>
                          <div class="flex flex-wrap gap-1.5">
                              <template x-for="q in catData.pertanyaan" :key="q.id">
                                  <button
                                      type="button"
                                      @click="scrollToQuestion(q.id)"
                                      :title="'Indikator ' + q.kode_pertanyaan + (isFlagged(q.id) ? ' (Flag)' : '') + (fillStatus(q.id) === 2 ? ' ✓' : fillStatus(q.id) === 1 ? ' (sebagian)' : '')"
                                      class="relative w-9 h-9 rounded text-[11px] font-bold transition-all duration-150 focus:outline-none hover:scale-110 hover:shadow-md overflow-hidden"
                                       :class="isFlagged(q.id) ? 'text-white' : fillStatus(q.id) === 2 ? 'text-white' : fillStatus(q.id) === 1 ? 'text-[#1d293d]' : 'text-[#62748e]'"
                                      :style="isFlagged(q.id) ? 'background:#ef4444;' : fillStatus(q.id) === 2 ? 'background:#1b5e20;' : fillStatus(q.id) === 1 ? 'background:#facc15;' : 'background:#e0e0e0;'"
                                      x-text="q.kode_pertanyaan">
                                  </button>
                              </template>
                          </div>
                      </div>
                  </template>
              </div>
          </div>
      </div>
      </div>{{-- end !loading --}}
    </div>
</x-layouts.reviewer>
