<x-layouts.app :hideNav="true" :hideFooter="true">
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-10"
        x-data="{
            reason: '',
            init() {
                const params = new URLSearchParams(window.location.search);
                this.reason = params.get('reason') || 'invalid';
            }
        }">
        <div class="w-full max-w-[480px]">
            <div class="bg-white border border-[#e2e8f0] rounded-3xl shadow-sm p-8 md:p-10 text-center">

                {{-- Expired State --}}
                <template x-if="reason === 'expired'">
                    <div>
                        {{-- Clock Icon --}}
                        <div class="mx-auto w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[28px] leading-[34px] text-[#1d293d] mb-3">
                            Link Verifikasi Kedaluwarsa
                        </h1>

                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] md:text-[16px] leading-[24px] text-[#62748e] mb-8">
                            Link verifikasi email Anda sudah kedaluwarsa. Silakan minta link verifikasi baru melalui halaman cek email atau login kembali.
                        </p>

                        <div class="space-y-3">
                            <a href="/cek-email"
                                class="block w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] leading-[24px] py-3.5 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition text-center">
                                Kirim Ulang Link Verifikasi
                            </a>
                            <a href="/masuk"
                                class="block w-full font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#1b5e20] hover:underline py-2">
                                &larr; Kembali ke halaman login
                            </a>
                        </div>
                    </div>
                </template>

                {{-- Invalid State --}}
                <template x-if="reason !== 'expired'">
                    <div>
                        {{-- X Circle Icon --}}
                        <div class="mx-auto w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[28px] leading-[34px] text-[#1d293d] mb-3">
                            Link Tidak Valid
                        </h1>

                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] md:text-[16px] leading-[24px] text-[#62748e] mb-8">
                            Link sudah digunakan atau tidak valid. Jika Anda belum memverifikasi email, silakan login dan minta link verifikasi baru.
                        </p>

                        <a href="/masuk"
                            class="block w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[16px] leading-[24px] py-3.5 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition text-center">
                            Kembali ke Halaman Login
                        </a>
                    </div>
                </template>

            </div>
        </div>
    </div>
</x-layouts.app>
