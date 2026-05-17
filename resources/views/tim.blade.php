<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">Tim Kami</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    Para profesional yang berdedikasi dalam mengembangkan dan mengelola sistem pemeringkatan Patriot Metric.
                </p>
            </div>
        </section>

        {{-- Team Grid --}}
        <section class="py-14 md:py-20 bg-[#f8fafc]">
            <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $teamMembers = [
                            ['name' => "Lorem Ipsum", 'role' => "Lorem Ipsum", 'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')],
                            ['name' => "Lorem Ipsum", 'role' => "Lorem Ipsum", 'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')],
                            ['name' => "Lorem Ipsum", 'role' => "Lorem Ipsum", 'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')],
                            ['name' => "Lorem Ipsum", 'role' => "Lorem Ipsum", 'img' => asset('assets/images/blank-profile-picture-973460_1280.webp')],
                        ];
                    @endphp
                    @foreach($teamMembers as $member)
                        <div class="bg-white rounded-2xl border border-[#f1f5f9] overflow-hidden hover:shadow-lg hover:border-[#1B5E20]/10 transition-all duration-300 group">
                            <div class="p-4 pb-0">
                                <div class="bg-[#f8fafc] rounded-xl overflow-hidden h-[280px]">
                                    <img src="{{ $member['img'] }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500" />
                                </div>
                            </div>
                            <div class="p-5 pt-4 text-center">
                                <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[17px] text-[#1d293d]">{{ $member['name'] }}</h3>
                                <p class="mt-1.5 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[13px] text-[#1B5E20]">{{ $member['role'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
