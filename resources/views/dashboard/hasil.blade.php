<x-layouts.dashboard>
    <x-slot:title>Hasil Penilaian</x-slot:title>

    {{--
        ✏️ DATA KATEGORI: Edit di blok x-data di bawah ini.
        Setiap kategori: { name, score, max, color ('green'|'orange'), items: [...] }
        Setiap item: { no, title, score, max, jawaban, tautan, catatan }
    --}}
    <div x-data="{
        loading: true,
        openCategories: {},
        toggleCategory(idx) { this.openCategories[idx] = !this.openCategories[idx]; },
        categories: [],
        tahun_periode: new Date().getFullYear(),
        institusi: 'Loading...',
        total_score: 0,
        total_max: 0,
        total_capaian_skor: 0,
        status: 'Loading...',
        is_published: false,
        is_validated: false,
        error_message: '',
        
        async init() {
            this.loading = true;
            try {
                const response = await fetch('/api/assessment/peserta/hasil', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();

                if (response.ok && result.success) {
                    const data = result.data;
                    this.categories = data.categories;
                    this.tahun_periode = data.tahun_periode;
                    this.institusi = data.institusi;
                    this.total_score = data.total_score;
                    this.total_max = data.total_max;
                    this.total_capaian_skor = data.total_capaian_skor;
                    this.status = data.status;
                    this.is_published = data.is_published || false;
                    this.is_validated = data.is_validated || false;
                } else if (response.status === 401) {
                    window.location.href = '/masuk';
                } else {
                    this.error_message = result.message || 'Gagal memuat hasil penilaian.';
                }
                
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            } catch (error) {
                console.error('Failed to fetch results', error);
                this.error_message = 'Terjadi kesalahan jaringan.';
            } finally {
                this.loading = false;
            }
        }
    }" class="bg-[#f5f5f5] min-h-full py-5 px-4 md:px-8">

        <div class="max-w-[860px] mx-auto">
            {{-- Loading State --}}
            <template x-if="loading">
                <div>
                    <x-dashboard.loading
                        title="Memuat Hasil Penilaian..."
                        caption="Mohon tunggu sebentar, sistem sedang merekap data Anda." />
                </div>
            </template>

            <template x-if="!loading && !error_message">
                <div class="space-y-5">
                    {{-- Banner Total Penilaian --}}
                    <x-dashboard.hasil.banner />

                    {{-- Rincian Poin per Kategori --}}
                    <x-dashboard.hasil.kategori />
                </div>
            </template>

            {{-- Error State --}}
            <template x-if="!loading && error_message">
                <div class="bg-white border border-[#e0e0e0] rounded-xl p-8 text-center shadow-sm">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="clock" class="w-8 h-8 text-amber-600"></i>
                    </div>
                    <h2 class="text-[18px] font-bold text-[#1d293d]">Hasil Belum Tersedia</h2>
                    <p class="text-[14px] text-[#62748e] mt-2 max-w-[400px] mx-auto" x-text="error_message"></p>
                    <a href="/dashboard" class="inline-flex items-center gap-2 mt-6 bg-[#1b5e20] text-white px-6 py-2.5 rounded-lg font-semibold text-[13px] hover:bg-[#15461c] transition-colors">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </template>
        </div>
    </div>
</x-layouts.dashboard>
