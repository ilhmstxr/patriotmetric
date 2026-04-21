<x-layouts.reviewer>
    <x-slot:title>DASHBOARD UTAMA</x-slot:title>

    <div class="flex-1 overflow-y-auto bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif] p-[40px]">
        <div class="max-w-[1200px] mx-auto flex flex-col gap-[32px]">
            
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-[24px]">
                <div class="bg-white rounded-[16px] p-[24px] border border-[#e2e8f0] shadow-sm flex items-center gap-[16px]">
                    <div class="w-[56px] h-[56px] rounded-[12px] bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i data-lucide="clock" class="w-[28px] h-[28px]"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-medium text-[#64748b]">Belum Dinilai</p>
                        <h3 class="text-[28px] font-bold text-[#1d293d] mt-[4px]">12</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-[16px] p-[24px] border border-[#e2e8f0] shadow-sm flex items-center gap-[16px]">
                    <div class="w-[56px] h-[56px] rounded-[12px] bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="users" class="w-[28px] h-[28px]"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-medium text-[#64748b]">Total Di-Plotting</p>
                        <h3 class="text-[28px] font-bold text-[#1d293d] mt-[4px]">45</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-[16px] p-[24px] border border-[#e2e8f0] shadow-sm flex items-center gap-[16px]">
                    <div class="w-[56px] h-[56px] rounded-[12px] bg-green-50 text-green-600 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-[28px] h-[28px]"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-medium text-[#64748b]">Selesai Dinilai</p>
                        <h3 class="text-[28px] font-bold text-[#1d293d] mt-[4px]">33</h3>
                    </div>
                </div>
            </div>

            {{-- Table 1: Submitter Belum di Review --}}
            <div class="bg-white rounded-[16px] border border-[#e2e8f0] shadow-sm overflow-hidden">
                <div class="px-[24px] py-[20px] border-b border-[#e2e8f0] flex justify-between items-center bg-white">
                    <h2 class="font-bold text-[#1d293d] text-[18px] flex items-center gap-[8px]">
                        <i data-lucide="alert-circle" class="text-amber-500 w-[20px] h-[20px]"></i>
                        Submitter Belum Direview (Perlu Tindakan)
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider">No</th>
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider">Nama Institusi</th>
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider">Tgl Submit</th>
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            <tr class="hover:bg-[#f1f5f9] transition-colors">
                                <td class="py-[16px] px-[24px] text-[15px] text-[#1d293d] font-medium">1</td>
                                <td class="py-[16px] px-[24px]">
                                    <div class="flex items-center gap-[12px]">
                                        <div class="w-[36px] h-[36px] rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[14px]">UI</div>
                                        <span class="text-[15px] font-semibold text-[#1d293d]">Universitas Indonesia</span>
                                    </div>
                                </td>
                                <td class="py-[16px] px-[24px] text-[14px] text-[#64748b]">12 Okt 2025</td>
                                <td class="py-[16px] px-[24px] text-right">
                                    <a href="{{ route('reviewer.submitter_detail', ['id' => 1]) }}" class="inline-flex items-center gap-[6px] bg-[#1b5e20] text-white px-[16px] py-[8px] rounded-[8px] text-[13px] font-semibold hover:bg-[#15461c] transition-colors">
                                        <i data-lucide="edit" class="w-[14px] h-[14px]"></i> Nilai Sekarang
                                    </a>
                                </td>
                            </tr>
                            <tr class="hover:bg-[#f1f5f9] transition-colors">
                                <td class="py-[16px] px-[24px] text-[15px] text-[#1d293d] font-medium">2</td>
                                <td class="py-[16px] px-[24px]">
                                    <div class="flex items-center gap-[12px]">
                                        <div class="w-[36px] h-[36px] rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-[14px]">UG</div>
                                        <span class="text-[15px] font-semibold text-[#1d293d]">Universitas Gadjah Mada</span>
                                    </div>
                                </td>
                                <td class="py-[16px] px-[24px] text-[14px] text-[#64748b]">14 Okt 2025</td>
                                <td class="py-[16px] px-[24px] text-right">
                                    <a href="{{ route('reviewer.submitter_detail', ['id' => 2]) }}" class="inline-flex items-center gap-[6px] bg-[#1b5e20] text-white px-[16px] py-[8px] rounded-[8px] text-[13px] font-semibold hover:bg-[#15461c] transition-colors">
                                        <i data-lucide="edit" class="w-[14px] h-[14px]"></i> Nilai Sekarang
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Table 2: Submitter Di-Plotting --}}
            <div class="bg-white rounded-[16px] border border-[#e2e8f0] shadow-sm overflow-hidden" x-data="{ filter: 'all' }">
                <div class="px-[24px] py-[20px] border-b border-[#e2e8f0] flex flex-col md:flex-row justify-between items-start md:items-center gap-[16px] bg-white">
                    <h2 class="font-bold text-[#1d293d] text-[18px] flex items-center gap-[8px]">
                        <i data-lucide="list" class="text-blue-500 w-[20px] h-[20px]"></i>
                        Daftar Plotting Submitter Anda
                    </h2>
                    
                    <div class="flex items-center gap-[12px]">
                        <span class="text-[13px] font-medium text-[#64748b]">Filter Status:</span>
                        <select x-model="filter" class="border border-[#cbd5e1] rounded-[8px] px-[12px] py-[8px] text-[14px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20]">
                            <option value="all">Semua Status</option>
                            <option value="pending">Belum Dinilai</option>
                            <option value="done">Selesai Dinilai</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider">Nama Institusi</th>
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider">Tgl Submit</th>
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider">Status</th>
                                <th class="py-[16px] px-[24px] font-semibold text-[#64748b] text-[13px] uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            <tr class="hover:bg-[#f1f5f9] transition-colors" x-show="filter === 'all' || filter === 'pending'">
                                <td class="py-[16px] px-[24px]">
                                    <div class="flex items-center gap-[12px]">
                                        <div class="w-[36px] h-[36px] rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[14px]">UI</div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-[#1d293d]">Universitas Indonesia</p>
                                            <p class="text-[12px] text-[#64748b]">PTN</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-[16px] px-[24px] text-[14px] text-[#64748b]">12 Okt 2025</td>
                                <td class="py-[16px] px-[24px]">
                                    <span class="inline-flex items-center gap-[4px] px-[10px] py-[4px] rounded-full bg-amber-100 text-amber-700 text-[12px] font-bold">
                                        <i data-lucide="clock" class="w-[12px] h-[12px]"></i> Belum Dinilai
                                    </span>
                                </td>
                                <td class="py-[16px] px-[24px] text-right">
                                    <a href="{{ route('reviewer.submitter_detail', ['id' => 1]) }}" class="inline-flex items-center gap-[6px] bg-white border border-[#cbd5e1] text-[#45556c] px-[16px] py-[8px] rounded-[8px] text-[13px] font-semibold hover:bg-[#f1f5f9] transition-colors">
                                        <i data-lucide="eye" class="w-[14px] h-[14px]"></i> Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            <tr class="hover:bg-[#f1f5f9] transition-colors" x-show="filter === 'all' || filter === 'done'">
                                <td class="py-[16px] px-[24px]">
                                    <div class="flex items-center gap-[12px]">
                                        <div class="w-[36px] h-[36px] rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-[14px]">UN</div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-[#1d293d]">Universitas Airlangga</p>
                                            <p class="text-[12px] text-[#64748b]">PTN</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-[16px] px-[24px] text-[14px] text-[#64748b]">09 Okt 2025</td>
                                <td class="py-[16px] px-[24px]">
                                    <span class="inline-flex items-center gap-[4px] px-[10px] py-[4px] rounded-full bg-green-100 text-green-700 text-[12px] font-bold">
                                        <i data-lucide="check-circle-2" class="w-[12px] h-[12px]"></i> Selesai
                                    </span>
                                </td>
                                <td class="py-[16px] px-[24px] text-right">
                                    <a href="{{ route('reviewer.submitter_detail', ['id' => 3, 'status' => 'done']) }}" class="inline-flex items-center gap-[6px] bg-white border border-[#cbd5e1] text-[#45556c] px-[16px] py-[8px] rounded-[8px] text-[13px] font-semibold hover:bg-[#f1f5f9] transition-colors">
                                        <i data-lucide="eye" class="w-[14px] h-[14px]"></i> Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-layouts.reviewer>
