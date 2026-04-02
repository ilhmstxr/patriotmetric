<x-layouts.dashboard>
    <x-slot:title>HASIL PENILAIAN</x-slot:title>

    <div class="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
      {{-- Content Area --}}
      <div class="flex-1 overflow-y-auto p-[32px]">
        <div class="flex flex-col gap-[32px] max-w-[920px]">
          {{-- Banner --}}
          <div class="bg-[#1b5e20] rounded-[10px] p-[32px] shadow-[0px_4px_6px_0px_rgba(0,0,0,0.1)] text-white flex justify-between items-center relative overflow-hidden">
            {{-- Decorative Element --}}
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-white opacity-5 rounded-full blur-[60px] translate-x-1/4 -translate-y-1/4"></div>
            <div class="absolute bottom-0 right-[200px] w-[200px] h-[200px] bg-[#c89600] opacity-10 rounded-full blur-[40px] translate-y-1/2"></div>
            
            <div class="relative z-10 space-y-[24px] flex-1">
              <h2 class="text-[12px] font-bold tracking-[0.2em] text-[#c89600] uppercase">
                PREDIKAT INSTITUSI
              </h2>
              
              <div class="flex gap-[8px] text-[#c89600]">
                {{-- 4 Stars filled, 1 empty --}}
                @for ($i = 0; $i < 4; $i++)
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                  </svg>
                @endfor
                <svg width="32" height="32" viewBox="0 0 24 24" fill="rgba(255,255,255,0.2)">
                  <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                </svg>
              </div>

              <div>
                <h3 class="text-[28px] font-bold mb-[8px] text-white tracking-tight">Bintang 4 - Adhi Karya</h3>
                <p class="text-[15px] font-medium text-[rgba(255,255,255,0.8)]">
                  Universitas Pembangunan Nasional Veteran Jawa Timur
                </p>
              </div>
            </div>

            {{-- Total Points Box --}}
            <div class="relative z-10 bg-[rgba(255,255,255,0.1)] backdrop-blur-md border border-[rgba(255,255,255,0.2)] rounded-[12px] p-[24px] flex flex-col items-center justify-center min-w-[200px]">
              <i data-lucide="award" class="w-[40px] h-[40px] text-[#c89600] mb-[16px]" stroke-width="1.5"></i>
              <div class="text-[36px] font-bold text-white leading-none mb-[4px]">
                85 <span class="text-[20px] text-[rgba(255,255,255,0.6)]">/ 100</span>
              </div>
              <div class="text-[11px] font-bold tracking-[0.15em] text-[#c89600] uppercase mt-[8px]">
                TOTAL POIN VALID
              </div>
            </div>
          </div>

          {{-- Status --}}
          <div class="bg-white rounded-[10px] border border-[#e2e8f0] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] p-[24px] flex items-center justify-between">
            <div class="flex items-center gap-[16px]">
              <div class="w-[48px] h-[48px] rounded-[8px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center">
                <i data-lucide="check-circle" class="w-[24px] h-[24px] text-[#1b5e20]"></i>
              </div>
              <div>
                <h3 class="font-bold text-[#1d293d] text-[16px] uppercase tracking-[0.4px]">Status Penilaian</h3>
                <p class="text-[#62748e] text-[14px] font-medium mt-[4px]">Kondisi data rubrik Anda saat ini</p>
              </div>
            </div>
            <div class="bg-[rgba(27,94,32,0.1)] text-[#1b5e20] font-bold px-[24px] py-[10px] rounded-full border border-[rgba(27,94,32,0.2)] text-[14px]">
              Telah Divalidasi Reviwer
            </div>
          </div>

          {{-- Categories Breakdown --}}
          <div>
            <h3 class="text-[#1d293d] font-bold text-[16px] uppercase tracking-[0.4px] mb-[24px]">
              Rincian Poin per Kategori
            </h3>
            <div class="grid grid-cols-3 gap-[24px]">
              {{-- Category 1 --}}
              <div class="bg-white rounded-[10px] border border-[#e2e8f0] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] p-[24px] space-y-[24px]">
                <div class="flex items-center gap-[12px]">
                  <div class="w-[40px] h-[40px] rounded-[8px] bg-[#f8fafc] border border-[#e2e8f0] flex items-center justify-center">
                    <span class="font-bold text-[#1d293d]">01</span>
                  </div>
                  <h4 class="font-bold text-[#1d293d] text-[14px]">Kebijakan</h4>
                </div>
                <div>
                  <div class="flex justify-between items-end mb-[12px]">
                    <span class="text-[28px] font-bold text-[#1b5e20] leading-none">20</span>
                    <span class="text-[13px] font-semibold text-[#62748e] mb-[4px]">/ 25 pts</span>
                  </div>
                  <div class="h-[8px] w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-full overflow-hidden">
                    <div class="h-full bg-[#1b5e20] rounded-full" style="width: 80%"></div>
                  </div>
                </div>
              </div>

              {{-- Category 2 --}}
              <div class="bg-white rounded-[10px] border border-[#e2e8f0] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] p-[24px] space-y-[24px]">
                <div class="flex items-center gap-[12px]">
                  <div class="w-[40px] h-[40px] rounded-[8px] bg-[#f8fafc] border border-[#e2e8f0] flex items-center justify-center">
                    <span class="font-bold text-[#1d293d]">02</span>
                  </div>
                  <h4 class="font-bold text-[#1d293d] text-[14px]">Kelembagaan</h4>
                </div>
                <div>
                  <div class="flex justify-between items-end mb-[12px]">
                    <span class="text-[28px] font-bold text-[#1b5e20] leading-none">45</span>
                    <span class="text-[13px] font-semibold text-[#62748e] mb-[4px]">/ 50 pts</span>
                  </div>
                  <div class="h-[8px] w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-full overflow-hidden">
                    <div class="h-full bg-[#1b5e20] rounded-full" style="width: 90%"></div>
                  </div>
                </div>
              </div>

              {{-- Category 3 --}}
              <div class="bg-white rounded-[10px] border border-[#e2e8f0] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] p-[24px] space-y-[24px]">
                <div class="flex items-center gap-[12px]">
                  <div class="w-[40px] h-[40px] rounded-[8px] bg-[#f8fafc] border border-[#e2e8f0] flex items-center justify-center">
                    <span class="font-bold text-[#1d293d]">03</span>
                  </div>
                  <h4 class="font-bold text-[#1d293d] text-[14px]">Patriotisme Mhs</h4>
                </div>
                <div>
                  <div class="flex justify-between items-end mb-[12px]">
                    <span class="text-[28px] font-bold text-[#1b5e20] leading-none">20</span>
                    <span class="text-[13px] font-semibold text-[#62748e] mb-[4px]">/ 25 pts</span>
                  </div>
                  <div class="h-[8px] w-full bg-[#f8fafc] border border-[#e2e8f0] rounded-full overflow-hidden">
                    <div class="h-full bg-[#1b5e20] rounded-full" style="width: 80%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-layouts.dashboard>
