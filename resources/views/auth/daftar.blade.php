<x-layouts.app :hideNav="true" :hideFooter="true">
    {{-- Guard: jika sudah login, redirect sesuai status --}}
    <script>
        (function() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                const user = JSON.parse(localStorage.getItem('auth_user') || '{}');
                const role = (user.role || '').toLowerCase();
                if (role === 'reviewer') {
                    window.location.replace('/reviewer');
                    return;
                }

                const status = localStorage.getItem('pengumpulan_status') || 'ACTIVE';
                if (status === 'ACTIVE') {
                    window.location.replace('/verifikasi');
                } else {
                    window.location.replace('/dashboard');
                }
            }
        })();
    </script>
    <div class="min-h-screen flex" x-data="{ 
        agree: false, 
        isFormValid: false,
        isLoading: false,
        errorMessage: '',
        successMessage: '',
        formData: {
            nama_pt: '',
            jenis_pt: '',
            nama_pic: '',
            jabatan_pic: '',
            no_hp_pic: '',
            email: '',
            password: '',
            password_confirmation: ''
        },
        async register() {
            if (!this.agree || !this.$refs.form.checkValidity()) return;
            
            // Bypass confirmasi password jika form tidak memiliki field tersebut
            this.formData.password_confirmation = this.formData.password;

            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    this.successMessage = result.message;
                    setTimeout(() => {
                        window.location.href = '/masuk';
                    }, 2000);
                } else {
                    if (result.errors) {
                        this.errorMessage = Object.values(result.errors)[0][0];
                    } else {
                        this.errorMessage = result.message || 'Registrasi gagal. Periksa kembali data Anda.';
                    }
                }
            } catch (error) {
                this.errorMessage = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
            } finally {
                this.isLoading = false;
            }
        }
    }">
        {{-- Left Panel --}}
        <div class="hidden lg:flex w-[45%] relative sticky top-0 h-screen overflow-hidden items-center">
            <img src="{{ asset('assets/images/IMG_0940.JPG') }}" class="absolute inset-0 w-full h-full object-cover" alt="Background" />
            <div class="absolute inset-0 bg-[#1b5e20] opacity-80"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a230c] to-transparent opacity-50"></div>
            <div class="absolute -top-48 right-[-100px] bg-[rgba(212,175,55,0.3)] blur-[100px] rounded-full size-96"></div>
            <div class="relative px-16 py-16 z-10 w-full">
                <div class="-mb-12 mt-4">
                    <img src="{{ asset('assets/images/b89aca8b9cc2d0494234bedd13382da054b48ab6.png') }}" alt="Logo Patriot Metric" class="h-100 w-auto object-contain object-left" />
                </div>
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[50px] leading-[45px] text-white max-w-[500px]">
                    Jadilah Bagian dari Perubahan
                </h1>
                <p class="mt-8 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[25px] leading-[29.25px] text-[rgba(255,255,255,0.8)] max-w-[500px]">
                    Dengan mendaftarkan institusi Anda, Anda telah mengambil langkah nyata dalam membina karakter bela negara generasi penerus bangsa.
                </p>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="flex-1 flex items-start justify-center px-6 md:px-8 py-10 md:py-12 bg-white overflow-y-auto">
            <div class="w-full max-w-[576px]">
                <a href="{{ url('/masuk') }}" class="font-['Plus_Jakarta_Sans',sans-serif] font-semibold text-[14px] text-[#1B5E20] hover:underline">
                    Sudah punya akun? Masuk
                </a>

                <h2 class="mt-6 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] md:text-[30px] leading-[36px] text-[#1d293d]">Daftarkan Institusi</h2>
                <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[14px] md:text-[16px] leading-[24px] text-[#62748e]">
                    Lengkapi data berikut untuk membuat akun institusi.
                </p>

                <!-- Notifikasi Status -->
                <div x-show="errorMessage" style="display: none;" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-[14px] text-red-600 font-medium" x-text="errorMessage"></div>
                <div x-show="successMessage" style="display: none;" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-[14px] text-green-600 font-medium" x-text="successMessage"></div>

                <form class="mt-8 space-y-8" @submit.prevent="register" x-ref="form" @input="isFormValid = $refs.form.checkValidity()" @change="isFormValid = $refs.form.checkValidity()">
                    {{-- Data Institusi --}}
                    <div>
                        <div class="flex items-center gap-2 pb-3 border-b border-[#f1f5f9] mb-6">
                            <i data-lucide="building-2" class="w-5 h-5 text-[#1B5E20]"></i>
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] text-[#1d293d]">Data Institusi</span>
                        </div>
                        <div class="space-y-4">
                            {{-- Field Nama Institusi --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="building-2" class="w-5 h-5 text-[#90A1B9]"></i>
                                </div>
                                <input name="nama_institusi" type="text" x-model="formData.nama_pt" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Nama Perguruan Tinggi
                                </label>
                            </div>
                            
                            {{-- Field Jenis Institusi --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="building-2" class="w-5 h-5 text-[#90A1B9]"></i>
                                </div>
                                <select name="jenis_institusi" x-model="formData.jenis_pt" required class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition appearance-none">
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
                            <i data-lucide="user" class="w-5 h-5 text-[#1B5E20]"></i>
                            <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] text-[#1d293d]">Data PIC</span>
                        </div>
                        <div class="space-y-4">
                            {{-- Field Nama PIC --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="user" class="w-5 h-5 text-[#90A1B9]"></i>
                                </div>
                                <input name="nama_pic" type="text" x-model="formData.nama_pic" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Nama PIC Peserta
                                </label>
                            </div>
                            
                            {{-- Field Jabatan PIC --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="briefcase" class="w-5 h-5 text-[#90A1B9]"></i>
                                </div>
                                <input name="jabatan_pic" type="text" x-model="formData.jabatan_pic" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Jabatan PIC Peserta
                                </label>
                            </div>
                            
                            {{-- Field No HP --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="phone" class="w-5 h-5 text-[#90A1B9]"></i>
                                </div>
                                <input name="no_hp" type="tel" x-model="formData.no_hp_pic" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    No HP/ WhatsApp Aktif PIC Peserta
                                </label>
                            </div>

                            {{-- Field Email --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="mail" class="w-5 h-5 text-[#90A1B9]"></i>
                                </div>
                                <input name="email" type="email" x-model="formData.email" required placeholder="dosen@upnjatim.ac.id" class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] placeholder-hide-on-blur transition" />
                                <label class="absolute left-12 top-4 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#62748e] transition-all peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:text-[12px] peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-[12px] pointer-events-none">
                                    Email PIC
                                </label>
                            </div>

                            {{-- Field Password --}}
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="lock" class="w-5 h-5 text-[#90A1B9]"></i>
                                </div>
                                <input name="password" type="password" x-model="formData.password" required placeholder=" " class="peer w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-[20px] pl-12 pr-4 pt-6 pb-2 font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] transition" />
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
                        x-bind:disabled="!agree || !isFormValid || isLoading"
                    >
                        <span x-show="!isLoading">Kirim Pendaftaran</span>
                        <span x-show="isLoading" style="display: none;">Memproses...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
