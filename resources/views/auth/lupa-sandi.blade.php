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
                    Lupa Kata Sandi
                </h1>
                <p class="mt-6 md:mt-8 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] md:text-[22px] leading-relaxed text-[rgba(255,255,255,0.9)] max-w-[500px] mx-auto lg:mx-0">
                    Masukkan email yang terdaftar untuk menerima link reset kata sandi Anda.
                </p>
            </div>

            {{-- Right Floating Form --}}
            <div class="w-full lg:w-[480px] bg-white rounded-[32px] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] p-8 md:p-10 border border-white/20"
                x-data="{
                    email: '',
                    isLoading: false,
                    errorMessage: '',
                    successMessage: '',
                    async sendResetLink() {
                        this.isLoading = true;
                        this.errorMessage = '';
                        this.successMessage = '';
                        try {
                            const response = await fetch('/api/auth/forgot-password', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    email: this.email
                                })
                            });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                this.successMessage = 'Link reset telah dikirim ke email Anda';
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: this.successMessage,
                                    confirmButtonColor: '#1b5e20'
                                });
                            } else {
                                this.errorMessage = result.message || 'Gagal mengirim link reset. Silakan coba lagi.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: this.errorMessage,
                                    confirmButtonColor: '#1b5e20'
                                });
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
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[30px] leading-[36px] text-[#1d293d]">Lupa Sandi</h2>
                    <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[24px] text-[#62748e]">
                        Masukkan email Anda untuk menerima link reset kata sandi.
                    </p>

                    {{-- Success State --}}
                    <template x-if="successMessage">
                        <div class="mt-6 bg-[#f0fdf4] border border-[#bbf7d0] rounded-[16px] p-6 text-center">
                            <div class="flex justify-center mb-3">
                                <i data-lucide="mail-check" class="w-12 h-12 text-[#1b5e20]"></i>
                            </div>
                            <p class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[16px] text-[#166534]" x-text="successMessage"></p>
                            <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] text-[#45556c]">
                                Periksa inbox dan folder spam email Anda.
                            </p>
                        </div>
                    </template>

                    {{-- Form --}}
                    <form class="mt-8 space-y-6" @submit.prevent="sendResetLink" x-show="!successMessage">
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

                        {{-- Submit --}}
                        <button
                            type="submit"
                            x-bind:disabled="isLoading"
                            class="w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] leading-[28px] py-4 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition flex items-center justify-center gap-2 disabled:opacity-50">
                            <span x-show="!isLoading">Kirim Link Reset</span>
                            <span x-show="isLoading" style="display: none;">Memproses...</span>
                        </button>
                    </form>

                    <p class="mt-6 text-center font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] text-[#45556c]">
                        <a href="/masuk" class="font-bold text-[#1b5e20] hover:underline">← Kembali ke halaman login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
