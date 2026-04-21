{{-- Password Change Modal --}}
<div x-data="{ showPasswordModal: false, showOld: false, showNew: false, showConfirm: false, error: '', success: false }"
     @open-password-modal.window="showPasswordModal = true; error = ''; success = false"
     x-cloak>

    {{-- Backdrop --}}
    <div x-show="showPasswordModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100]"
         @click="showPasswordModal = false"
         style="display:none;"></div>

    {{-- Modal Panel --}}
    <div x-show="showPasswordModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[101] flex justify-center p-4 overflow-y-auto"
         style="display:none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[440px] my-auto relative overflow-hidden" @click.stop>
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-[#f1f5f9]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#e8f5e9] rounded-full flex items-center justify-center">
                        <i data-lucide="lock" class="w-4 h-4 text-[#1b5e20]"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1d293d] text-[15px] leading-[20px]">Ganti Password</h3>
                        <p class="text-[#62748e] text-[12px] leading-[16px]">Perbarui kata sandi akun Anda</p>
                    </div>
                </div>
                <button @click="showPasswordModal = false"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-[#62748e] hover:bg-[#f1f5f9] hover:text-[#1d293d] transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5">
                {{-- Error Alert --}}
                <div x-show="error" x-transition
                     class="flex items-center gap-2 bg-red-50 border border-red-100 text-red-700 rounded-lg px-3 py-2.5 mb-4 text-[13px]"
                     style="display:none;">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span x-text="error"></span>
                </div>

                {{-- Success State --}}
                <div x-show="success" x-transition
                     class="flex flex-col items-center py-6 gap-3"
                     style="display:none;">
                    <div class="w-14 h-14 bg-[#e8f5e9] rounded-full flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-7 h-7 text-[#1b5e20]"></i>
                    </div>
                    <p class="font-semibold text-[#1d293d] text-[15px]">Password berhasil diperbarui!</p>
                    <p class="text-[#62748e] text-[13px] text-center">Gunakan password baru Anda untuk login berikutnya.</p>
                    <button @click="showPasswordModal = false"
                            class="mt-2 bg-[#1b5e20] hover:bg-[#155017] text-white font-semibold text-[14px] px-6 py-2.5 rounded-xl transition-colors">
                        Tutup
                    </button>
                </div>

                {{-- Form --}}
                <form x-show="!success" style="display:none;" @submit.prevent="
                    const old = $el.querySelector('[name=old_password]').value;
                    const newp = $el.querySelector('[name=new_password]').value;
                    const conf = $el.querySelector('[name=confirm_password]').value;
                    error = '';
                    if (!old || !newp || !conf) { error = 'Semua kolom harus diisi.'; return; }
                    if (newp.length < 8) { error = 'Password baru minimal 8 karakter.'; return; }
                    if (newp !== conf) { error = 'Konfirmasi password tidak cocok.'; return; }
                    success = true;
                    $el.reset();"
                      class="space-y-4">
                    {{-- Old Password --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[#1d293d] font-semibold text-[13px]">Password Lama</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#94a3b8]"></i>
                            <input name="old_password"
                                   :type="showOld ? 'text' : 'password'"
                                   placeholder="Masukkan password lama"
                                   class="w-full h-[44px] pl-10 pr-10 border border-[#e2e8f0] rounded-xl text-[14px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] focus:ring-2 focus:ring-[#1b5e20]/10 placeholder:text-[#94a3b8] transition-all" />
                            <button type="button" @click="showOld = !showOld"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#62748e] transition-colors">
                                <i x-show="!showOld" data-lucide="eye" class="w-4 h-4"></i>
                                <i x-show="showOld" data-lucide="eye-off" class="w-4 h-4" style="display:none;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[#1d293d] font-semibold text-[13px]">Password Baru</label>
                        <div class="relative">
                            <i data-lucide="shield" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#94a3b8]"></i>
                            <input name="new_password"
                                   :type="showNew ? 'text' : 'password'"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full h-[44px] pl-10 pr-10 border border-[#e2e8f0] rounded-xl text-[14px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] focus:ring-2 focus:ring-[#1b5e20]/10 placeholder:text-[#94a3b8] transition-all" />
                            <button type="button" @click="showNew = !showNew"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#62748e] transition-colors">
                                <i x-show="!showNew" data-lucide="eye" class="w-4 h-4"></i>
                                <i x-show="showNew" data-lucide="eye-off" class="w-4 h-4" style="display:none;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[#1d293d] font-semibold text-[13px]">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <i data-lucide="shield-check" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#94a3b8]"></i>
                            <input name="confirm_password"
                                   :type="showConfirm ? 'text' : 'password'"
                                   placeholder="Ulangi password baru"
                                   class="w-full h-[44px] pl-10 pr-10 border border-[#e2e8f0] rounded-xl text-[14px] text-[#1d293d] focus:outline-none focus:border-[#1b5e20] focus:ring-2 focus:ring-[#1b5e20]/10 placeholder:text-[#94a3b8] transition-all" />
                            <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#62748e] transition-colors">
                                <i x-show="!showConfirm" data-lucide="eye" class="w-4 h-4"></i>
                                <i x-show="showConfirm" data-lucide="eye-off" class="w-4 h-4" style="display:none;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="w-full h-[44px] bg-[#1b5e20] hover:bg-[#155017] active:bg-[#0f3b15] text-white font-semibold text-[14px] rounded-xl transition-colors shadow-sm mt-2 flex items-center justify-center gap-2">
                       Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
