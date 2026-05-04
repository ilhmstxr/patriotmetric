{{-- ===================================================== --}}
{{-- DASHBOARD: Komponen Loading Konsisten                  --}}
{{-- Props:                                                 --}}
{{--   - title   : teks utama (default: "Memuat Data...")   --}}
{{--   - caption : teks kecil opsional                      --}}
{{-- ===================================================== --}}
@props([
    'title' => 'Memuat Data...',
    'caption' => 'Mohon tunggu sebentar, sistem sedang menyiapkan data Anda.',
])

<div class="flex flex-col items-center justify-center py-32 space-y-4">
    <div class="w-12 h-12 border-4 border-[#1b5e20] border-t-transparent rounded-full animate-spin"></div>
    <p class="text-[15px] font-bold text-[#1d293d] tracking-wide uppercase">{{ $title }}</p>
    @if($caption)
        <p class="text-[13px] text-[#62748e] text-center max-w-md">{{ $caption }}</p>
    @endif
</div>
