<x-layouts.dashboard>
    <x-slot:title>Hasil Penilaian</x-slot:title>

    {{--
        ✏️ DATA KATEGORI: Edit di blok x-data di bawah ini.
        Setiap kategori: { name, score, max, color ('green'|'orange'), items: [...] }
        Setiap item: { no, title, score, max, jawaban, tautan, catatan }
    --}}
    <div x-data="{
        openCategories: { 0: true, 1: true, 2: true },
        toggleCategory(idx) { this.openCategories[idx] = !this.openCategories[idx]; },
        categories: [],
        tahun_periode: new Date().getFullYear(),
        institusi: 'Loading...',
        total_score: 0,
        total_max: 0,
        status: 'Loading...',
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
                    this.categories = data.categories;
                    this.tahun_periode = data.tahun_periode;
                    this.institusi = data.institusi;
                    this.total_score = data.total_score;
                    this.total_max = data.total_max;
                    this.status = data.status;
                    this.is_validated = data.is_validated;
                    
                    // Open all categories by default
                    this.categories.forEach((_, idx) => {
                        this.openCategories[idx] = true;
                    });
                } else {
                    if (response.status === 401) {
                        window.location.href = '/masuk';
                    }
                }
            } catch (error) {
                console.error('Failed to fetch results', error);
            }
        }
    }" class="bg-[#f5f5f5] min-h-full py-5 px-4 md:px-8">

        <div class="max-w-[860px] mx-auto space-y-5">

            {{-- ✏️ Banner Hijau Total Penilaian → components/dashboard/hasil/banner.blade.php --}}
            <x-dashboard.hasil.banner />

            {{-- ✏️ Card Status Penilaian → components/dashboard/hasil/status.blade.php --}}
            <x-dashboard.hasil.status />

            {{-- ✏️ Accordion Rincian Poin → components/dashboard/hasil/kategori.blade.php --}}
            <x-dashboard.hasil.kategori />

        </div>
    </div>
</x-layouts.dashboard>
