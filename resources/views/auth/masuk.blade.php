<x-layouts.app :hideNav="true" :hideFooter="true">
    <div class="min-h-screen flex">
        {{-- Left Panel --}}
        <div class="hidden lg:flex w-[45%] relative overflow-hidden items-center">
            <img src="{{ asset('assets/images/IMG_0940.JPG') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background" />
            <div class="absolute inset-0 bg-[#1b5e20] opacity-80"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a230c] to-transparent opacity-50"></div>
            <div class="absolute -top-48 right-[-100px] bg-[rgba(212,175,55,0.3)] blur-[100px] rounded-full size-96"></div>
            <div class="relative px-16 py-16 z-10">
                <div class="-mb-12 mt-4">
                    <img src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" alt="Logo Patriot Metric" class="h-100 w-auto object-contain object-left" />
                </div>
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[50px] leading-[45px] text-white max-w-[500px]">
                    Selamat Datang di Patriot Metric
                </h1>
                <p class="mt-8 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[25px] leading-[29.25px] text-[rgba(255,255,255,0.8)] max-w-[500px]">
                    Masuk untuk mengakses dashboard institusi Anda, memperbarui data rubrik, dan memantau perkembangan nilai bela negara kampus.
                </p>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="flex-1 flex items-center justify-center px-6 md:px-8 py-10 md:py-16 bg-white" x-data="{ 
            email: '', 
            password: '', 
            isLoading: false, 
            errorMessage: '',
            successMessage: '',
            async login() {
                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';
                try {
                    const response = await fetch('/api/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: this.email,
                            password: this.password
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        this.successMessage = result.message;
                        // Simpan token untuk API calls selanjutnya
                        localStorage.setItem('auth_token', result.data.token);
                        localStorage.setItem('auth_user', JSON.stringify(result.data.user));

                        // Redirect berdasarkan status dari server
                        const redirectTo = result.data.redirect_to || '/verifikasi';
                        setTimeout(() => {
                            window.location.href = redirectTo;
                        }, 1000);
                    } else {
                        this.errorMessage = result.message || 'Login gagal. Periksa kembali kredensial Anda.';
                    }
                } catch (error) {
                    this.errorMessage = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                } finally {
                    this.isLoading = false;
                }
            }
        }">
            <div class="w-full max-w-[448px]">
                <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[30px] leading-[36px] text-[#1d293d]">Masuk Akun</h2>
                <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[24px] text-[#62748e]">
                    Silakan masukkan kredensial institusi Anda.
                </p>

                <!-- Notifikasi Status -->
                <div x-show="errorMessage" style="display: none;" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-[14px] text-red-600 font-medium" x-text="errorMessage"></div>
                <div x-show="successMessage" style="display: none;" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-[14px] text-green-600 font-medium" x-text="successMessage"></div>

                <form class="mt-8 space-y-6" @submit.prevent="login">
                    {{-- Email --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-[#90A1B9]"></i>
                        </div>
                        <input
                            type="email"
                            name="email"
                            x-model="email"
                            required
                            placeholder=" "
                            class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                        <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                            Email Resmi Institusi / PIC
                        </label>
                    </div>

                    {{-- Password --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-[#90A1B9]"></i>
                        </div>
                        <input
                            type="password"
                            name="password"
                            x-model="password"
                            required
                            placeholder=" "
                            class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                        <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                            Kata Sandi
                        </label>
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input name="remember" type="checkbox" class="size-4 rounded accent-[#1b5e20]" />
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#45556c]">Ingat saya</span>
                        </label>
                        <a href="#" class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#1b5e20] hover:underline">
                            Lupa sandi?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        x-bind:disabled="isLoading"
                        class="w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] leading-[28px] py-4 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition flex items-center justify-center gap-2 disabled:opacity-50">
                        <span x-show="!isLoading">Masuk Sekarang</span>
                        <span x-show="isLoading" style="display: none;">Memproses...</span>
                    </button>
                </form>

                <p class="mt-6 text-center font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] text-[#45556c]">
                    Belum mendaftarkan institusi?
                    <a href="{{ url('/daftar') }}" class="font-bold text-[#1b5e20] hover:underline">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.app>