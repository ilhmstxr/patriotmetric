<x-layouts.reviewer>
    <x-slot:title>DASHBOARD UTAMA</x-slot:title>

    <!-- Inisialisasi Alpine.js dan memanggil fungsi fetch saat halaman dimuat -->
    <div x-data="reviewerDashboard()" x-init="initData()" class="bg-[#f5f5f5] min-h-[calc(100vh-120px)] py-[20px] px-[16px] md:px-[32px]">
        <div class="max-w-[1000px] mx-auto space-y-[20px]">
            
            {{-- Header Greeting --}}
            <div class="flex items-center justify-between bg-white p-[20px] md:p-[24px] rounded-[10px] border border-[#e0e0e0]">
                <div>
                    <h1 class="text-[18px] md:text-[22px] font-bold text-[#1d293d]">Selamat Datang, Tim Penilai!</h1>
                    <p class="text-[13px] md:text-[14px] text-[#64748b] mt-1">Anda memiliki <span x-text="summary.menunggu_review" class="font-bold text-amber-600">0</span> tugas review yang belum diselesaikan. Mari selesaikan tenggat waktu penilaian.</p>
                </div>
            </div>

            {{-- Indikator Loading Global --}}
            <div x-show="isLoading" class="text-center py-4 text-[#64748b] animate-pulse">
                <i data-lucide="loader-2" class="w-6 h-6 mx-auto animate-spin mb-2"></i>
                Mengambil data plottingan...
            </div>

            {{-- Summary Cards --}}
            <div x-show="!isLoading" style="display: none;" class="grid grid-cols-1 md:grid-cols-3 gap-[20px]">
                <div class="bg-white rounded-[10px] p-[20px] border border-[#e0e0e0] flex items-center gap-[16px]">
                    <div class="w-[48px] h-[48px] rounded-[10px] bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i data-lucide="clock" class="w-[24px] h-[24px]"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-[#64748b]">Belum Dinilai</p>
                        <h3 class="text-[24px] font-bold text-[#1d293d] mt-0.5 leading-none" x-text="summary.menunggu_review">0</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-[10px] p-[20px] border border-[#e0e0e0] flex items-center gap-[16px]">
                    <div class="w-[48px] h-[48px] rounded-[10px] bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-[24px] h-[24px]"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-[#64748b]">Total Di-Plotting</p>
                        <h3 class="text-[24px] font-bold text-[#1d293d] mt-0.5 leading-none" x-text="summary.total_tugas">0</h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-[10px] p-[20px] border border-[#e0e0e0] flex items-center gap-[16px]">
                    <div class="w-[48px] h-[48px] rounded-[10px] bg-green-50 border border-green-100 text-green-600 flex items-center justify-center shrink-0">
                        <i data-lucide="check-circle" class="w-[24px] h-[24px]"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-[#64748b]">Selesai Dinilai</p>
                        <h3 class="text-[24px] font-bold text-[#1d293d] mt-0.5 leading-none" x-text="summary.selesai_review">0</h3>
                    </div>
                </div>
            </div>

            {{-- TABEL 1: Urgent (Hanya Status SUBMITTED / Belum dinilai) --}}
            <div x-show="!isLoading" style="display: none;" class="bg-white rounded-[10px] border border-[#e0e0e0] overflow-hidden">
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
                            {{-- Looping Data Pending Menggunakan Alpine --}}
                            <template x-for="(task, index) in pendingTasks" :key="task.id">
                                <tr class="hover:bg-[#f1f5f9] transition-colors">
                                    <td class="py-[16px] px-[24px] text-[15px] text-[#1d293d] font-medium" x-text="index + 1"></td>
                                    <td class="py-[16px] px-[24px]">
                                        <div class="flex items-center gap-[12px]">
                                            <!-- Avatar Inisial Dinamis -->
                                            <div class="w-[36px] h-[36px] rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-[14px]" x-text="getInitials(task.institusi?.nama_institusi)"></div>
                                            <span class="text-[15px] font-semibold text-[#1d293d]" x-text="task.institusi?.nama_institusi || 'Institusi Tidak Diketahui'"></span>
                                        </div>
                                    </td>
                                    <td class="py-[16px] px-[24px] text-[14px] text-[#64748b]" x-text="formatDate(task.updated_at)"></td>
                                    <td class="py-[16px] px-[24px] text-right">
                                        <!-- Note: Sesuaikan Base URL detail submission Anda -->
                                        <a :href="'/reviewer/detail/' + task.id" class="inline-flex items-center gap-[6px] bg-[#1b5e20] text-white px-[16px] py-[8px] rounded-[8px] text-[13px] font-semibold hover:bg-[#15461c] transition-colors">
                                            <i data-lucide="edit" class="w-[14px] h-[14px]"></i> Nilai Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </template>

                            {{-- State jika tidak ada yang perlu dinilai --}}
                            <tr x-show="pendingTasks.length === 0">
                                <td colspan="4" class="py-[30px] text-center text-[#64748b]">
                                    <i data-lucide="check-circle-2" class="w-8 h-8 mx-auto text-green-500 mb-2"></i>
                                    Hebat! Tidak ada tugas review yang tertunda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TABEL 2: Keseluruhan Plotting (Semua Status) --}}
            <div x-show="!isLoading" style="display: none;" class="bg-white rounded-[10px] border border-[#e0e0e0] overflow-hidden">
                <div class="px-[20px] md:px-[24px] py-[16px] md:py-[20px] border-b border-[#e2e8f0] flex flex-col md:flex-row justify-between items-start md:items-center gap-[16px] bg-[#f8fafc]">
                    <h2 class="font-bold text-[#1b5e20] text-[15px] flex items-center gap-[8px]">
                        <i data-lucide="list" class="text-blue-500 w-[18px] h-[18px]"></i>
                        Daftar Plotting Peserta Anda
                    </h2>
                    
                    <div class="flex items-center gap-[12px]">
                        <span class="text-[13px] font-medium text-[#64748b]">Filter Status:</span>
                        <!-- Mengikat select box ke variabel state 'filter' -->
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
                            {{-- Looping menggunakan fungsi filteredTasks() --}}
                            <template x-for="task in filteredTasks" :key="task.id">
                                <tr class="hover:bg-[#f1f5f9] transition-colors">
                                    <td class="py-[16px] px-[24px]">
                                        <div class="flex items-center gap-[12px]">
                                            <!-- Ubah warna icon berdasarkan status -->
                                            <div class="w-[36px] h-[36px] rounded-full flex items-center justify-center font-bold text-[14px]"
                                                 :class="task.status === 'SUBMITTED' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600'"
                                                 x-text="getInitials(task.institusi?.nama_institusi)"></div>
                                            <div>
                                                <p class="text-[15px] font-semibold text-[#1d293d]" x-text="task.institusi?.nama_institusi || '-'"></p>
                                                <p class="text-[12px] text-[#64748b]" x-text="task.institusi?.jenis_institusi || '-'"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-[16px] px-[24px] text-[14px] text-[#64748b]" x-text="formatDate(task.updated_at)"></td>
                                    <td class="py-[16px] px-[24px]">
                                        <!-- Badge Dinamis -->
                                        <span x-show="task.status === 'SUBMITTED'" class="inline-flex items-center gap-[4px] px-[10px] py-[4px] rounded-full bg-amber-100 text-amber-700 text-[12px] font-bold">
                                            <i data-lucide="clock" class="w-[12px] h-[12px]"></i> Belum Dinilai
                                        </span>
                                        <span x-show="task.status === 'GRADED'" class="inline-flex items-center gap-[4px] px-[10px] py-[4px] rounded-full bg-green-100 text-green-700 text-[12px] font-bold">
                                            <i data-lucide="check-circle-2" class="w-[12px] h-[12px]"></i> Selesai
                                        </span>
                                    </td>
                                    <td class="py-[16px] px-[24px] text-right">
                                        <a :href="'/reviewer/detail/' + task.id" class="inline-flex items-center gap-[6px] bg-white border border-[#cbd5e1] text-[#45556c] px-[16px] py-[8px] rounded-[8px] text-[13px] font-semibold hover:bg-[#f1f5f9] transition-colors">
                                            <i data-lucide="eye" class="w-[14px] h-[14px]"></i> Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            </template>
                            
                            {{-- State Kosong untuk Filter --}}
                            <tr x-show="filteredTasks.length === 0">
                                <td colspan="4" class="py-[20px] text-center text-[#64748b]">Tidak ada data untuk status ini.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Alpine.js Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reviewerDashboard', () => ({
                isLoading: true,
                tasks: [],
                filter: 'all', // State untuk dropdown filter
                summary: {
                    total_tugas: 0,
                    menunggu_review: 0,
                    selesai_review: 0
                },

                // Fungsi dijalankan saat halaman load (x-init)
                initData() {
                    // ZERO-GAP: Memanggil endpoint JSON dari routes/api.php
                    fetch('/api/assessment/reviewer/tasks', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                            // Jika API Anda pakai token spesifik, tambahkan Authorization Bearer di sini
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(json => {
                        if(json.success) {
                            this.summary = json.data;
                            this.tasks = json.data.daftar_asesmen || [];
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching tasks:', error);
                        alert('Gagal mengambil data tugas dari server.');
                    })
                    .finally(() => {
                        this.isLoading = false;
                        // Render ulang icon Lucide setelah DOM berubah (jika library Lucide dipanggil via CDN)
                        if (typeof lucide !== 'undefined') {
                            setTimeout(() => lucide.createIcons(), 50);
                        }
                    });
                },

                // Computed Property untuk Table 1 (Hanya yang SUBMITTED / Belum Dinilai)
                get pendingTasks() {
                    return this.tasks.filter(t => t.status === 'SUBMITTED');
                },

                // Computed Property untuk Table 2 (Berdasarkan Select Filter)
                get filteredTasks() {
                    if (this.filter === 'pending') {
                        return this.tasks.filter(t => t.status === 'SUBMITTED');
                    }
                    if (this.filter === 'done') {
                        return this.tasks.filter(t => t.status === 'GRADED');
                    }
                    return this.tasks; // filter === 'all'
                },

                // Helper: Format ISO Date ke DD MMM YYYY (contoh: 12 Okt 2025)
                formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                },

                // Helper: Membuat Inisial "Universitas Indonesia" -> "UI"
                getInitials(name) {
                    if (!name) return 'NA';
                    const words = name.split(' ');
                    if (words.length >= 2) {
                        return (words[0][0] + words[1][0]).toUpperCase();
                    }
                    return name.substring(0, 2).toUpperCase();
                }
            }));
        });
    </script>
</x-layouts.reviewer>