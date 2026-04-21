<x-layouts.reviewer>
    <x-slot:title>DASHBOARD UTAMA</x-slot:title>

    <div class="bg-[#f5f5f5] min-h-[calc(100vh-120px)] py-[20px] px-[16px] md:px-[32px]">
        <div class="max-w-[1000px] mx-auto space-y-[20px]">
            
            {{-- Header Greeting --}}
            <div class="flex items-center justify-between bg-white p-[20px] md:p-[24px] rounded-[10px] border border-[#e0e0e0]">
                <div>
                    <h1 class="text-[18px] md:text-[22px] font-bold text-[#1d293d]">Selamat Datang, Tim Penilai!</h1>
                    <p class="text-[13px] md:text-[14px] text-[#64748b] mt-1">Anda memiliki tugas review yang belum diselesaikan. Mari selesaikan tenggat waktu penilaian.</p>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-[20px]">
                <div class="bg-white rounded-[10px] p-[20px] border border-[#e0e0e0] flex items-center gap-[16px]">
                    <div class="w-[48px] h-[48px] rounded-[10px] bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i data-lucide="clock" class="w-[24px] h-[24px]"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-[#64748b]">Belum Dinilai</p>
                        <h3 class="text-[24px] font-bold text-[#1d293d] mt-0.5 leading-none">2</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-[10px] p-[20px] border border-[#e0e0e0] flex items-center gap-[16px]">
                    <div class="w-[48px] h-[48px] rounded-[10px] bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-[24px] h-[24px]"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-[#64748b]">Total Di-Plotting</p>
                        <h3 class="text-[24px] font-bold text-[#1d293d] mt-0.5 leading-none">3</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-[10px] p-[20px] border border-[#e0e0e0] flex items-center gap-[16px]">
                    <div class="w-[48px] h-[48px] rounded-[10px] bg-green-50 border border-green-100 text-green-600 flex items-center justify-center shrink-0">
                        <i data-lucide="check-circle" class="w-[24px] h-[24px]"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-[#64748b]">Selesai Dinilai</p>
                        <h3 class="text-[24px] font-bold text-[#1d293d] mt-0.5 leading-none">1</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[10px] border border-[#e0e0e0] overflow-hidden">
                <div class="px-[20px] md:px-[24px] py-[16px] md:py-[20px] border-b border-[#e2e8f0] flex justify-between items-center bg-[#f8fafc]">
                    <h2 class="font-bold text-[#1b5e20] text-[15px] flex items-center gap-[8px]">
                        <i data-lucide="alert-circle" class="text-amber-500 w-[18px] h-[18px]"></i>
                        Peserta Belum Direview (Perlu Tindakan)
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

            {{-- Table Keseluruhan Plotting Paling Bawah --}}
            <div class="bg-white rounded-[10px] border border-[#e0e0e0] overflow-hidden" x-data="{ filter: 'all' }">
                <div class="px-[20px] md:px-[24px] py-[16px] md:py-[20px] border-b border-[#e2e8f0] flex flex-col md:flex-row justify-between items-start md:items-center gap-[16px] bg-[#f8fafc]">
                    <h2 class="font-bold text-[#1b5e20] text-[15px] flex items-center gap-[8px]">
                        <i data-lucide="list" class="text-blue-500 w-[18px] h-[18px]"></i>
                        Daftar Plotting Peserta Anda
                    </h2>
                    
                    <div class="flex items-center gap-[12px]">
                        <span class="text-[13px] font-medium text-[#64748b]">Filter Status:</span>
                        <select x-model="filter" class="border border-[#cbd5e1] rounded-[8px] px-[12px] py-[8px] text-[13px] font-semibold text-[#45556c] focus:outline-none focus:border-[#1b5e20]">
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
                            <tr class="hover:bg-[#f1f5f9] transition-colors" x-show="filter === 'all' || filter === 'pending'">
                                <td class="py-[16px] px-[24px]">
                                    <div class="flex items-center gap-[12px]">
                                        <div class="w-[36px] h-[36px] rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-[14px]">UG</div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-[#1d293d]">Universitas Gadjah Mada</p>
                                            <p class="text-[12px] text-[#64748b]">PTN</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-[16px] px-[24px] text-[14px] text-[#64748b]">14 Okt 2025</td>
                                <td class="py-[16px] px-[24px]">
                                    <span class="inline-flex items-center gap-[4px] px-[10px] py-[4px] rounded-full bg-amber-100 text-amber-700 text-[12px] font-bold">
                                        <i data-lucide="clock" class="w-[12px] h-[12px]"></i> Belum Dinilai
                                    </span>
                                </td>
                                <td class="py-[16px] px-[24px] text-right">
                                    <a href="{{ route('reviewer.submitter_detail', ['id' => 2]) }}" class="inline-flex items-center gap-[6px] bg-white border border-[#cbd5e1] text-[#45556c] px-[16px] py-[8px] rounded-[8px] text-[13px] font-semibold hover:bg-[#f1f5f9] transition-colors">
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
