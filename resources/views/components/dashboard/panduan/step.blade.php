{{-- ===================================================== --}}
{{-- PANDUAN: Step Card (reusable)                        --}}
{{-- Props:                                               --}}
{{--   $number  = nomor langkah (1, 2, 3...)              --}}
{{-- Slot:       isi konten langkah                       --}}
{{-- ===================================================== --}}
@props(['number' => 1])

<div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[24px] md:p-[32px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)] flex flex-col md:flex-row gap-[16px] md:gap-[24px]">
    {{-- Nomor step --}}
    <div class="w-[56px] h-[56px] rounded-[10px] bg-[rgba(27,94,32,0.1)] flex items-center justify-center shrink-0 border border-[rgba(27,94,32,0.2)]">
        <span class="text-[24px] font-bold text-[#1b5e20]">{{ $number }}</span>
    </div>
    {{-- Konten step (dari slot) --}}
    <div class="space-y-[12px] pt-[4px] w-full">
        {{ $slot }}
    </div>
</div>
