<x-layouts.app :hideNav="true" :hideFooter="true">
    <div class="min-h-screen flex" x-data="{ agree: false, isFormValid: false }">
        {{-- Left Panel --}}
        <div class="hidden lg:flex w-[45%] bg-[#1b5e20] sticky top-0 h-screen overflow-hidden items-center">
            <div class="absolute inset-0 opacity-10"></div>
            <div class="absolute -top-48 right-[-100px] bg-[rgba(212,175,55,0.2)] blur-[100px] rounded-full size-96"></div>
            <div class="relative px-16 py-16">
                <div class="-mb-12 mt-4">
                    <img src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" alt="Logo Patriot Metric" class="h-60 w-auto object-contain object-left" />
                </div>
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[36px] leading-[45px] text-white max-w-[321px]">
                    Jadilah Bagian dari Perubahan
                </h1>
                <p class="mt-8 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[18px] leading-[29.25px] text-[rgba(255,255,255,0.8)] max-w-[360px]">
                    Dengan mendaftarkan institusi Anda, Anda telah mengambil langkah nyata dalam membina karakter bela negara generasi penerus bangsa.
                </p>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="flex-1 flex items-start justify-center px-8 py-12 bg-white overflow-y-auto">
            <div class="w-full max-w-[576px]">
                <a href="{{ url('/masuk') }}" class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#62748e] hover:underline">
                    Sudah punya akun? Masuk
                </a>

                <h2 class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[30px] leading-[36px] text-[#1d293d]">Daftarkan Institusi</h2>
                <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] leading-[24px] text-[#62748e]">
                    Lengkapi data berikut untuk membuat akun institusi.
                </p>

                <form class="mt-8 space-y-8" action="#" method="POST" x-ref="form" @input="isFormValid = $refs.form.checkValidity()" @change="isFormValid = $refs.form.checkValidity()">
                    @csrf
                    {{-- Data Institusi --}}
                    <div>
                        <div class="flex items-center gap-2 pb-3 border-b border-[#f1f5f9] mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20" fill="#1B5E20"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M192 112C183.2 112 176 119.2 176 128L176 512C176 520.8 183.2 528 192 528L272 528L272 448C272 430.3 286.3 416 304 416L336 416C353.7 416 368 430.3 368 448L368 528L448 528C456.8 528 464 520.8 464 512L464 128C464 119.2 456.8 112 448 112L192 112zM128 128C128 92.7 156.7 64 192 64L448 64C483.3 64 512 92.7 512 128L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 128zM224 176C224 167.2 231.2 160 240 160L272 160C280.8 160 288 167.2 288 176L288 208C288 216.8 280.8 224 272 224L240 224C231.2 224 224 216.8 224 208L224 176zM368 160L400 160C408.8 160 416 167.2 416 176L416 208C416 216.8 408.8 224 400 224L368 224C359.2 224 352 216.8 352 208L352 176C352 167.2 359.2 160 368 160zM224 304C224 295.2 231.2 288 240 288L272 288C280.8 288 288 295.2 288 304L288 336C288 344.8 280.8 352 272 352L240 352C231.2 352 224 344.8 224 336L224 304zM368 288L400 288C408.8 288 416 295.2 416 304L416 336C416 344.8 408.8 352 400 352L368 352C359.2 352 352 344.8 352 336L352 304C352 295.2 359.2 288 368 288z"/></svg>
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] text-[#1d293d]">Data Institusi</span>
                        </div>
                        <div class="space-y-4">
                            {{-- Field Nama Institusi --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20" fill="#90A1B9"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M192 112C183.2 112 176 119.2 176 128L176 512C176 520.8 183.2 528 192 528L272 528L272 448C272 430.3 286.3 416 304 416L336 416C353.7 416 368 430.3 368 448L368 528L448 528C456.8 528 464 520.8 464 512L464 128C464 119.2 456.8 112 448 112L192 112zM128 128C128 92.7 156.7 64 192 64L448 64C483.3 64 512 92.7 512 128L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 128zM224 176C224 167.2 231.2 160 240 160L272 160C280.8 160 288 167.2 288 176L288 208C288 216.8 280.8 224 272 224L240 224C231.2 224 224 216.8 224 208L224 176zM368 160L400 160C408.8 160 416 167.2 416 176L416 208C416 216.8 408.8 224 400 224L368 224C359.2 224 352 216.8 352 208L352 176C352 167.2 359.2 160 368 160zM224 304C224 295.2 231.2 288 240 288L272 288C280.8 288 288 295.2 288 304L288 336C288 344.8 280.8 352 272 352L240 352C231.2 352 224 344.8 224 336L224 304zM368 288L400 288C408.8 288 416 295.2 416 304L416 336C416 344.8 408.8 352 400 352L368 352C359.2 352 352 344.8 352 336L352 304C352 295.2 359.2 288 368 288z"/></svg>
                                </div>
                                <input name="nama_institusi" type="text" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Nama Perguruan Tinggi
                                </label>
                            </div>
                            
                            {{-- Field Jenis Institusi --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20" fill="#90A1B9"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M192 112C183.2 112 176 119.2 176 128L176 512C176 520.8 183.2 528 192 528L272 528L272 448C272 430.3 286.3 416 304 416L336 416C353.7 416 368 430.3 368 448L368 528L448 528C456.8 528 464 520.8 464 512L464 128C464 119.2 456.8 112 448 112L192 112zM128 128C128 92.7 156.7 64 192 64L448 64C483.3 64 512 92.7 512 128L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 128zM224 176C224 167.2 231.2 160 240 160L272 160C280.8 160 288 167.2 288 176L288 208C288 216.8 280.8 224 272 224L240 224C231.2 224 224 216.8 224 208L224 176zM368 160L400 160C408.8 160 416 167.2 416 176L416 208C416 216.8 408.8 224 400 224L368 224C359.2 224 352 216.8 352 208L352 176C352 167.2 359.2 160 368 160zM224 304C224 295.2 231.2 288 240 288L272 288C280.8 288 288 295.2 288 304L288 336C288 344.8 280.8 352 272 352L240 352C231.2 352 224 344.8 224 336L224 304zM368 288L400 288C408.8 288 416 295.2 416 304L416 336C416 344.8 408.8 352 400 352L368 352C359.2 352 352 344.8 352 336L352 304C352 295.2 359.2 288 368 288z"/></svg>
                                </div>
                                <select name="jenis_institusi" required class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition appearance-none">
                                    <option value="" disabled selected hidden></option>
                                    <option value="PTN">PTN</option>
                                    <option value="PTS">PTS</option>
                                    <option value="PTK">PTK</option>
                                </select>
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium transition-all pointer-events-none text-[14px] text-[#62748e] peer-valid:top-2 peer-valid:text-[12px] peer-invalid:top-5 peer-invalid:text-[14px] peer-focus:top-2 peer-focus:text-[12px]">
                                    Jenis Perguruan Tinggi
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Data PIC --}}
                    <div>
                        <div class="flex items-center gap-2 pb-3 border-b border-[#f1f5f9] mb-6">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M13.3333 17.5V15.8333C13.3333 14.9493 12.9821 14.1014 12.3569 13.4763C11.7318 12.8512 10.8841 12.5 10 12.5H5C4.11594 12.5 3.26811 12.8512 2.64299 13.4763C2.01787 14.1014 1.66667 14.9493 1.66667 15.8333V17.5" stroke="#1B5E20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                <path d="M7.5 9.16667C9.34095 9.16667 10.8333 7.67428 10.8333 5.83333C10.8333 3.99238 9.34095 2.5 7.5 2.5C5.65905 2.5 4.16667 3.99238 4.16667 5.83333C4.16667 7.67428 5.65905 9.16667 7.5 9.16667Z" stroke="#1B5E20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            </svg>
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] text-[#1d293d]">Data PIC</span>
                        </div>
                        <div class="space-y-4">
                            {{-- Field Nama PIC --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M13.3333 17.5V15.8333C13.3333 14.9493 12.9821 14.1014 12.3569 13.4763C11.7318 12.8512 10.8841 12.5 10 12.5H5C4.11594 12.5 3.26811 12.8512 2.64299 13.4763C2.01787 14.1014 1.66667 14.9493 1.66667 15.8333V17.5" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                        <path d="M7.5 9.16667C9.34095 9.16667 10.8333 7.67428 10.8333 5.83333C10.8333 3.99238 9.34095 2.5 7.5 2.5C5.65905 2.5 4.16667 3.99238 4.16667 5.83333C4.16667 7.67428 5.65905 9.16667 7.5 9.16667Z" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                    </svg>
                                </div>
                                <input name="nama_pic" type="text" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Nama PIC Peserta
                                </label>
                            </div>
                            
                            {{-- Field Jabatan PIC --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20" height="20" fill="#90A1B9"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M192 112C183.2 112 176 119.2 176 128L176 512C176 520.8 183.2 528 192 528L272 528L272 448C272 430.3 286.3 416 304 416L336 416C353.7 416 368 430.3 368 448L368 528L448 528C456.8 528 464 520.8 464 512L464 128C464 119.2 456.8 112 448 112L192 112zM128 128C128 92.7 156.7 64 192 64L448 64C483.3 64 512 92.7 512 128L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 128zM224 176C224 167.2 231.2 160 240 160L272 160C280.8 160 288 167.2 288 176L288 208C288 216.8 280.8 224 272 224L240 224C231.2 224 224 216.8 224 208L224 176zM368 160L400 160C408.8 160 416 167.2 416 176L416 208C416 216.8 408.8 224 400 224L368 224C359.2 224 352 216.8 352 208L352 176C352 167.2 359.2 160 368 160zM224 304C224 295.2 231.2 288 240 288L272 288C280.8 288 288 295.2 288 304L288 336C288 344.8 280.8 352 272 352L240 352C231.2 352 224 344.8 224 336L224 304zM368 288L400 288C408.8 288 416 295.2 416 304L416 336C416 344.8 408.8 352 400 352L368 352C359.2 352 352 344.8 352 336L352 304C352 295.2 359.2 288 368 288z"/></svg>
                                </div>
                                <input name="jabatan_pic" type="text" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Jabatan PIC Peserta
                                </label>
                            </div>
                            
                            {{-- Field No HP --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M18.3083 14.275V16.775C18.3095 17.0091 18.2627 17.2409 18.1706 17.4553C18.0785 17.6697 17.9432 17.862 17.7733 18.0193C17.6033 18.1767 17.4023 18.2959 17.1826 18.3694C16.9628 18.4428 16.7293 18.469 16.4967 18.4458C13.918 18.1675 11.4396 17.3064 9.25835 15.9333C7.23201 14.6864 5.51367 12.968 4.26668 10.9417C2.88835 8.75064 2.02695 6.26049 1.75418 3.67C1.73102 3.43812 1.757 3.20423 1.82991 2.98429C1.90282 2.76435 2.02112 2.56316 2.17736 2.39302C2.3336 2.22288 2.52451 2.08754 2.73754 1.99529C2.95057 1.90303 3.18102 1.85601 3.41418 1.85748H5.91418C6.32581 1.85348 6.72499 1.99546 7.03839 2.25784C7.35179 2.52022 7.55877 2.88583 7.62418 3.29248C7.74604 4.10507 7.96022 4.90116 8.26251 5.66581C8.37705 5.95002 8.40784 6.26134 8.35142 6.56242C8.29499 6.8635 8.15371 7.14175 7.94418 7.36415L6.86585 8.44248C8.02163 10.4812 9.68549 12.1451 11.7242 13.3008L12.8025 12.2225C13.0249 12.013 13.3032 11.8717 13.6043 11.8153C13.9053 11.7588 14.2167 11.7896 14.5009 11.9042C15.2655 12.2065 16.0616 12.4206 16.8742 12.5425C17.2853 12.6084 17.6547 12.8189 17.918 13.1375C18.1813 13.4561 18.3208 13.861 18.3083 14.275Z" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                    </svg>
                                </div>
                                <input name="no_hp" type="tel" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    No HP/ WhatsApp Aktif PIC Peserta
                                </label>
                            </div>

                            {{-- Field Email --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M3.33333 3.33333H16.6667C17.5833 3.33333 18.3333 4.08333 18.3333 4.99999V15C18.3333 15.9167 17.5833 16.6667 16.6667 16.6667H3.33333C2.41667 16.6667 1.66667 15.9167 1.66667 15V4.99999C1.66667 4.08333 2.41667 3.33333 3.33333 3.33333Z" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                        <path d="M18.3333 5L10 10.8333L1.66667 5" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                    </svg>
                                </div>
                                <input name="email" type="email" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Email PIC
                                </label>
                            </div>

                            {{-- Field Password --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M15.8333 9.16667H4.16667C3.24619 9.16667 2.5 9.91286 2.5 10.8333V16.6667C2.5 17.5871 3.24619 18.3333 4.16667 18.3333H15.8333C16.7538 18.3333 17.5 17.5871 17.5 16.6667V10.8333C17.5 9.91286 16.7538 9.16667 15.8333 9.16667Z" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                        <path d="M5.83333 9.16667V5.83333C5.83333 4.72827 6.27232 3.66846 7.05372 2.88706C7.83512 2.10565 8.89493 1.66667 10 1.66667C11.1051 1.66667 12.1649 2.10565 12.9463 2.88706C13.7277 3.66846 14.1667 4.72827 14.1667 5.83333V9.16667" stroke="#90A1B9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                                    </svg>
                                </div>
                                <input name="password" type="password" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Password
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Agreement --}}
                    <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-2xl p-5 flex gap-4">
                        <input
                            type="checkbox"
                            x-model="agree"
                            required
                            class="size-5 mt-1 accent-[#1b5e20] shrink-0"
                        />
                        <div class="font-['Plus_Jakarta_Sans',sans-serif] text-[14px] leading-[22.75px] text-[#45556c]">
                            <p class="font-medium mb-3">
                                Sebelum menekan tombol submit, pastikan seluruh data yang Anda isi sudah benar dan lengkap. Setelah formulir dikirim, data tidak dapat diubah kembali.
                            </p>
                            <a href="https://bit.ly/PEDOMANPATRIOTMETRIC" target="_blank" class="mt-3 font-bold text-[#1b5e20]">
                                Pedoman Patriot Metric UPN Veteran Jatim &rarr;
                            </a>
                            <p class="mt-3 font-medium">
                                Dengan melanjutkan, Anda menyatakan bahwa data yang Anda berikan adalah benar dan bahwa Anda telah memahami isi handbook serta siap mengikuti proses assessment sesuai ketentuan yang berlaku.
                            </p>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full bg-[#1b5e20] text-white font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] leading-[28px] py-4 rounded-[20px] shadow-[0px_20px_25px_0px_rgba(27,94,32,0.2)] hover:bg-[#174d1a] transition flex items-center justify-center gap-2 disabled:opacity-50"
                        x-bind:disabled="!agree || !isFormValid"
                    >
                        Kirim Pendaftaran
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M4.16667 10H15.8333" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                            <path d="M10 4.16667L15.8333 10L10 15.8333" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.66667" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
