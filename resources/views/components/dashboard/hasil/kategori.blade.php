{{-- ===================================================== --}}
{{-- HASIL: Accordion Rincian Poin per Kategori           --}}
{{-- Komponen ini menggunakan Alpine.js `categories` dan  --}}
{{-- `openCategories` dari x-data di halaman induknya.   --}}
{{-- ✏️ Edit data kategori di hasil.blade.php             --}}
{{-- ===================================================== --}}
<div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden">

    {{-- Header section --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-[#e0e0e0]">
        <div class="w-[34px] h-[34px] bg-[#f5f5f5] border border-[#e0e0e0] rounded-lg flex items-center justify-center shrink-0">
            <i data-lucide="bar-chart-2" class="w-[17px] h-[17px] text-[#314158]"></i>
        </div>
        <h2 class="font-bold text-[#1d293d] text-[14px] tracking-wider uppercase">Rincian Poin per Kategori</h2>
    </div>

    {{-- Accordion list --}}
    <div class="divide-y divide-[#e0e0e0]">
        <template x-for="(cat, idx) in categories" :key="idx">
            <div>
                {{-- Accordion header --}}
                <button @click="toggleCategory(idx)"
                        class="w-full flex items-center justify-between px-5 py-4 hover:bg-[#fafafa] transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-[34px] h-[34px] bg-[#f5f5f5] border border-[#e0e0e0] rounded flex items-center justify-center shrink-0">
                            <i data-lucide="folder" class="w-[17px] h-[17px] text-[#314158]"></i>
                        </div>
                        <h3 class="font-bold text-[#1d293d] text-[14px]" x-text="cat.name"></h3>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <div class="flex items-center gap-1.5 justify-end">
                                <span class="text-[15px] font-bold text-[#1d293d]" x-text="cat.score"></span>
                                <span class="text-[12px] font-medium text-[#90a1b9]">/ <span x-text="cat.max"></span></span>
                            </div>
                        </div>
                        <i data-lucide="chevron-down" class="w-[16px] h-[16px] text-[#62748e] transition-transform duration-200" :class="openCategories[idx] ? 'rotate-180' : ''"></i>
                    </div>
                </button>

                {{-- Accordion body: daftar item --}}
                <div x-show="openCategories[idx]" x-transition class="bg-white">
                    <template x-for="(item, iIdx) in cat.items" :key="iIdx">
                        <div class="border-t border-[#f0f0f0] px-5 py-4">
                            {{-- Header item --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-start gap-3">
                                    <span class="text-[12px] font-bold text-[#45556c] shrink-0 mt-0.5" x-text="item.no"></span>
                                    <p class="text-[13px] font-semibold text-[#1d293d] leading-snug" x-text="item.title"></p>
                                </div>
                                {{-- Badge skor item --}}
                                <span class="text-[12px] font-bold text-[#45556c] shrink-0 bg-[#f5f5f5] border border-[#e0e0e0] px-2.5 py-1 rounded"
                                      x-text="item.score + ' / ' + item.max"></span>
                            </div>

                            {{-- Detail item --}}
                            <div class="ml-5 space-y-2">
                                <p class="text-[11px] font-semibold text-[#62748e] uppercase tracking-wide">Jawaban Anda:</p>
                                <p class="text-[12px] font-medium text-[#1d293d]" x-text="item.jawaban"></p>

                                <p class="text-[11px] font-semibold text-[#62748e] uppercase tracking-wide mt-2">Tautan Bukti Dokumen:</p>
                                <a :href="item.tautan" target="_blank"
                                   class="text-[12px] font-medium text-[#1b5e20] hover:underline break-all"
                                   x-text="item.tautan"></a>

                                {{-- Catatan Reviewer --}}
                                <div class="bg-[#fffbeb] border border-[#fde68a] rounded px-3 py-2.5 mt-2">
                                    <p class="text-[11px] font-bold text-[#92400e] uppercase tracking-wide mb-1">Catatan Reviewer:</p>
                                    <p class="text-[12px] font-medium text-[#92400e]" x-text="item.catatan"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
