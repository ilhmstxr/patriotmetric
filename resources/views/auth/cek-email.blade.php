<x-layouts.app :hideNav="true" :hideFooter="true">
    {{-- Guard: hanya yang UNVERIFIED yang boleh di sini --}}
    <script>
        (function() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.replace('/masuk');
                return;
            }
            const status = localStorage.getItem('user_status') || 'ACTIVE';
            if (status !== 'UNVERIFIED') {
                window.location.replace('/dashboard');
                return;
            }
        })();
    </script>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-10"
        x-data="{
            email: '',
            isResending: false,
            cooldown: 0,
            message: '',
            messageType: '',
            cooldownInterval: null,

            init() {
                const user = JSON.parse(localStorage.getItem('auth_user') || '{}');
                this.email = user.email || '';
            },

            async resendEmail() {
                if (this.isResending || this.cooldown > 0) return;

                this.isResending = true;
                this.message = '';
                this.messageType = '';

                try {
                    const token = localStorage.getItem('auth_token');
                    const response = await fetch('/api/auth/resend-verification', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        this.message = result.message || 'Email verifikasi berhasil dikirim ulang.';
                        this.messageType = 'success';
                        this.startCooldown();
                    } else {
                        this.message = result.message || 'Gagal mengirim ulang email. Silakan coba lagi.';
                        this.messageType = 'error';
                    }
                } catch (error) {
                    this.message = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    this.messageType = 'error';
                } finally {
                    this.isResending = false;
                }
            },

            startCooldown() {
                this.cooldown = 60;
                if (this.cooldownInterval) clearInterval(this.cooldownInterval);
                this.cooldownInterval = setInterval(() => {
                    this.cooldown--;
                    if (this.cooldown <= 0) {
                        this.cooldown = 0;
                        clearInterval(this.cooldownInterval);
                        this.cooldownInterval = null;
                    }
                }, 1000);
            }
        }">
        <div class="w-full max-w-[480px]">
            <div class="bg-white border border-[#e2e8f0] rounded-3xl shadow-sm p-8 md:p-10 text-center">
                {{-- Mail Icon --}}
                <div class="mx-auto w-20 h-20 bg-[#e8f5e9] rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-[#1b5e20]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>

                {{-- Heading --}}
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[28px] leading-[34px] text-[#1d293d] mb-3">
                    Cek Email Anda
                </h1>

                {{-- Description --}}
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] md:text-[16px] leading-[24px] text-[#62748e] mb-2">
                    Kami telah mengirimkan link verifikasi ke alamat email:
                </p>

                {{-- Email Address --}}
                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[16px] text-[#1d293d] mb-6" x-text="email"></p>

                <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[13px] md:text-[14px] leading-[22px] text-[#62748e] mb-8">
                    Silakan buka email Anda dan klik link verifikasi untuk mengaktifkan akun. Link berlaku selama 60 menit.
                </p>

                {{-- Feedback Message --}}
                <div x-show="message" style="display:none;" class="mb-4 px-4 py-3 rounded-xl text-[13px] font-medium font-['Plus_Jakarta_Sans',sans-serif]"
                    :class="messageType === 'success' ? 'bg-[#e8f5e9] text-[#1b5e20]' : 'bg-red-50 text-red-600'"
                    x-text="message">
                </div>

                {{-- Resend Button --}}
                <button
                    @click="resendEmail()"
                    :disabled="isResending || cooldown > 0"
                    class="w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] leading-[24px] py-3.5 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isResending && cooldown === 0">Kirim Ulang Email</span>
                    <span x-show="isResending" style="display:none;">Mengirim...</span>
                    <span x-show="!isResending && cooldown > 0" style="display:none;">
                        Kirim Ulang (<span x-text="cooldown"></span>s)
                    </span>
                </button>

                {{-- Back to Login Link --}}
                <a href="/masuk" class="mt-6 inline-block font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#1b5e20] hover:underline">
                    &larr; Kembali ke halaman login
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
