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
        <div class="flex-1 flex items-center justify-center px-8 py-16 bg-white">
            <div class="w-full max-w-[448px]">
                <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[30px] leading-[36px] text-[#1d293d]">Masuk Akun</h2>
                <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[24px] text-[#62748e]">
                    Silakan masukkan kredensial institusi Anda.
                </p>

                <!-- {{-- Testing Hint --}}
                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-[12px] text-amber-800 font-medium">
                        <span class="font-bold">Info Testing:</span> Gunakan email <code class="bg-white px-1 py-0.5 rounded border border-amber-100 font-mono text-[#1b5e20] font-bold">admin@upnjatim.ac.id</code> untuk langsung masuk ke halaman Dashboard Utama. Email selain itu akan masuk ke Form Daftar Ulang.
                    </p>
                </div> -->

                <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf
                    {{-- Email --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M3.33333 3.33333H16.6667C17.5833 3.33333 18.3333 4.08333 18.3333 4.99999V15C18.3333 15.9167 17.5833 16.6667 16.6667 16.6667H3.33333C2.41667 16.6667 1.66667 15.9167 1.66667 15V4.99999C1.66667 4.08333 2.41667 3.33333 3.33333 3.33333Z" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                <path d="M18.3333 5L10 10.8333L1.66667 5" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            </svg>
                        </div>
                        <input
                            type="email"
                            name="email"
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
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M15.8333 9.16667H4.16667C3.24619 9.16667 2.5 9.91286 2.5 10.8333V16.6667C2.5 17.5871 3.24619 18.3333 4.16667 18.3333H15.8333C16.7538 18.3333 17.5 17.5871 17.5 16.6667V10.8333C17.5 9.91286 16.7538 9.16667 15.8333 9.16667Z" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                <path d="M5.83333 9.16667V5.83333C5.83333 4.72827 6.27232 3.66846 7.05372 2.88706C7.83512 2.10565 8.89493 1.66667 10 1.66667C11.1051 1.66667 12.1649 2.10565 12.9463 2.88706C13.7277 3.66846 14.1667 4.72827 14.1667 5.83333V9.16667" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            </svg>
                        </div>
                        <input
                            type="password"
                            name="password"
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
                        class="w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] leading-[28px] py-4 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition flex items-center justify-center gap-2">
                        Masuk Sekarang
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