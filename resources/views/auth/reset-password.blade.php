<x-layouts.app :hideNav="true" :hideFooter="true">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden py-12">
        {{-- Full Background --}}
        <img src="{{ asset('assets/images/mhs.webp') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background" />
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div class="relative z-10 flex flex-col lg:flex-row w-full max-w-[1300px] mx-auto items-center justify-between px-6 lg:px-12 gap-12">
            {{-- Left Content --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center text-center lg:text-left">
                <div class="-mb-12 mt-4 flex justify-center lg:justify-start">
                    <img src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.webp') }}" alt="Logo Patriot Metric" class="h-100 w-auto object-contain" />
                </div>
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] md:text-[50px] leading-[1.1] text-white max-w-[500px] mx-auto lg:mx-0">
                    Reset Kata Sandi
                </h1>
                <p class="mt-6 md:mt-8 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] md:text-[22px] leading-relaxed text-[rgba(255,255,255,0.9)] max-w-[500px] mx-auto lg:mx-0">
                    Masukkan kata sandi baru untuk akun Anda. Pastikan kata sandi minimal 8 karakter dan mudah diingat.
                </p>
            </div>

            {{-- Right Floating Form --}}
            <div class="w-full lg:w-[480px] bg-white rounded-[32px] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] p-8 md:p-10 border border-white/20"
                x-data="{
                    password: '',
                    password_confirmation: '',
                    showPassword: false,
                    isLoading: false,
                    errorMessage: '',
                    tokenExpired: false,
                    get isFormValid() {
                        return this.password.length >= 8 && this.password.length <= 128 && this.password === this.password_confirmation;
                    },
                    get passwordTooShort() {
                        return this.password.length > 0 && this.password.length < 8;
                    },
                    get passwordTooLong() {
                        return this.password.length > 128;
                    },
                    get passwordMismatch() {
                        return this.password_confirmation.length > 0 && this.password !== this.password_confirmation;
                    },
                    async resetPassword() {
                        if (!this.isFormValid) return;

                        this.isLoading = true;
                        this.errorMessage = '';
                        this.tokenExpired = false;

                        const token = '{{ $token }}';
                        const email = new URLSearchParams(window.location.search).get('email');

                        if (!email) {
                            this.errorMessage = 'Email tidak ditemukan di URL. Silakan gunakan link dari email Anda.';
                            this.isLoading = false;
                            return;
                        }

                        try {
                            const response = await fetch('/api/auth/reset-password', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    token: token,
                                    email: email,
                                    password: this.password,
                                    password_confirmation: this.password_confirmation
                                })
                            });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Password Berhasil Direset!',
                                    text: result.message || 'Kata sandi Anda telah diperbarui. Silakan login dengan kata sandi baru.',
                                    confirmButtonColor: '#1b5e20'
                                }).then(() => {
                                    window.location.href = '/masuk';
                                });
                            } else {
                                const message = result.message || 'Gagal mereset password. Silakan coba lagi.';

                                if (response.status === 422 && (message.toLowerCase().includes('kedaluwarsa') || message.toLowerCase().includes('expired') || message.toLowerCase().includes('tidak valid') || message.toLowerCase().includes('invalid'))) {
                                    this.tokenExpired = true;
                                    this.errorMessage = message;
                                } else {
                                    this.errorMessage = message;
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal Reset Password',
                                        text: message,
                                        confirmButtonColor: '#1b5e20'
                                    });
                                }
                            }
                        } catch (error) {
                            this.errorMessage = 'Kesalahan jaringan. Silakan coba lagi.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan Jaringan',
                                text: this.errorMessage,
                                confirmButtonColor: '#1b5e20'
                            });
                        } finally {
                            this.isLoading = false;
                        }
                    }
                }">
                <div class="w-full">
                    {{-- Normal Form State --}}
                    <div x-show="!tokenExpired">
                        <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[30px] leading-[36px] text-[#1d293d]">Buat Kata Sandi Baru</h2>
                        <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[24px] text-[#62748e]">
                            Masukkan kata sandi baru untuk akun Anda.
                        </p>

                        <form class="mt-8 space-y-6" @submit.prevent="resetPassword">
                            {{-- Password Baru --}}
                            <div>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <i data-lucide="lock" class="w-5 h-5 text-[#90A1B9]"></i>
                                    </div>
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        x-model="password"
                                        required
                                        minlength="8"
                                        maxlength="128"
                                        placeholder=" "
                                        class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-12 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                    <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                        Kata Sandi Baru
                                    </label>
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-[#90A1B9] hover:text-[#45556c] transition-colors focus:outline-none">
                                        <i x-show="!showPassword" data-lucide="eye-closed" class="w-5 h-5"></i>
                                        <i x-show="showPassword" style="display: none;" data-lucide="eye" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <p x-show="passwordTooShort" x-cloak class="mt-2 ml-4 font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-red-500">
                                    Password minimal 8 karakter
                                </p>
                                <p x-show="passwordTooLong" x-cloak class="mt-2 ml-4 font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-red-500">
                                    Password maksimal 128 karakter
                                </p>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <i data-lucide="lock" class="w-5 h-5 text-[#90A1B9]"></i>
                                    </div>
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        x-model="password_confirmation"
                                        required
                                        minlength="8"
                                        maxlength="128"
                                        placeholder=" "
                                        class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-12 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                    <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                        Konfirmasi Kata Sandi
                                    </label>
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-[#90A1B9] hover:text-[#45556c] transition-colors focus:outline-none">
                                        <i x-show="!showPassword" data-lucide="eye-closed" class="w-5 h-5"></i>
                                        <i x-show="showPassword" style="display: none;" data-lucide="eye" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <p x-show="passwordMismatch" x-cloak class="mt-2 ml-4 font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-red-500">
                                    Konfirmasi password tidak cocok
                                </p>
                            </div>

                            {{-- Error Message --}}
                            <p x-show="errorMessage && !tokenExpired" x-text="errorMessage" x-cloak class="font-['Plus_Jakarta_Sans',sans-serif] text-[14px] text-red-500 text-center"></p>

                            {{-- Submit --}}
                            <button
                                type="submit"
                                x-bind:disabled="isLoading || !isFormValid"
                                class="w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] leading-[28px] py-4 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition flex items-center justify-center gap-2 disabled:opacity-50">
                                <span x-show="!isLoading">Reset Kata Sandi</span>
                                <span x-show="isLoading" style="display: none;">Memproses...</span>
                            </button>
                        </form>
                    </div>

                    {{-- Token Expired/Invalid State --}}
                    <div x-show="tokenExpired" x-cloak>
                        <div class="text-center py-6">
                            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                                <i data-lucide="alert-triangle" class="w-8 h-8 text-red-500"></i>
                            </div>
                            <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[30px] leading-[36px] text-[#1d293d]">Link Tidak Valid</h2>
                            <p class="mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[24px] text-[#62748e]" x-text="errorMessage"></p>

                            <a href="/lupa-sandi"
                                class="mt-6 inline-flex items-center justify-center w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] leading-[28px] py-4 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition gap-2">
                                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                                Minta Link Baru
                            </a>
                        </div>
                    </div>

                    <p class="mt-6 text-center font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] text-[#45556c]">
                        Sudah ingat kata sandi?
                        <a href="/masuk" class="font-bold text-[#1b5e20] hover:underline">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
