{{-- ===================================================== --}}
{{-- HASIL: Rincian Poin per Kategori (Accordion Cards)   --}}
{{-- ===================================================== --}}
<div class="space-y-4">
    {{-- Header section --}}
    <div class="flex items-center gap-3 px-1 py-2">
        <div class="w-[34px] h-[34px] bg-white border border-[#e0e0e0] rounded-lg flex items-center justify-center shrink-0 shadow-sm">
            <i data-lucide="bar-chart-2" class="w-[17px] h-[17px] text-[#1b5e20]"></i>
        </div>
        <h2 class="font-bold text-[#1d293d] text-[14px] tracking-wider uppercase">Rincian Poin per Kategori</h2>
    </div>

    {{-- Accordion cards --}}
    <template x-for="(cat, idx) in categories" :key="idx">
        <div class="bg-white border border-[#e0e0e0] rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            {{-- Accordion header --}}
            <button @click="toggleCategory(idx)"
                    class="w-full flex items-center justify-between px-6 py-5 hover:bg-[#fcfdfd] transition-colors text-left group">
                <div class="flex items-center gap-4">
                    {{-- Status Indicator Circle --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300 border border-[#e2e8f0]"
                         :class="openCategories[idx] ? 'bg-[#e8f5e9] text-[#1b5e20] border-[#c8e6c9]' : 'bg-[#f1f5f9] text-[#475569]'">
                        <i data-lucide="folder" class="w-5 h-5" x-show="!openCategories[idx]"></i>
                        <i data-lucide="folder-open" class="w-5 h-5" x-show="openCategories[idx]" style="display:none;"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1d293d] text-[15px] group-hover:text-[#1b5e20] transition-colors" x-text="cat.name"></h3>
                        <p class="text-[11px] text-[#64748b] font-medium uppercase tracking-wider mt-0.5">Klik untuk melihat detail pertanyaan</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block border-r border-[#e2e8f0] pr-6">
                        <p class="text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest mb-1">Capaian Skor</p>
                        <div class="flex items-center gap-1.5 justify-end">
                            <span class="text-[18px] font-extrabold text-[#1d293d]" x-text="cat.score"></span>
                            <span class="text-[13px] font-bold text-[#94a3b8]">/ <span x-text="cat.max"></span></span>
                        </div>
                    </div>
                    {{-- Arrow Indicator --}}
                    <div class="w-9 h-9 rounded-full flex items-center justify-center bg-[#f1f5f9] border border-[#e2e8f0] group-hover:bg-[#e8f5e9] group-hover:border-[#c8e6c9] transition-all duration-300">
                        <span class="transition-transform duration-300 inline-block" :class="openCategories[idx] ? 'rotate-180' : 'rotate-0'">
                            <i data-lucide="chevron-down" class="w-[20px] h-[20px] text-[#475569] group-hover:text-[#1b5e20]"></i>
                        </span>
                    </div>
                </div>
            </button>

            {{-- Accordion body: daftar item --}}
            <div x-show="openCategories[idx]" 
                 x-collapse
                 style="display: none;"
                 class="bg-[#fcfdfd] border-t border-[#f1f5f9]">
                <div class="divide-y divide-[#f1f5f9]">
                    <template x-for="(item, iIdx) in cat.items" :key="iIdx">
                        <div class="px-6 py-5 hover:bg-white transition-colors">
                            {{-- Header item --}}
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-7 h-7 rounded-lg bg-[#f1f5f9] flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-[12px] font-bold text-[#475569]" x-text="item.no"></span>
                                    </div>
                                    <p class="text-[14px] font-semibold text-[#1e293b] leading-relaxed max-w-[500px]" x-text="item.title"></p>
                                </div>
                                {{-- Badge skor item --}}
                                <div class="shrink-0 flex flex-col items-end gap-1.5">
                                    <template x-if="item.is_validated">
                                        <div class="flex flex-col items-end">
                                            <span class="text-[9px] font-bold text-[#059669] uppercase tracking-[0.1em] mb-1">Skor Akhir</span>
                                            <div class="flex items-center gap-1.5 bg-[#ecfdf5] border border-[#10b981]/20 px-3 py-1.5 rounded-lg">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-[#059669]"></i>
                                                <span class="text-[13px] font-bold text-[#047857]" x-text="item.score + ' / ' + item.max"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!item.is_validated">
                                        <div class="flex flex-col items-end">
                                            <span class="text-[9px] font-bold text-[#d97706] uppercase tracking-[0.1em] mb-1">Estimasi Skor</span>
                                            <div class="flex items-center gap-1.5 bg-[#fffbeb] border border-[#f59e0b]/20 px-3 py-1.5 rounded-lg">
                                                <span class="text-[13px] font-bold text-[#b45309]" x-text="item.score + ' / ' + item.max"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Detail item --}}
                            <div class="ml-[44px] space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-wider">
                                            <i class="w-3.5 h-3.5"></i> Jawaban Anda
                                        </p>
                                        <div class="bg-white border border-[#f1f5f9] rounded-lg p-3 text-[13px] text-[#334155] font-medium leading-relaxed shadow-sm" x-text="item.jawaban"></div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <p class="text-[11px] font-bold text-[#64748b] uppercase tracking-wider flex items-center gap-1.5">
                                            <i data-lucide="link" class="w-3.5 h-3.5"></i> Tautan Bukti
                                        </p>
                                        <div class="bg-white border border-[#f1f5f9] rounded-lg p-3 shadow-sm">
                                            <template x-if="item.tautan">
                                                <a :href="item.tautan" target="_blank"
                                                   class="text-[13px] font-bold text-[#1b5e20] hover:text-[#15461c] hover:underline flex items-center gap-2 truncate">
                                                    <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0"></i>
                                                    <span class="truncate">Buka Dokumen Bukti</span>
                                                </a>
                                            </template>
                                            <template x-if="!item.tautan">
                                                <span class="text-[13px] text-[#94a3b8] italic">Tidak ada lampiran</span>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Catatan Reviewer --}}
                                <template x-if="item.catatan">
                                    <div class="bg-gradient-to-r from-[#fffbeb] to-[#fffde0] border-l-4 border-[#f59e0b] rounded-r-lg p-4 shadow-sm">
                                        <p class="text-[11px] font-bold text-[#92400e] uppercase tracking-widest mb-1.5 flex items-center gap-2">
                                            <i data-lucide="info" class="w-4 h-4"></i> Catatan Reviewer
                                        </p>
                                        <p class="text-[13px] font-semibold text-[#78350f] leading-relaxed" x-text="item.catatan"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>
