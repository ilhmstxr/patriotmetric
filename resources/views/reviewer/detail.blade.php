<x-dynamic-component :component="isset($adminReadonly) && $adminReadonly ? 'layouts.app' : 'layouts.reviewer'" :hideNav="isset($adminReadonly) && $adminReadonly" :hideFooter="isset($adminReadonly) && $adminReadonly">
    @php
        $reqId = isset($id) ? $id : request('id', '1');
    @endphp
    <x-slot:title>DETAIL PENILAIAN PESERTA</x-slot:title>
    <div x-data="{
        activeTab: 'penilaian',
        isSaving: false,
        lastSaved: '',
        isDone: false,
        adminReadonly: {{ isset($adminReadonly) && $adminReadonly ? 'true' : 'false' }},
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
        unlockedQuestions: {},

        // Floating Drawer & Flags
        drawerOpen: false,
        flags: {},
        saveStatus: {}, // 'saving' | 'saved' | '' per question
      
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

            // No global $watch — save is triggered per question via @blur
        },

        async saveQuestionScore(questionId) {
            if (this.loading || this.isDone) return;
            this.isSaving = true;
            this.saveStatus[questionId] = 'saving';
            const scores = {};
            const notes = {};
            const skor = this.reviewerScores[questionId];
            const note = this.reviewerNotes[questionId];
            if (skor !== null && skor !== undefined && skor !== '') scores[questionId] = skor;
            if (note && note.trim() !== '') notes[questionId] = note;
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
                this.saveStatus[questionId] = 'saved';
                setTimeout(() => { if (this.saveStatus[questionId] === 'saved') this.saveStatus[questionId] = ''; }, 2000);
            } catch (e) {
                console.error('Gagal menyimpan skor:', e);
                this.saveStatus[questionId] = '';
            } finally {
                this.isSaving = false;
                let d = new Date();
                this.lastSaved = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
            }
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
                'ACTIVE'      : { label: 'Belum Submit',     color: 'bg-slate-100 text-slate-600',       icon: 'clock' },
                'IN_PROGRESS' : { label: 'Dalam Pengerjaan', color: 'bg-indigo-100 text-indigo-700',     icon: 'pen-line' },
                'SUBMITTED'   : { label: 'Menunggu Review',  color: 'bg-violet-100 text-violet-700',     icon: 'hourglass' },
                'GRADED'      : { label: 'Sudah Dinilai',    color: 'bg-teal-100 text-teal-700',         icon: 'check-circle-2' },
                'PUBLISHED'   : { label: 'Published',        color: 'bg-sky-100 text-sky-700',           icon: 'globe' },
                'REJECTED'    : { label: 'Ditolak',          color: 'bg-rose-100 text-rose-700',         icon: 'x-circle' },
            };
            return map[s] || { label: s || '...', color: 'bg-slate-100 text-slate-500', icon: 'info' };
        },

        validateBeforeFinalize() {
            const unanswered = this.allQuestions.filter(q => {
                const score = this.reviewerScores[q.id];
                return score === null || score === undefined || score === '';
            });
            if (unanswered.length > 0) {
                const firstQ = unanswered[0];
                const el = document.getElementById('q-' + firstQ.id);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.classList.add('ring-2', 'ring-red-400', 'ring-offset-2');
                    setTimeout(() => el.classList.remove('ring-2', 'ring-red-400', 'ring-offset-2'), 3000);
                }
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Lengkap',
                    text: 'Masih ada ' + unanswered.length + ' pertanyaan yang belum dinilai. Silakan lengkapi terlebih dahulu.',
                    confirmButtonColor: '#1b5e20'
                });
                return;
            }
            this.showLockConfirm = true;
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
                    this.flags = {};
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
            // isDone = true jika status GRADED, PUBLISHED, atau REJECTED
            this.isDone = ['GRADED', 'PUBLISHED', 'REJECTED'].includes(this.Assessment.status);
            if (this.adminReadonly) this.isDone = true;
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
            const apiUrl = this.adminReadonly
                ? `/admin/api/assessment/${this.pesertaId}`
                : `/api/assessment/reviewer/tasks/detail/${this.pesertaId}`;
            try {
                const headers = { 'Accept': 'application/json' };
                if (!this.adminReadonly) {
                    headers['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token');
                }
                const response = await fetch(apiUrl, { headers });
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
            const labels = {
                'islam': 'Islam',
                'kristen': 'Kristen',
                'katolik': 'Katolik',
                'hindu': 'Hindu',
                'buddha': 'Buddha',
                'konghucu': 'Konghucu',
                'kepercayaan terhadap tuhan yang maha esa': 'Kepercayaan Terhadap Tuhan YME'
            };
            return Object.entries(this.profil_peserta.agama).map(([name, count]) => ({
                name: labels[name.toLowerCase()] || (name.charAt(0).toUpperCase() + name.slice(1)),
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
        },

        parseAnswerJson(val) {
            if (!val) return null;
            if (typeof val === 'object') return val;
            try {
                const parsed = JSON.parse(val);
                return (parsed && typeof parsed === 'object') ? parsed : null;
            } catch (e) {
                return null;
            }
        },
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
        <template x-if="!adminReadonly">
            <a href="{{ route('reviewer.dashboard') }}" wire:navigate class="inline-flex items-center gap-[6px] text-[#62748e] hover:text-[#1b5e20] text-[13px] font-semibold mb-[8px] transition-colors">
              <i data-lucide="arrow-left" class="w-[14px] h-[14px]"></i> Kembali ke Daftar Plotting
            </a>
        </template>
        <template x-if="adminReadonly">
            <a href="/admin/assessments" class="inline-flex items-center gap-[6px] text-[#62748e] hover:text-[#1b5e20] text-[13px] font-semibold mb-[8px] transition-colors">
              <i data-lucide="arrow-left" class="w-[14px] h-[14px]"></i> Kembali ke Admin Panel
            </a>
        </template>
        
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

                {{-- Skor Overview --}}
                <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1] mb-6">
                  <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">Ringkasan Skor</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-[24px]">
                    <div class="bg-[#f8fafc] p-4 rounded-[8px] border border-[#e2e8f0] text-center">
                        <p class="text-[12px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Status</p>
                        <span class="inline-flex items-center gap-[6px] px-[12px] py-[4px] rounded-full text-[13px] font-bold" :class="submissionStatus.color">
                            <span x-text="submissionStatus.label"></span>
                        </span>
                    </div>
                    <div class="bg-[#f8fafc] p-4 rounded-[8px] border border-[#e2e8f0] text-center">
                        <p class="text-[12px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Total Skor Sistem</p>
                        <p class="text-[22px] font-bold text-[#1d293d]" x-text="Assessment.total_skor_sistem ?? 'N/A'"></p>
                    </div>
                    <div class="bg-[#f8fafc] p-4 rounded-[8px] border border-[#e2e8f0] text-center">
                        <p class="text-[12px] font-bold text-[#64748b] uppercase tracking-wider mb-1">Total Skor Akhir (Reviewer)</p>
                        <p class="text-[22px] font-bold text-[#1b5e20]" x-text="Assessment.total_skor_akhir ?? 'N/A'"></p>
                    </div>
                  </div>
                </div>

                {{-- Group A: Identitas Institusi --}}
                <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1] mb-6">
                  <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4 border-b border-[#e2e8f0] pb-2">
                    <h3 class="font-bold text-[#1b5e20] text-[15px]">A. Identitas Perguruan Tinggi</h3>
                    <template x-if="logoUrl">
                        <a :href="logoUrl" target="_blank" class="block w-16 h-16 overflow-hidden rounded-full bg-white border-2 border-slate-200 p-1 hover:border-[#1b5e20] transition-colors shadow-sm">
                            <img :src="logoUrl" class="w-full h-full object-contain" alt="Logo Perguruan Tinggi">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-0">
                        <!-- Kolom Kiri: 4 baris -->
                        <div class="space-y-4 md:border-r md:border-dashed md:border-[#e2e8f0] md:pr-6">
                            <template x-for="(agama, i) in agamaData.slice(0, 4)" :key="'l-' + i">
                                <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                    <span class="text-[14px] text-[#64748b] font-medium" x-text="agama.name"></span>
                                    <span class="text-[15px] text-[#1d293d] font-bold" x-text="agama.count"></span>
                                </div>
                            </template>
                        </div>
                        <!-- Kolom Kanan: 3 baris -->
                        <div class="space-y-4 md:pl-6">
                            <template x-for="(agama, i) in agamaData.slice(4, 7)" :key="'r-' + i">
                                <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                                    <span class="text-[14px] text-[#64748b] font-medium" x-text="agama.name"></span>
                                    <span class="text-[15px] text-[#1d293d] font-bold" x-text="agama.count"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Group F: Dokumen Pendukung --}}
                <div class="bg-white p-[24px] rounded-[12px] border border-[#cbd5e1] mb-6">
                    <h3 class="font-bold text-[#1b5e20] text-[15px] mb-4 border-b border-[#e2e8f0] pb-2">F. Dokumen Pendukung</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                            <span class="text-[14px] text-[#64748b] font-medium">Surat Pernyataan</span>
                            <template x-if="dokumenPendukung.surat_pernyataan">
                                <a :href="dokumenPendukung.surat_pernyataan" target="_blank" class="text-[14px] font-semibold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-[14px] h-[14px]"></i> Lihat Dokumen
                                </a>
                            </template>
                            <template x-if="!dokumenPendukung.surat_pernyataan">
                                <span class="text-[14px] text-[#94a3b8] italic">Belum diunggah</span>
                            </template>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                            <span class="text-[14px] text-[#64748b] font-medium">SK Pendirian</span>
                            <template x-if="dokumenPendukung.sk_pendirian">
                                <a :href="dokumenPendukung.sk_pendirian" target="_blank" class="text-[14px] font-semibold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-[14px] h-[14px]"></i> Lihat Dokumen
                                </a>
                            </template>
                            <template x-if="!dokumenPendukung.sk_pendirian">
                                <span class="text-[14px] text-[#94a3b8] italic">Belum diunggah</span>
                            </template>
                        </div>
                        {{-- SK Akreditasi AIPT - dinonaktifkan
                        <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                            <span class="text-[14px] text-[#64748b] font-medium">SK Akreditasi</span>
                            <template x-if="dokumenPendukung.sk_akreditasi">
                                <a :href="dokumenPendukung.sk_akreditasi" target="_blank" class="text-[14px] font-semibold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-[14px] h-[14px]"></i> Lihat Dokumen
                                </a>
                            </template>
                            <template x-if="!dokumenPendukung.sk_akreditasi">
                                <span class="text-[14px] text-[#94a3b8] italic">Belum diunggah</span>
                            </template>
                        </div>
                        --}}
                        <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                            <span class="text-[14px] text-[#64748b] font-medium">Profil PT</span>
                            <template x-if="dokumenPendukung.profil_pt">
                                <a :href="dokumenPendukung.profil_pt" target="_blank" class="text-[14px] font-semibold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-[14px] h-[14px]"></i> Lihat Dokumen
                                </a>
                            </template>
                            <template x-if="!dokumenPendukung.profil_pt">
                                <span class="text-[14px] text-[#94a3b8] italic">Belum diunggah</span>
                            </template>
                        </div>
                        <div class="flex justify-between items-center border-b border-dashed border-[#e2e8f0] pb-2">
                            <span class="text-[14px] text-[#64748b] font-medium">Struktur Organisasi</span>
                            <template x-if="dokumenPendukung.struktur_organisasi">
                                <a :href="dokumenPendukung.struktur_organisasi" target="_blank" class="text-[14px] font-semibold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-[14px] h-[14px]"></i> Lihat Dokumen
                                </a>
                            </template>
                            <template x-if="!dokumenPendukung.struktur_organisasi">
                                <span class="text-[14px] text-[#94a3b8] italic">Belum diunggah</span>
                            </template>
                        </div>
                        <div class="flex justify-between items-center pb-2">
                            <span class="text-[14px] text-[#64748b] font-medium">Kalender Akademik</span>
                            <template x-if="dokumenPendukung.kalender_akademik">
                                <a :href="dokumenPendukung.kalender_akademik" target="_blank" class="text-[14px] font-semibold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-[14px] h-[14px]"></i> Lihat Dokumen
                                </a>
                            </template>
                            <template x-if="!dokumenPendukung.kalender_akademik">
                                <span class="text-[14px] text-[#94a3b8] italic">Belum diunggah</span>
                            </template>
                        </div>
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

                  {{-- Questions (Accordion Content) --}}
                  <template x-for="q in categoryData.pertanyaan" :key="q.id">
                    <div :id="'q-' + q.id" class="relative bg-white border rounded-[12px] p-[20px] md:p-[28px] flex flex-col md:flex-row md:items-start gap-[20px] md:gap-[32px] overflow-hidden mb-[16px] transition-all duration-300" :class="isFlagged(q.id) ? 'border-red-500 ring-1 ring-red-100' : 'border-[#cbd5e1]'">
                      
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
                                            x-text="(q.tipe === 'isian_singkat' ? Number(opt.opsi_jawaban) : Math.min(opt.value !== null && opt.value !== undefined ? Number(opt.value) : Number(opt.opsi_jawaban), 5)) + ' Poin'"></span>
                                      <span x-text="opt.keterangan || opt.opsi_jawaban || '-'"></span>
                                    </li>
                                  </template>
                                </ul>
                            </div>
                          </template>
                        </div>
                      </div>

                      {{-- Right Column: Answers & Scoring Form --}}
                      <div class="flex-1 md:self-start flex flex-col space-y-[20px] bg-[#f8fafc] border border-[#e2e8f0] rounded-[8px] p-[20px]">
                        
                        {{-- Peserta's Answer --}}
                        <div>
                            <h4 class="text-[12px] font-bold text-[#64748b] uppercase tracking-[0.5px] mb-[6px]">Jawaban Klaim Peserta:</h4>
                            
                            {{-- Standard Display (Non-JSON) --}}
                            <template x-if="!parseAnswerJson(answers[q.id])">
                                <div class="bg-white border border-[#cbd5e1] rounded-[8px] p-[12px] text-[#1d293d] font-bold text-[14px] flex items-start gap-2 shadow-sm">
                                    <i data-lucide="check-circle-2" class="w-[18px] h-[18px] text-blue-600 shrink-0 mt-0.5"></i>
                                    <span x-text="answers[q.id] || 'Belum diisi / Belum disubmit'"></span>
                                </div>
                            </template>

                            {{-- Structured JSON Display --}}
                            <template x-if="parseAnswerJson(answers[q.id])">
                                <div class="space-y-3">
                                    {{-- B.13 Special Case --}}
                                    <template x-if="q.kode_pertanyaan === 'B.13'">
                                        <div class="bg-white border border-[#cbd5e1] rounded-[8px] p-4 shadow-sm space-y-3">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="p-2 bg-slate-50 rounded border border-slate-200">
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Lokal</p>
                                                    <p class="text-[14px] font-bold text-slate-700" x-text="parseAnswerJson(answers[q.id]).lokal?.nilai || 0"></p>
                                                </div>
                                                <div class="p-2 bg-slate-50 rounded border border-slate-200">
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Regional</p>
                                                    <p class="text-[14px] font-bold text-slate-700" x-text="parseAnswerJson(answers[q.id]).regional?.nilai || 0"></p>
                                                </div>
                                                <div class="p-2 bg-slate-50 rounded border border-slate-200">
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Nasional</p>
                                                    <p class="text-[14px] font-bold text-slate-700" x-text="parseAnswerJson(answers[q.id]).nasional?.nilai || 0"></p>
                                                </div>
                                                <div class="p-2 bg-slate-50 rounded border border-slate-200">
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Internasional</p>
                                                    <p class="text-[14px] font-bold text-slate-700" x-text="parseAnswerJson(answers[q.id]).internasional?.nilai || 0"></p>
                                                </div>
                                            </div>
                                            <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
                                                <span class="text-[12px] font-bold text-slate-600">Total Poin:</span>
                                                <span class="text-[16px] font-extrabold text-[#1b5e20]" x-text="parseAnswerJson(answers[q.id]).total_poin || 0"></span>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- System Calculated Display (C.2, B.18, etc.) --}}
                                    <template x-if="q.kode_pertanyaan !== 'B.13' && parseAnswerJson(answers[q.id])">
                                        <div class="space-y-2">
                                            <div class="bg-white border border-[#cbd5e1] rounded-[8px] p-[12px] text-[#1d293d] font-bold text-[14px] flex items-start gap-2 shadow-sm">
                                                <i data-lucide="hash" class="w-[18px] h-[18px] text-slate-500 shrink-0 mt-0.5"></i>
                                                <div>
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase mb-0.5">Input Mentah</p>
                                                    <span x-text="parseAnswerJson(answers[q.id]).raw_input || '-'"></span>
                                                </div>
                                            </div>
                                            <template x-if="parseAnswerJson(answers[q.id]).calculated_percentage !== null && parseAnswerJson(answers[q.id]).calculated_percentage !== undefined || parseAnswerJson(answers[q.id]).label">
                                                <div class="bg-[#f0fdf4] border border-[#bbf7d0] rounded-[8px] p-[12px] text-[#166534] font-bold text-[14px] flex items-start gap-2 shadow-sm border-l-4">
                                                    <i data-lucide="cpu" class="w-[18px] h-[18px] text-[#166534] shrink-0 mt-0.5"></i>
                                                    <div>
                                                        <p class="text-[10px] text-[#166534] font-bold uppercase mb-0.5">Kalkulasi Sistem</p>
                                                        <span x-text="((parseAnswerJson(answers[q.id]).calculated_percentage !== null && parseAnswerJson(answers[q.id]).calculated_percentage !== undefined) ? parseAnswerJson(answers[q.id]).calculated_percentage + '%' : '') + ((parseAnswerJson(answers[q.id]).calculated_percentage !== null && parseAnswerJson(answers[q.id]).calculated_percentage !== undefined && parseAnswerJson(answers[q.id]).label) ? ' ' : '') + (parseAnswerJson(answers[q.id]).label || '')"></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
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
                        <div class="pt-[16px] border-t border-[#cbd5e1] relative">

                            {{-- Unlock overlay --}}
                            <div x-show="!isDone && !unlockedQuestions[q.id] && !reviewerScores[q.id]"
                                 @click="unlockedQuestions[q.id] = true"
                                 class="absolute inset-0 z-10 bg-white/80 backdrop-blur-[1px] rounded-[8px] flex items-center justify-center cursor-pointer hover:bg-white/60 transition-all group">
                                <div class="flex items-center gap-2 px-4 py-2 bg-white border border-[#cbd5e1] rounded-lg shadow-sm group-hover:border-[#1b5e20] group-hover:shadow-md transition-all">
                                    <i data-lucide="mouse-pointer-click" class="w-4 h-4 text-[#64748b] group-hover:text-[#1b5e20]"></i>
                                    <span class="text-[13px] font-semibold text-[#64748b] group-hover:text-[#1b5e20]">Klik untuk mulai menilai</span>
                                </div>
                            </div>
                            {{-- Input Score --}}
                            <div class="mb-[16px]">
                                <h4 class="text-[13px] font-bold text-[#1d293d] mb-[8px] flex items-center justify-between gap-[6px]">
                                    <span class="flex items-center gap-[6px]">Berikan Score Final <span class="text-red-500">*</span></span>
                                    {{-- Inline save indicator kecil --}}
                                    <span x-show="saveStatus[q.id] === 'saving'" style="display:none;" class="text-[10px] text-amber-500 font-medium inline-flex items-center gap-1">
                                        <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> Menyimpan
                                    </span>
                                    <span x-show="saveStatus[q.id] === 'saved'" style="display:none;" class="text-[10px] text-[#1b5e20] font-semibold inline-flex items-center gap-1">
                                        <i data-lucide="check" class="w-3 h-3"></i> Tersimpan
                                    </span>
                                </h4>
                                <div class="flex items-center gap-[12px]">
                                    <template x-if="isDone && (!reviewerScores[q.id] && reviewerScores[q.id] !== 0)">
                                        <span class="w-[80px] px-[12px] py-[10px] rounded-[8px] border-2 bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8] text-[18px] font-bold text-center">N/A</span>
                                    </template>
                                    <template x-if="!isDone || reviewerScores[q.id] || reviewerScores[q.id] === 0">
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
                                      @blur="saveQuestionScore(q.id)"
                                    />
                                    </template>
                                    <span class="text-[13px] text-[#64748b] font-medium leading-tight max-w-[200px]">Maks 5. Ketik 0 jika bukti tidak valid.</span>
                                </div>
                            </div>

                            {{-- Input Catatan --}}
                            <div>
                                <h4 class="text-[13px] font-bold text-[#1d293d] mb-[8px]">
                                    Catatan Penilai / Alasan:
                                </h4>
                                <template x-if="isDone && (!reviewerNotes[q.id] || reviewerNotes[q.id].trim() === '')">
                                    <div class="w-full text-[14px] p-[12px] rounded-[8px] border-2 bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8] italic">Belum diisi</div>
                                </template>
                                <template x-if="!isDone || (reviewerNotes[q.id] && reviewerNotes[q.id].trim() !== '')">
                                <textarea
                                    rows="3"
                                    placeholder="Tuliskan alasan mengapa skor diberikan, atau hal yang kurang dari bukti validasi..."
                                    class="w-full text-[14px] p-[12px] rounded-[8px] border-2 focus:outline-none focus:border-[#1b5e20] hover:border-[#1b5e20]/60 transition-colors resize-y"
                                    :class="isDone ? 'bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8]' : (reviewerNotes[q.id] && reviewerNotes[q.id].length > 0 && reviewerNotes[q.id].length < 20 ? 'border-amber-400 bg-white text-[#1d293d]' : 'border-[#cbd5e1] text-[#1d293d] bg-white')"
                                    :disabled="isDone"
                                    x-model="reviewerNotes[q.id]"
                                    @blur="saveQuestionScore(q.id)"
                                ></textarea>
                                </template>
                                {{-- Character counter + warning --}}
                                <div class="flex items-center justify-between mt-[4px]">
                                    <div x-show="reviewerNotes[q.id] && reviewerNotes[q.id].length > 0 && reviewerNotes[q.id].length < 20"
                                         style="display:none;"
                                         class="flex items-center gap-[4px] text-amber-600">
                                        <svg class="w-[12px] h-[12px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span class="text-[11px] font-medium">Minimal 20 karakter diperlukan</span>
                                    </div>
                                    <span class="text-[11px] font-medium ml-auto"
                                          :class="reviewerNotes[q.id] && reviewerNotes[q.id].length > 0 && reviewerNotes[q.id].length < 20 ? 'text-amber-600' : 'text-[#94a3b8]'"
                                          x-text="(reviewerNotes[q.id] ? reviewerNotes[q.id].length : 0) + ' karakter'"></span>
                                </div>
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
                  <span class="w-[8px] h-[8px] rounded-full bg-green-400 animate-pulse shrink-0"></span>
                  Pastikan Seluruh Nilai Sudah Terisi Sebelum Finalisasi
                </div>
                <span class="text-[12px] font-medium text-[#64748b]">Data tersimpan otomatis. Klik Finalisasi jika sudah yakin.</span>
            </div>
            <div class="flex items-center gap-[10px]">
                {{-- Finalisasi Penilaian --}}
                <button
                  @click="validateBeforeFinalize()"
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
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-teal-600"></span> Dinilai</span>
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-indigo-400"></span> Sebagian</span>
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#e0e0e0]"></span> Kosong</span>
                  <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-violet-500"></span> Flag</span>
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
                                       :class="isFlagged(q.id) ? 'text-white' : fillStatus(q.id) === 2 ? 'text-white' : fillStatus(q.id) === 1 ? 'text-white' : 'text-[#62748e]'"
                                      :style="isFlagged(q.id) ? 'background:#8b5cf6;' : fillStatus(q.id) === 2 ? 'background:#0d9488;' : fillStatus(q.id) === 1 ? 'background:#818cf8;' : 'background:#e0e0e0;'"
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
</x-dynamic-component>
