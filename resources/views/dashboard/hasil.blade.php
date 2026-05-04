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
        is_validated: false,
        
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
                    this.is_validated = data.is_validated;
                    
                    // Re-init Lucide icons after DOM update
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                } else {
                    if (response.status === 401) {
                        window.location.href = '/masuk';
                    }
                }
            } catch (error) {
                console.error('Failed to fetch results', error);
            } finally {
                this.loading = false;
            }
        }
    }" class="bg-[#f5f5f5] min-h-full py-5 px-4 md:px-8">

        <div class="max-w-[860px] mx-auto">
            {{-- Loading State (konsisten) --}}
            <template x-if="loading">
                <div>
                    <x-dashboard.loading
                        title="Memuat Hasil Penilaian..."
                        caption="Mohon tunggu sebentar, sistem sedang merekap data Anda." />
                </div>
            </template>

            <template x-if="!loading">
                <div class="space-y-5">
                    {{-- ✏️ Banner Hijau Total Penilaian → components/dashboard/hasil/banner.blade.php --}}
                    <x-dashboard.hasil.banner />

                    {{-- ✏️ Card Status Penilaian → components/dashboard/hasil/status.blade.php --}}
                    <x-dashboard.hasil.status />

                    {{-- ✏️ Accordion Rincian Poin → components/dashboard/hasil/kategori.blade.php --}}
                    <x-dashboard.hasil.kategori />
                </div>
            </template>
        </div>
    </div>
</x-layouts.dashboard>
