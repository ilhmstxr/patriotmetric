<x-layouts.dashboard>
    <x-slot:title>Hasil Penilaian</x-slot:title>

    {{--
        ✏️ DATA KATEGORI: Edit di blok x-data di bawah ini.
        Setiap kategori: { name, score, max, color ('green'|'orange'), items: [...] }
        Setiap item: { no, title, score, max, jawaban, tautan, catatan }
    --}}
    <div x-data="{
        openCategories: {},
        toggleCategory(idx) { this.openCategories[idx] = !this.openCategories[idx]; },
        categories: [],
        tahun_periode: new Date().getFullYear(),
        institusi: '-',
        total_score: 0,
        total_max: 0,
        total_capaian_skor: 0,
        status: '-',
        is_published: false,
        is_validated: false,

        async init() {
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
                    this.categories = data.categories || [];
                    this.tahun_periode = data.tahun_periode || new Date().getFullYear();
                    this.institusi = data.institusi || '-';
                    this.total_score = data.total_score || 0;
                    this.total_max = data.total_max || 0;
                    this.total_capaian_skor = data.total_capaian_skor || 0;
                    this.status = data.status || '-';
                    this.is_published = data.is_published || false;
                    this.is_validated = data.is_validated || false;
                } else if (response.status === 401) {
                    window.location.href = '/masuk';
                }

                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            } catch (error) {
                console.error('Failed to fetch results', error);
            }
        }
    }" class="bg-[#f5f5f5] min-h-full py-5 px-4 md:px-8">

        <div class="max-w-[860px] mx-auto">
            <div class="space-y-5">
                {{-- Banner Total Penilaian --}}
                <x-dashboard.hasil.banner />

                {{-- Rincian Poin per Kategori --}}
                <x-dashboard.hasil.kategori />
            </div>
        </div>
    </div>
</x-layouts.dashboard>
