<x-layouts.dashboard>
    <x-slot:title>Form Rubrik</x-slot:title>

    <div x-data="{
        answers: {},
        links: {},
        categories: [],
        loading: true,

        async init() {
            try {
                // Panggil API untuk mengambil semua pertanyaan
                const response = await fetch('/api/assessment/peserta/questions/assessmentid={{ $assessmentId ?? 0 }}');
                const result = await response.json();

                if (result.success) {
                    this.categories = this.groupByCategory(result.data);
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
                const category = item.kategori || { nama_kategori: 'Tanpa Kategori', bobot_presentase: 0 };
                const catName = category.nama_kategori;
                
                if (!groups[catName]) {
                    groups[catName] = {
                        category: catName,
                        weight: (category.bobot_presentase || 0) + '%',
                        questions: []
                    };
                }

                // Map data API ke format UI
                groups[catName].questions.push({
                    id: item.id,
                    code: item.kode_pertanyaan,
                    title: item.teks_pertanyaan,
                    evidenceRequirements: item.kebutuhan_bukti ? item.kebutuhan_bukti.split('\n') : [],
                    type: item.tipe === 'pilihan_ganda' ? 'multiple-choice' : 'short-answer',
                    options: item.opsi_jawabans.map(opt => ({
                        id: opt.id,
                        text: opt.keterangan || opt.opsi_jawaban
                    }))
                });
            });

            return Object.values(groups);
        }
    }" class="bg-[#f5f5f5] min-h-full font-['Plus_Jakarta_Sans',sans-serif]">

        {{-- Scrollable content area --}}
        <div class="py-5 px-4 md:px-8">
            <div class="max-w-[960px] mx-auto">

                {{-- Form title --}}
                <h1 class="font-bold text-[#1d293d] text-[18px] uppercase tracking-wide mb-5">Form Rubrik</h1>

                <div class="space-y-6">
                    {{-- Loading State --}}
                    <template x-if="loading">
                        <div class="flex flex-col items-center justify-center py-20 space-y-4">
                            <div class="w-10 h-10 border-4 border-[#1b5e20] border-t-transparent rounded-full animate-spin"></div>
                            <p class="text-[14px] font-medium text-[#62748e]">Memuat data pertanyaan...</p>
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
                                <div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden">
                                    <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-[#e0e0e0]">

                                        {{-- LEFT: Question + Evidence --}}
                                        <div class="md:w-[45%] p-5 space-y-4 shrink-0">
                                            {{-- Code + Title --}}
                                            <div class="flex gap-3">
                                                <div class="w-[28px] h-[28px] rounded bg-[#f5f5f5] border border-[#e0e0e0] flex items-center justify-center font-bold text-[#1d293d] text-[12px] shrink-0 mt-0.5"
                                                     x-text="q.code"></div>
                                                <h3 class="font-bold text-[#1d293d] text-[13px] leading-snug" x-text="q.title"></h3>
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
                                            <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider">Jawaban:</p>

                                            {{-- Multiple choice options --}}
                                            <template x-if="q.type === 'multiple-choice'">
                                                <div class="space-y-2">
                                                    <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                                        <button
                                                            type="button"
                                                            @click="answers[q.id] = opt.id"
                                                            :class="answers[q.id] === opt.id
                                                                ? 'bg-[#e8f5e9] border-[#1b5e20] text-[#1b5e20] font-semibold'
                                                                : 'bg-white border-[#e0e0e0] text-[#45556c] font-medium hover:border-[#b0b0b0]'"
                                                            class="w-full text-left px-3.5 py-2.5 rounded border text-[12px] leading-snug transition-colors"
                                                            x-text="opt.text">
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>

                                            {{-- Short answer --}}
                                            <template x-if="q.type !== 'multiple-choice'">
                                                <input
                                                    type="text"
                                                    placeholder="Masukkan jawaban..."
                                                    class="w-full px-3.5 py-2.5 rounded border border-[#e0e0e0] text-[12px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] bg-white placeholder-[#90a1b9]"
                                                    x-model="answers[q.id]"
                                                />
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
                                                    class="w-full px-3.5 py-2.5 rounded border border-[#e0e0e0] text-[12px] font-medium focus:outline-none focus:border-[#1b5e20] bg-[#fafafa] text-[#1d293d] placeholder-[#90a1b9]"
                                                    x-model="links[q.id]"
                                                />
                                                <p class="text-[10px] font-medium text-[#90a1b9] mt-1.5">* Pastikan tautan dapat diakses publik (Anyone with the link)</p>
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

    </div>
</x-layouts.dashboard>
