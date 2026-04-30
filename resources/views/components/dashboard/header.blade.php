{{-- ===================================================== --}}
{{-- DASHBOARD HEADER - Baris 1: Logo + Info User         --}}
{{-- Customize: nama user, institusi, avatar di sini      --}}
{{-- ===================================================== --}}



    {{-- Header Row --}}
    <div class="flex items-center justify-between px-6 md:px-10 h-[72px] border-b border-[#e0e0e0]">

        {{-- Logo kiri --}}
        @php
            $logoLink = request()->is('reviewer*') || request()->routeIs('reviewer.*') ? route('reviewer.index') : route('dashboard.index');
        @endphp
        <a href="{{ $logoLink }}" class="flex items-center shrink-0">
            <div class="h-[48px] lg:h-[73px] w-[81px] lg:w-[124px] relative shrink-0">
                <img alt="Patriot Metric" class="absolute inset-0 max-w-none object-cover lg:object-contain pointer-events-none size-full" src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" />
            </div>
        </a>

        {{-- User Info kanan + Avatar Dropdown --}}
        <div class="flex items-center gap-3 shrink-0" 
             x-data="{ 
                 userMenuOpen: false,
                 userData: {
                     nama_pic: '...',
                     nama_pt: '...',
                     avatar: '...',
                     logo_url: null
                 },
                    processUserData(user, p) {
                        const role = (user.role || '').toLowerCase();
                        // 1. Check if redirection is needed for Peserta
                        if (role === 'peserta') {
                            const isAtVerifikasi = window.location.pathname.includes('/verifikasi');
                            if (!p || p.status === 'ACTIVE') {
                                if (!isAtVerifikasi) {
                                    window.location.href = '/verifikasi';
                                    return true;
                                }
                            } else if (isAtVerifikasi && ['IN_PROGRESS', 'SUBMITTED', 'GRADED'].includes(p.status)) {
                                window.location.href = '/dashboard';
                                return true;
                            }
                        }

                     // 2. Map user data for display
                        if (p) {
                            this.userData.nama_pic = p.nama_pic || user.email;
                            this.userData.nama_pt = p.institusi ? p.institusi.nama_institusi : 'Institusi Terdaftar';
                            this.userData.avatar = this.userData.nama_pt.substring(0, 3).toUpperCase();
                            
                            // Try to get logo from identitas.legal_documents (New Schema) or institusi (Old Fallback)
                            let logo = null;
                            if (p.identitas && p.identitas.legal_documents && p.identitas.legal_documents.logo_pt) {
                                logo = p.identitas.legal_documents.logo_pt;
                            } else if (p.institusi && p.institusi.logo_url) {
                                logo = p.institusi.logo_url;
                            }
                            
                            if (logo) {
                                this.userData.logo_url = logo.startsWith('/') ? logo : '/' + logo;
                            }
                        } else {
                           this.userData.nama_pic = user.email;
                           if (role === 'reviewer') {
                               this.userData.nama_pt = 'Reviewer Patriot Metric';
                               this.userData.avatar = 'REV';
                           }
                        }
                     return false;
                 },

                 async init() {
                     try {
                         const token = localStorage.getItem('auth_token');
                         if (!token) {
                             if (!window.location.pathname.includes('/reviewer')) {
                                 window.location.href = '/masuk';
                             }
                             return;
                         }
                         
                         // Try to load from cache first for immediate UI
                         const cached = localStorage.getItem('profile_data_cache');
                         if (cached) {
                             try {
                                 const result = JSON.parse(cached);
                                 this.processUserData(result.user, result.pengumpulan);
                             } catch (e) { console.error('Cache parse error', e); }
                         }

                         const res = await fetch('/api/auth/me', {
                             headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                         });
                         const result = await res.json();
                         if (res.ok && result.success) {
                             // Store to cache
                             localStorage.setItem('profile_data_cache', JSON.stringify(result.data));

                             const p = result.data.pengumpulan;
                             const user = result.data.user;
                             this.processUserData(user, p);
                         } else {
                             // Token invalid or expired
                             if (!window.location.pathname.includes('/reviewer')) {
                                 localStorage.removeItem('auth_token');
                                 localStorage.removeItem('auth_user');
                                 localStorage.removeItem('profile_data_cache');
                                 sessionStorage.clear();
                                 window.location.href = '/masuk';
                             }
                         }
                     } catch (e) { console.error(e); }
                 }
             }" 
             @click.outside="userMenuOpen = false">
            <div class="text-right hidden sm:block">
                <p class="font-bold text-[#1d293d] text-[14px] leading-[20px]" x-text="userData.nama_pic"></p>
                <p class="font-medium text-[#62748e] text-[11px] leading-[16px] uppercase tracking-wide" x-text="userData.nama_pt"></p>
            </div>

            {{-- Avatar Dropdown Trigger --}}
            <div class="relative">
                <button @click="userMenuOpen = !userMenuOpen"
                        class="w-[40px] h-[40px] bg-[#1b5e20] rounded-full flex items-center justify-center shrink-0 hover:bg-[#155017] transition-colors ring-2 ring-transparent hover:ring-[#1b5e20]/20 focus:outline-none overflow-hidden">
                    <template x-if="userData.logo_url">
                        <img :src="userData.logo_url" alt="Logo" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!userData.logo_url">
                        <span class="text-white font-bold text-[13px] tracking-wide" x-text="userData.avatar"></span>
                    </template>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="userMenuOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                     class="absolute right-0 top-full mt-2 w-[200px] bg-white rounded-xl shadow-xl border border-[#f1f5f9] py-1.5 z-[60]"
                     style="display:none;">
                    {{-- User info in dropdown (mobile) --}}
                    <div class="sm:hidden px-4 py-2.5 border-b border-[#f1f5f9] mb-1">
                        <p class="font-bold text-[#1d293d] text-[13px] leading-[18px]" x-text="userData.nama_pic"></p>
                        <p class="text-[#62748e] text-[11px] leading-[14px]" x-text="userData.nama_pt"></p>
                    </div>

                    {{-- Ganti Password Option --}}
                    <button @click="userMenuOpen = false; $dispatch('open-password-modal')"
                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-[13px] font-medium text-[#45556c] hover:text-[#1b5e20] hover:bg-[#f0fdf4] transition-colors">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                        Ganti Password
                    </button>

                    <div class="border-t border-[#f1f5f9] mx-3 my-1"></div>

                    {{-- Keluar Option --}}
                    <button 
                        @click="
                            fetch('/api/auth/logout', { method: 'POST', headers: { 'Authorization': 'Bearer ' + localStorage.getItem('auth_token'), 'Accept': 'application/json' } }).finally(() => {
                                localStorage.removeItem('auth_token');
                                localStorage.removeItem('auth_user');
                                localStorage.removeItem('pengumpulan_status');
                                localStorage.removeItem('rubrik_data_cache');
                                localStorage.removeItem('profile_data_cache');
                                sessionStorage.clear();
                                window.location.href = '/masuk';
                            });
                        "
                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-[13px] font-medium text-[#e53935] hover:text-[#b71c1c] hover:bg-red-50 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

