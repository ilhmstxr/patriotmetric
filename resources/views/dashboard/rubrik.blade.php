<x-layouts.dashboard>
    <x-slot:title>Form Rubrik</x-slot:title>

    <div x-data="{
        answers: {},
        links: {},
        categories: [],
        loading: true,
        isSaving: false,
        lastSaved: '',
        status: '',
        is_edit_enabled: true,
        profil: {},
        saveTimers: {},
        saveStatus: {},

        {{-- Floating Drawer State --}}
        drawerOpen: false,

        {{-- Flag State (persisted in sessionStorage) --}}
        flags: {},

        toggleFlag(questionId) {
            this.flags[questionId] = !this.flags[questionId];
            try { sessionStorage.setItem('rubrik_flags', JSON.stringify(this.flags)); } catch(e) {}
        },

        isFlagged(questionId) {
            return !!this.flags[questionId];
        },

        isAnswered(questionId) {
            const ans = this.answers[questionId];
            return ans !== null && ans !== undefined && ans !== '';
        },

        scrollToQuestion(qId) {
            const el = document.getElementById('q-' + qId);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Briefly highlight
                el.classList.add('ring-2', 'ring-[#1b5e20]', 'ring-offset-2');
                setTimeout(() => el.classList.remove('ring-2', 'ring-[#1b5e20]', 'ring-offset-2'), 1500);
            }
            this.drawerOpen = false;
        },

        get allQuestions() {
            return this.categories.flatMap(c => c.questions);
        },

        get totalAnswered() {
            return this.allQuestions.filter(q => this.isAnswered(q.id)).length;
        },

        get totalFlagged() {
            return this.allQuestions.filter(q => this.isFlagged(q.id)).length;
        },

        async init() {
            {{-- Restore flags from sessionStorage --}}
            try {
                const savedFlags = sessionStorage.getItem('rubrik_flags');
                if (savedFlags) this.flags = JSON.parse(savedFlags);
            } catch(e) {}

            try {
                {{-- Selalu ambil fresh dari API (tidak pakai cache) agar profil selalu up-to-date --}}
                const response = await fetch('/api/assessment/peserta/questions', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();

                if (result.success) {
                    this.status = result.data.status;
                    this.is_edit_enabled = result.data.is_edit_enabled;
                    this.profil = result.data.profil || {};
                    this.categories = this.groupByCategory(result.data.questions);

                    {{-- Auto-fill untuk soal tipe otomatis_sistem --}}
                    this.$nextTick(() => {
                        this.allQuestions
                            .filter(q => q.type === 'otomatis_sistem')
                            .forEach(q => {
                                const val = this.computeAutomatic(q);
                                if (val !== null && (this.answers[q.id] === undefined || this.answers[q.id] === null || this.answers[q.id] === '')) {
                                    this.answers[q.id] = val;
                                    {{-- Auto-save nilai otomatis ke DB --}}
                                    this.scheduleAutoSave(q.id);
                                }
                            });
                        if (window.lucide) window.lucide.createIcons();
                    });
                } else if (response.status === 401) {
                    window.location.href = '/masuk';
                }
            } catch (error) {
                console.error('Gagal mengambil data pertanyaan:', error);
            } finally {
                this.loading = false;
            }
        },

        groupByCategory(data) {
            const groups = {};
            data.forEach(item => {
                const category = item.kategori || { nama_kategori: 'Tanpa Kategori' };
                const catName = category.nama_kategori;
                
                if (!groups[catName]) {
                    groups[catName] = {
                        category: catName,
                        weight: 0,
                        questions: []
                    };
                }

                const existingJawaban = item.jawaban && item.jawaban.length > 0 ? item.jawaban[0] : null;
                if (existingJawaban) {
                    if (item.tipe === 'pilihan_ganda') {
                        this.answers[item.id] = existingJawaban.jawaban_id;
                    } else {
                        this.answers[item.id] = existingJawaban.jawaban_teks;
                    }
                    this.links[item.id] = existingJawaban.tautan_bukti_drive || '';
                }

                groups[catName].questions.push({
                    id: item.id,
                    code: item.kode_pertanyaan,
                    title: item.teks_pertanyaan,
                    evidenceRequirements: Array.isArray(item.kebutuhan_bukti) ? item.kebutuhan_bukti : (item.kebutuhan_bukti ? [item.kebutuhan_bukti] : []),
                    type: item.tipe,
                    options: (item.OpsiJawaban || item.opsi_jawaban || []).map(opt => ({
                        id: opt.id,
                        text: opt.keterangan || opt.opsi_jawaban || opt.OpsiJawaban
                    }))
                });
                
                groups[catName].weight += 5;
            });

            return Object.values(groups);
        },

        {{-- ================================================================= --}}
        {{-- 🟢 FILL STATUS — 0:kosong, 1:setengah (1 dari 2), 2:penuh (keduanya) --}}
        {{-- ================================================================= --}}
        fillStatus(qId) {
            const hasAnswer = this.isAnswered(qId);
            const hasLink   = !!(this.links[qId] && String(this.links[qId]).trim() !== '');
            if (hasAnswer && hasLink) return 2;
            if (hasAnswer || hasLink) return 1;
            return 0;
        },

        {{-- ================================================================= --}}
        {{-- 💾 DEBOUNCE AUTO-SAVE PER INDIKATOR                              --}}
        {{-- ================================================================= --}}
        isValidDriveLink(url) {
            if (!url || url.trim() === '') return true;
            return url.includes('drive.google.com') || url.includes('docs.google.com');
        },

        scheduleAutoSave(qId) {
            const url = this.links[qId];
            if (url && url.trim() !== '' && !this.isValidDriveLink(url)) {
                this.saveStatus[qId] = 'invalid_link';
                return;
            }

            clearTimeout(this.saveTimers[qId]);
            this.saveStatus[qId] = 'saving';
            this.saveTimers[qId] = setTimeout(() => this.autoSave(qId), 800);
        },

        async autoSave(qId) {
            const question = this.allQuestions.find(q => q.id == qId);
            if (!question) { this.saveStatus[qId] = 'error'; return; }

            const isMulti = question.type === 'pilihan_ganda';
            const rawAns  = this.answers[qId];

            const payload = {
                pertanyaan_id: parseInt(qId),
                jawaban_id:    isMulti ? (rawAns != null && rawAns !== '' ? parseInt(rawAns) : null) : null,
                jawaban_teks:  !isMulti ? (rawAns != null ? String(rawAns) : null) : null,
                tautan_bukti:  this.links[qId] && String(this.links[qId]).trim() !== '' ? this.links[qId] : null,
            };

            try {
                const res = await fetch('/api/assessment/peserta/save-answer', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                this.saveStatus[qId] = (res.ok && result.success) ? 'saved' : 'error';
                {{-- Reset status 'saved' setelah 2 detik agar tidak terlalu mencolok --}}
                if (this.saveStatus[qId] === 'saved') {
                    setTimeout(() => { if (this.saveStatus[qId] === 'saved') this.saveStatus[qId] = ''; }, 2000);
                }
            } catch(e) {
                this.saveStatus[qId] = 'error';
            }
        },

        {{-- ================================================================= --}}
        {{-- 📐 FORMULA PREVIEW (isian_singkat yang memiliki pembagi profil)   --}}
        {{-- Formula: nilai_input / denominator * 100                          --}}
        {{-- ================================================================= --}}
        computeFormula(q) {
            const val = parseFloat(this.answers[q.id]);
            if (isNaN(val) || val < 0) return null;

            const map = {
                'B.6':  { varKey: 'jml_dosen',       label: 'total dosen' },
                'B.7':  { varKey: 'jml_prodi',        label: 'total prodi' },
                'B.18': { varKey: 'jml_ormawa',       label: 'total ormawa' },
                'B.20': { varKey: 'jml_agama_aktif',  label: 'jenis agama yang ada' },
                'C.5':  { varKey: 'jml_mahasiswa',    label: 'total mahasiswa' },
                'C.7':  { varKey: 'jml_mahasiswa',    label: 'total mahasiswa' },
                'C.9':  { varKey: 'jml_mahasiswa',    label: 'total mahasiswa' },
            };

            const entry = map[q.code];
            if (!entry) return null;

            const denom = parseFloat(this.profil[entry.varKey]);
            if (!denom || denom <= 0) return null;

            return {
                persen: ((val / denom) * 100).toFixed(2),
                label: entry.label,
            };
        },

        {{-- ================================================================= --}}
        {{-- ⚙️ AUTO-FILL OTOMATIS SISTEM (C.6 → jml_ukm dari profil)       --}}
        {{-- ================================================================= --}}
        computeAutomatic(q) {
            const map = {
                'C.6': () => this.profil.jml_ukm != null ? String(this.profil.jml_ukm) : null,
            };
            const fn = map[q.code];
            return fn ? fn() : null;
        },

        async saveDraft() {
            this.isSaving = true;
            
            const answersPayload = [];
            for (const qId in this.answers) {
                const isNumeric = !isNaN(this.answers[qId]) && this.answers[qId] !== '';
                answersPayload.push({
                    pertanyaan_id: parseInt(qId),
                    jawaban_id: isNumeric ? parseInt(this.answers[qId]) : null,
                    jawaban_teks: isNumeric ? null : String(this.answers[qId] || ''),
                    tautan_bukti: this.links[qId] || null
                });
            }

            try {
                const response = await fetch('/api/assessment/peserta/save-draft', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ answers: answersPayload })
                });
                
                const result = await response.json();
                if (response.ok && result.success) {
                    let d = new Date();
                    this.lastSaved = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                    sessionStorage.removeItem('rubrik_data_cache');
                    sessionStorage.removeItem('hasil_data_cache');
                    sessionStorage.removeItem('rubrik_flags'); {{-- Clear flags on submit --}}
                    alert('Draft berhasil disimpan dan di-submit!');
                    this.status = 'SUBMITTED';
                    window.location.href = '/dashboard/hasil';
                } else {
                    alert(result.message || 'Gagal menyimpan draft.');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                this.isSaving = false;
            }
        }
    }" class="bg-[#f5f5f5] min-h-full font-['Plus_Jakarta_Sans',sans-serif]">

        {{-- Scrollable content area --}}
        <div class="py-5 px-4 md:px-8">
            <div class="max-w-[960px] mx-auto">

                {{-- Form title --}}
                <h1 class="font-bold text-[#1d293d] text-[18px] uppercase tracking-wide mb-5">Form Rubrik</h1>

                <div class="space-y-6">
                    {{-- Loading State (konsisten) --}}
                    <template x-if="loading">
                        <div>
                            <x-dashboard.loading
                                title="Memuat Form Rubrik..."
                                caption="Mohon tunggu sebentar, sistem sedang menyiapkan pertanyaan rubrik Anda." />
                        </div>
                    </template>

                    <template x-if="!loading">
                        <template x-for="(categoryData, cIdx) in categories" :key="cIdx">
                            <div class="space-y-4">

                            {{-- Category Header --}}
                            <div class="flex items-center justify-between border-b border-[#e0e0e0] pb-2">
                                <h2 class="text-[15px] font-bold text-[#1d293d] uppercase tracking-wide" x-text="categoryData.category"></h2>
                                <span class="text-[12px] font-semibold text-[#62748e]" x-text="'Bobot: ' + categoryData.weight"></span>
                            </div>

                            {{-- Questions --}}
                            <template x-for="q in categoryData.questions" :key="q.id">
                                {{-- 🏷️ Question Card — id anchor for scroll targeting + relative for flag ribbon --}}
                                <div :id="'q-' + q.id"
                                     class="relative bg-white border border-[#e0e0e0] rounded-lg overflow-hidden transition-all duration-300"
                                     :class="isFlagged(q.id) ? 'border-amber-400' : ''">

                                    {{-- ===== 🔖 Bookmark Flag ===== --}}
                                    <button type="button"
                                        @click.stop="toggleFlag(q.id)"
                                        :title="isFlagged(q.id) ? 'Hapus flag' : 'Tandai pertanyaan ini'"
                                        class="absolute top-0 right-4 z-10 focus:outline-none"
                                        style="width: 24px;">
                                        {{-- Classic bookmark: rectangle + single V-notch at bottom --}}
                                        <span class="block w-full transition-all duration-300"
                                            :style="isFlagged(q.id)
                                                ? 'height:35px; background:#f59e0b; clip-path:polygon(0 0,100% 0,100% 100%,50% 80%,0 100%); box-shadow:0 6px 14px rgba(245,158,11,0.45);'
                                                : 'height:20px; background:#cbd5e1; clip-path:polygon(0 0,100% 0,100% 100%,50% 75%,0 100%); box-shadow:none;'">
                                        </span>
                                    </button>

                                    <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-[#e0e0e0]">

                                        {{-- LEFT: Question + Evidence --}}
                                        <div class="md:w-[45%] p-5 space-y-4 shrink-0">
                                            {{-- Code + Title --}}
                                            <div class="flex gap-3">
                                                <div class="w-[28px] h-[28px] rounded bg-[#f5f5f5] border border-[#e0e0e0] flex items-center justify-center font-bold text-[#1d293d] text-[12px] shrink-0 mt-0.5"
                                                     x-text="q.code"></div>
                                                <h3 class="font-bold text-[#1d293d] text-[13px] leading-snug pr-6" x-text="q.title"></h3>
                                            </div>

                                            {{-- Evidence requirements --}}
                                            <div class="bg-[#fafafa] border border-[#e0e0e0] rounded p-3 space-y-1.5">
                                                <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider mb-2">Syarat Bukti:</p>
                                                <template x-for="(req, rIdx) in q.evidenceRequirements" :key="rIdx">
                                                    <div class="flex gap-2 items-start">
                                                        <span class="shrink-0 mt-1.5 w-1 h-1 bg-[#90a1b9] rounded-full"></span>
                                                        <span class="text-[12px] font-medium text-[#62748e] leading-snug" x-text="req"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- RIGHT: Answers + Link --}}
                                        <div class="flex-1 p-5 space-y-4">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider">Jawaban:</p>
                                                {{-- Save Status Badge --}}
                                                <span x-show="saveStatus[q.id] === 'invalid_link'" class="text-[10px] text-red-500 font-bold flex items-center gap-1" style="display:none;">✗ Bukan Link Google Drive</span>
                                                <span x-show="saveStatus[q.id] === 'saving'" class="text-[10px] text-amber-500 font-medium flex items-center gap-1" style="display:none;">⏳ Menyimpan...</span>
                                                <span x-show="saveStatus[q.id] === 'saved'" class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1" style="display:none;">✓ Tersimpan</span>
                                                <span x-show="saveStatus[q.id] === 'error'" class="text-[10px] text-red-500 font-semibold flex items-center gap-1" style="display:none;">✗ Gagal simpan</span>
                                            </div>

                                            {{-- Pilihan Ganda --}}
                                            <template x-if="q.type === 'pilihan_ganda'">
                                                <div class="space-y-2">
                                                    <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                                        <button
                                                            type="button"
                                                            :disabled="!is_edit_enabled || status === 'SUBMITTED' || status === 'GRADED'"
                                                            @click="answers[q.id] = opt.id; scheduleAutoSave(q.id)"
                                                            :class="answers[q.id] === opt.id
                                                                ? 'bg-[#e8f5e9] border-[#1b5e20] text-[#1b5e20] font-semibold'
                                                                : 'bg-white border-[#e0e0e0] text-[#45556c] font-medium hover:border-[#b0b0b0]'"
                                                            class="w-full text-left px-3.5 py-2.5 rounded border text-[12px] leading-snug transition-colors whitespace-pre-line"
                                                            x-text="opt.text">
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>
 
                                            {{-- Isian Singkat --}}
                                            <template x-if="q.type === 'isian_singkat'">
                                                <div class="space-y-2">
                                                    <div class="flex items-center gap-3">
                                                        <input
                                                            type="number"
                                                            placeholder="0"
                                                            :disabled="!is_edit_enabled || status === 'SUBMITTED' || status === 'GRADED'"
                                                            class="w-[100px] px-3.5 py-2.5 rounded border border-[#e0e0e0] text-[12px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] bg-white placeholder-[#90a1b9] disabled:bg-[#f5f5f5] disabled:text-[#90a1b9]"
                                                            x-model="answers[q.id]"
                                                            @input="scheduleAutoSave(q.id)"
                                                        />
                                                        {{-- Gunakan deskripsi sebagai unit (Cth: 10 skema KKN) --}}
                                                        <span class="text-[12px] font-semibold text-[#45556c]" x-text="q.description"></span>
                                                    </div>
                                                    {{-- Formula Preview % --}}
                                                    <template x-if="computeFormula(q) !== null">
                                                        <div class="w-fit flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 rounded px-2.5 py-1.5">
                                                            <span class="text-[10px] text-emerald-700 font-bold">≈</span>
                                                            <span class="text-[11px] font-bold text-emerald-700" x-text="computeFormula(q)?.persen + '%'"></span>
                                                            <span class="text-[10px] text-emerald-600">dari</span>
                                                            <span class="text-[10px] font-semibold text-emerald-700" x-text="computeFormula(q)?.label"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
 
                                            {{-- Otomatis Sistem --}}
                                            <template x-if="q.type === 'otomatis_sistem'">
                                                <div class="relative">
                                                    <input
                                                        type="text"
                                                        placeholder="Akan dihitung otomatis oleh sistem"
                                                        disabled
                                                        class="w-full px-3.5 py-2.5 rounded border border-[#e0e0e0] text-[12px] font-bold text-[#62748e] bg-[#f8f9fa] cursor-not-allowed"
                                                        x-model="answers[q.id]"
                                                    />
                                                    <div class="mt-2 flex items-center gap-1.5 text-[10px] text-[#2e7d32] font-semibold">
                                                        <i data-lucide="cpu" class="w-3 h-3"></i>
                                                        Data diproses otomatis oleh sistem berdasarkan profil institusi
                                                    </div>
                                                </div>
                                            </template>

                                            {{-- Tautan Bukti --}}
                                            <div class="pt-2">
                                                <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                                    <i data-lucide="link" class="w-[12px] h-[12px]"></i>
                                                    Tautan Bukti / Dokumen
                                                </p>
                                                <input
                                                    type="url"
                                                    placeholder="https://drive.google.com/..."
                                                    :disabled="!is_edit_enabled || status === 'SUBMITTED' || status === 'GRADED'"
                                                    class="w-full px-3.5 py-2.5 rounded border border-[#e0e0e0] text-[12px] font-medium focus:outline-none focus:border-[#1b5e20] bg-[#fafafa] text-[#1d293d] placeholder-[#90a1b9] disabled:bg-[#f5f5f5] disabled:text-[#90a1b9]"
                                                    x-model="links[q.id]"
                                                    @input="scheduleAutoSave(q.id)"
                                                />
                                                <p class="text-[10px] font-medium text-red-500 font-bold mt-1.5">* Pastikan tautan dapat diakses publik (<em>Anyone with the link</em>)</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>
                        </div>
                        </template>
                    </template>
                </div>

            </div>
        </div>

        {{-- ✏️ Sticky Footer → components/dashboard/rubrik/footer.blade.php --}}
        <x-dashboard.rubrik.footer />

        {{-- ================================================================== --}}
        {{-- 🧭 FLOATING QUIZ NAVIGATION DRAWER                                --}}
        {{-- Tombol panah sticky di kanan layar, klik untuk toggle drawer panel --}}
        {{-- ================================================================== --}}
        <div x-show="!loading" style="display:none;">

            {{-- Toggle Arrow Button — follows header show/hide via showBar from parent scope --}}
            <button
                type="button"
                @click="drawerOpen = !drawerOpen"
                class="fixed right-0 z-40 bg-[#1b5e20] text-white shadow-lg flex items-center justify-center"
                :style="{
                    top: showBar ? 'calc(120px + ((100vh - 120px) / 2) - 32px)' : 'calc(50vh - 32px)',
                    width: '28px',
                    height: '64px',
                    borderRadius: '8px 0 0 8px',
                    transition: 'top 0.3s ease'
                }"
                :title="drawerOpen ? 'Tutup Navigator' : 'Buka Navigator Soal'">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 transition-transform duration-300"
                     :class="drawerOpen ? 'rotate-0' : 'rotate-0'"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>

            {{-- Drawer Backdrop (close on outside click) --}}
            <div
                x-show="drawerOpen"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="drawerOpen = false"
                class="fixed inset-0 bg-black/20 z-30"
                style="display:none;">
            </div>

            {{-- Drawer Panel — top/height react to showBar from parent body x-data --}}
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
                    top:        showBar ? '120px' : '0px',
                    height:     showBar ? 'calc(100vh - 120px)' : '100vh',
                    transition: 'top 0.3s ease, height 0.3s ease'
                }">


                {{-- Drawer Header --}}
                <div class="px-4 py-4 border-b border-[#e0e0e0] flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="font-bold text-[#1d293d] text-[13px] uppercase tracking-wide">Navigator Soal</h3>
                        <p class="text-[11px] text-[#62748e] mt-0.5">
                            <span class="font-semibold text-[#1b5e20]" x-text="totalAnswered"></span>
                            / <span x-text="allQuestions.length"></span> terjawab
                            <template x-if="totalFlagged > 0">
                                <span class="ml-2 text-amber-600 font-semibold">
                                    · <span x-text="totalFlagged"></span> flag
                                </span>
                            </template>
                        </p>
                    </div>
                    <button type="button" @click="drawerOpen = false"
                        class="text-[#90a1b9] hover:text-[#45556c] p-1 rounded transition-colors focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                {{-- Legend --}}
                <div class="px-4 pt-3 pb-2 flex flex-wrap items-center gap-3 text-[10px] font-medium text-[#62748e] shrink-0">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-[#1b5e20]"></span> Lengkap
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm" style="background:linear-gradient(to right,#1b5e20 50%,#e0e0e0 50%)"></span> Sebagian
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-[#e0e0e0]"></span> Kosong
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-amber-400"></span> Flag
                    </span>
                </div>

                {{-- Question Grid (scrollable) --}}
                <div class="flex-1 overflow-y-auto px-4 py-2 space-y-5">
                    <template x-for="(catData, ci) in categories" :key="ci">
                        <div>
                            {{-- Category Label --}}
                            <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider mb-2" x-text="catData.category"></p>
                            {{-- Grid of question blocks --}}
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="q in catData.questions" :key="q.id">
                                    <button
                                        type="button"
                                        @click="scrollToQuestion(q.id)"
                                        :title="'Soal ' + q.code + (isFlagged(q.id) ? ' (Flag)' : '') + (fillStatus(q.id) === 2 ? ' ✓' : fillStatus(q.id) === 1 ? ' (sebagian)' : '')"
                                        class="relative w-9 h-9 rounded text-[11px] font-bold transition-all duration-150 focus:outline-none hover:scale-110 hover:shadow-md overflow-hidden"
                                        :class="isFlagged(q.id) ? 'text-white' : fillStatus(q.id) === 2 ? 'text-white' : fillStatus(q.id) === 1 ? 'text-white' : 'text-[#62748e]'"
                                        :style="isFlagged(q.id)
                                            ? 'background:#f59e0b;'
                                            : fillStatus(q.id) === 2
                                                ? 'background:#1b5e20;'
                                                : fillStatus(q.id) === 1
                                                    ? 'background:linear-gradient(to right,#1b5e20 50%,#e0e0e0 50%);'
                                                    : 'background:#e0e0e0;'"
                                        x-text="q.code">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Drawer Footer --}}
                <div class="px-4 py-3 border-t border-[#e0e0e0] shrink-0">
                    <div class="flex items-center gap-2 text-[11px] text-[#90a1b9]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Klik nomor soal untuk loncat ke soal tersebut.
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.dashboard>
