<x-layouts.dashboard>
    <x-slot:title>Data Profil</x-slot:title>

    <div class="bg-[#f5f5f5] min-h-full py-5 px-4 md:px-8" 
         x-data="{ 
            isLoading: true,
            profileData: {
                pengumpulan: null,
                institusi: null,
                identitas: null,
                agamas: {}
            },
            async init() {
                try {
                    const cacheKey = 'profile_data_cache';
                    const cachedData = localStorage.getItem(cacheKey);

                    if (cachedData) {
                        const data = JSON.parse(cachedData);
                        this.profileData.pengumpulan = data.pengumpulan;
                        this.profileData.institusi = data.pengumpulan?.institusi;
                        this.profileData.identitas = data.pengumpulan?.identitas;
                        
                        if (data.pengumpulan?.agamas) {
                            data.pengumpulan.agamas.forEach(a => {
                                this.profileData.agamas[a.agama.toLowerCase()] = a.jumlah;
                            });
                        }
                        this.isLoading = false;
                        
                        // Background refresh to keep it fresh
                        this.fetchData(false);
                        return;
                    }

                    await this.fetchData(true);
                } catch (e) { console.error(e); }
            },
            async fetchData(showLoading = true) {
                if (showLoading) this.isLoading = true;
                try {
                    const res = await fetch('/api/auth/me', {
                        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('auth_token'), 'Accept': 'application/json' }
                    });
                    const result = await res.json();
                    if (res.ok && result.success) {
                        // Store to cache
                        localStorage.setItem('profile_data_cache', JSON.stringify(result.data));
                        
                        this.profileData.pengumpulan = result.data.pengumpulan;
                        this.profileData.institusi = result.data.pengumpulan?.institusi;
                        this.profileData.identitas = result.data.pengumpulan?.identitas;
                        
                        // Map agamas for easier lookup
                        this.profileData.agamas = {};
                        if (result.data.pengumpulan?.agamas) {
                            result.data.pengumpulan.agamas.forEach(a => {
                                this.profileData.agamas[a.agama.toLowerCase()] = a.jumlah;
                            });
                        }
                    }
                } catch (e) { console.error(e); } finally { this.isLoading = false; }
            }
         }">
        <div class="max-w-[860px] mx-auto space-y-5" x-show="!isLoading" x-cloak>

            {{-- ✏️ Badge periode + tombol Edit Profil --}}
            <x-dashboard.profil.periode-bar />

            {{-- ✏️ Section Visi & Misi → components/dashboard/profil/visi-misi.blade.php --}}
            <x-dashboard.profil.visi-misi />

            {{-- ✏️ Section Data Institusi → components/dashboard/profil/institusi.blade.php --}}
            <x-dashboard.profil.institusi />

            {{-- ✏️ Section Data SDM → components/dashboard/profil/sdm.blade.php --}}
            <x-dashboard.profil.sdm />

            {{-- ✏️ Section Data Mahasiswa → components/dashboard/profil/mahasiswa.blade.php --}}
            <x-dashboard.profil.mahasiswa />

            {{-- ✏️ Section Demografi Agama → components/dashboard/profil/demografi.blade.php --}}
            <x-dashboard.profil.demografi />

            {{-- ✏️ Section Data PIC → components/dashboard/profil/pic.blade.php --}}
            <x-dashboard.profil.pic />

        </div>

        {{-- Loading Skeleton --}}
        <div class="max-w-[860px] mx-auto space-y-5" x-show="isLoading">
            <div class="h-20 bg-gray-200 animate-pulse rounded-lg"></div>
            <div class="h-40 bg-gray-200 animate-pulse rounded-lg"></div>
            <div class="h-40 bg-gray-200 animate-pulse rounded-lg"></div>
        </div>
    </div>
</x-layouts.dashboard>
