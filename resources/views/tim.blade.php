<x-layouts.app>
    <div class="bg-[#f8fafc]">
        {{-- Header --}}
        <section class="py-20 bg-white border-b border-[#e2e8f0]">
            <div class="max-w-[768px] mx-auto px-8 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[40px] md:text-[48px] leading-[1.2] text-[#1e293b]">Tim Kami</h1>
            </div>
        </section>

        {{-- Team Grid --}}
        <section class="py-20">
            <div class="max-w-[1280px] mx-auto px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                    $teamMembers = [
                        [
                            'name' => "Lorem Ipsum",
                            'role' => "Lorem Ipsum",
                            'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')
                        ],
                        [
                            'name' => "Lorem Ipsum",
                            'role' => "Lorem Ipsum",
                            'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')
                        ],
                        [
                            'name' => "Lorem Ipsum",
                            'role' => "Lorem Ipsum",
                            'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')
                        ],
                        [
                            'name' => "Lorem Ipsum",
                            'role' => "Lorem Ipsum",
                            'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')
                        ]
                    ];
                    @endphp
                    @foreach($teamMembers as $member)
                    <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden flex flex-col">
                        <div class="p-4 pb-0">
                            <div class="bg-[#f1f5f9] rounded-xl overflow-hidden h-[300px]">
                                <img src="{{ $member['img'] }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover" />
                            </div>
                        </div>
                        <div class="p-5 pt-4 text-center flex-1 flex flex-col justify-end">
                            <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] leading-[26px] text-[#1e293b]">{{ $member['name'] }}</h3>
                            <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#16a34a]">{{ $member['role'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
