<x-layouts.dashboard>
    <x-slot:title>Form Rubrik</x-slot:title>

    <div x-data="{
        answers: {},
        links: {},
        mockDatabase: [
          {
            category: 'KEBIJAKAN (01)',
            weight: '25%',
            questions: [
              {
                id: 'kebijakan_1',
                code: '01',
                title: 'Kebijakan/Implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma',
                evidenceRequirements: [
                  'Dokumen Kebijakan berupa SK',
                  'Bukti Implementasi (foto/video/dokumen-nya)'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada kebijakan/SK tapi belum diimplementasikan',
                  'Ada kebijakan dan diimplementasikan dalam satu kegiatan Tridharma',
                  'Ada kebijakan dan diimplementasikan dalam dua kegiatan Tridharma',
                  'Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma',
                  'Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma dan kegiatan penunjang'
                ]
              },
              {
                id: 'kebijakan_2',
                code: '02',
                title: 'Kebijakan pencegahan dan penanganan kekerasan',
                evidenceRequirements: [
                  'Dokumen Kebijakan / Pedoman',
                  'SK Satgas PPKS',
                  'Dokumentasi Sosialisasi',
                  'Laporan/Hasil Aduan'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada kebijakan, pedoman pencegahan dan penanganan kekerasan tetapi belum diimplementasikan',
                  'Ada kebijakan, pedoman, sosialisasi, langkah pencegahan dan penanganan kekerasan',
                  'Ada kebijakan, pedoman, sosialisasi, Satgas Pencegahan dan Penanganan Kekerasan',
                  'Ada kebijakan, pedoman, sosialisasi, Satgas, jurnal pelaporan/tindak lanjut',
                  'Lengkap (beserta tindak lanjut laporan dan/atau pendampingan, perlindungan, pemulihan korban dan sanksi)'
                ]
              },
              {
                id: 'kebijakan_3',
                code: '03',
                title: 'Kebijakan bagi sivitas akademika untuk bangga menggunakan produk lokal dalam pelaksanaan pembelajaran',
                evidenceRequirements: [
                  'Dokumen Edaran SK / Himbauan',
                  'Foto implementasi di lingkungan kampus'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak ada',
                  'Kebijakan ada/tidak tidak diterapkan dalam praktik.',
                  'Kebijakan ada, tetapi penerapannya tidak konsisten dan tidak diawasi/dikendalikan',
                  'Kebijakan ada dan diterapkan, tetapi hanya dalam beberapa prodi/fakultas tertentu.',
                  'Kebijakan penggunaan produk lokal diterapkan secara luas, tetapi belum sepenuhnya didukung oleh seluruh sivitas akademik',
                  'Kebijakan penggunaan produk lokal di kampus diterapkan secara konsisten dan mendapat dukungan penuh'
                ]
              }
            ]
          },
          {
            category: 'KELEMBAGAAN (02)',
            weight: '25%',
            questions: [
              {
                id: 'kelembagaan_1',
                code: '01',
                title: 'Unit kerja yang berfokus pada pengembangan karakter bela negara',
                evidenceRequirements: [
                  'SK Pembentukan Unit Kerja',
                  'Dokumen Program Kerja',
                  'Laporan Pelaksanaan Program'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada unit kerja',
                  'Ada unit kerja, program kerja',
                  'Ada unit kerja, program kerja, kegiatan implementasi program kerja',
                  'Ada unit kerja, program kerja, kegiatan implementasi, evaluasi program',
                  'Ada unit kerja, program kerja, kegiatan implementasi, evaluasi, perencanaan tahun berikutnya'
                ]
              },
              {
                id: 'kelembagaan_2',
                code: '02',
                title: 'Jumlah kelompok riset berkarakter bela negara',
                evidenceRequirements: [
                  'Daftar kelompok riset dari LPPM',
                  'Publikasi hasil jurnal terkait'
                ],
                type: 'short-answer',
                options: []
              }
            ]
          },
          {
            category: 'PATRIOTISME MAHASISWA (03)',
            weight: '10%',
            questions: [
              {
                id: 'patriotisme_1',
                code: '01',
                title: 'Mahasiswa aktif sebagai anggota komponen cadangan (KOMCAD)',
                evidenceRequirements: [
                  'SK Pembentukan Unit Kerja',
                  'Sertifikat/Kartu Anggota KOMCAD'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak ada',
                  '1 mahasiswa aktif',
                  '2 mahasiswa aktif',
                  '3 mahasiswa aktif',
                  '4 mahasiswa aktif',
                  '> 4 mahasiswa aktif'
                ]
              },
              {
                id: 'patriotisme_2',
                code: '02',
                title: 'Jumlah Unit Kegiatan Mahasiswa (UKM)',
                evidenceRequirements: [
                  'SK Rektor/Direktur tentang UKM',
                  'Laporan Kegiatan Tahunan UKM'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak ada',
                  'Terdapat 1 - 5 UKM',
                  'Terdapat 6 - 10 UKM',
                  'Terdapat 11 - 15 UKM',
                  'Terdapat 16 - 20 UKM',
                  'Terdapat > 20 UKM'
                ]
              }
            ]
          }
        ]
    }" class="bg-[#f5f5f5] min-h-full font-['Plus_Jakarta_Sans',sans-serif]">

        {{-- Scrollable content area --}}
        <div class="py-5 px-4 md:px-8">
            <div class="max-w-[960px] mx-auto">

                {{-- Form title --}}
                <h1 class="font-bold text-[#1d293d] text-[18px] uppercase tracking-wide mb-5">Form Rubrik</h1>

                <div class="space-y-6">
                    <template x-for="(categoryData, cIdx) in mockDatabase" :key="cIdx">
                        <div class="space-y-4">

                            {{-- Category Header --}}
                            <div class="flex items-center justify-between border-b border-[#e0e0e0] pb-2">
                                <h2 class="text-[15px] font-bold text-[#1d293d] uppercase tracking-wide" x-text="categoryData.category"></h2>
                                <span class="text-[12px] font-semibold text-[#62748e]" x-text="'Bobot: ' + categoryData.weight"></span>
                            </div>

                            {{-- Questions --}}
                            <template x-for="q in categoryData.questions" :key="q.id">
                                <div class="bg-white border border-[#e0e0e0] rounded-lg overflow-hidden">
                                    <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-[#e0e0e0]">

                                        {{-- LEFT: Question + Evidence --}}
                                        <div class="md:w-[45%] p-5 space-y-4 shrink-0">
                                            {{-- Code + Title --}}
                                            <div class="flex gap-3">
                                                <div class="w-[28px] h-[28px] rounded bg-[#f5f5f5] border border-[#e0e0e0] flex items-center justify-center font-bold text-[#1d293d] text-[12px] shrink-0 mt-0.5"
                                                     x-text="q.code"></div>
                                                <h3 class="font-bold text-[#1d293d] text-[13px] leading-snug" x-text="q.title"></h3>
                                            </div>

                                            {{-- Evidence requirements --}}
                                            <div class="bg-[#fafafa] border border-[#e0e0e0] rounded p-3 space-y-1.5">
                                                <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider mb-2">Syarat Bukti:</p>
                                                <template x-for="(req, rIdx) in q.evidenceRequirements" :key="rIdx">
                                                    <div class="flex gap-2 items-start">
                                                        <span class="shrink-0 mt-1.5 w-1 h-1 bg-[#90a1b9] rounded-full"></span>
                                                        <span class="text-[12px] font-medium text-[#62748e] leading-snug" x-text="req"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- RIGHT: Answers + Link --}}
                                        <div class="flex-1 p-5 space-y-4">
                                            <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider">Jawaban:</p>

                                            {{-- Multiple choice options --}}
                                            <template x-if="q.type === 'multiple-choice'">
                                                <div class="space-y-2">
                                                    <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                                        <button
                                                            type="button"
                                                            @click="answers[q.id] = opt"
                                                            :class="answers[q.id] === opt
                                                                ? 'bg-[#e8f5e9] border-[#1b5e20] text-[#1b5e20] font-semibold'
                                                                : 'bg-white border-[#e0e0e0] text-[#45556c] font-medium hover:border-[#b0b0b0]'"
                                                            class="w-full text-left px-3.5 py-2.5 rounded border text-[12px] leading-snug transition-colors"
                                                            x-text="opt">
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>

                                            {{-- Short answer --}}
                                            <template x-if="q.type !== 'multiple-choice'">
                                                <input
                                                    type="text"
                                                    placeholder="Masukkan jawaban..."
                                                    class="w-full px-3.5 py-2.5 rounded border border-[#e0e0e0] text-[12px] font-medium text-[#1d293d] focus:outline-none focus:border-[#1b5e20] bg-white placeholder-[#90a1b9]"
                                                    x-model="answers[q.id]"
                                                />
                                            </template>

                                            {{-- Tautan Bukti --}}
                                            <div class="pt-2">
                                                <p class="text-[10px] font-bold text-[#62748e] uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                                    <i data-lucide="link" class="w-[12px] h-[12px]"></i>
                                                    Tautan Bukti / Dokumen
                                                </p>
                                                <input
                                                    type="url"
                                                    placeholder="https://drive.google.com/..."
                                                    class="w-full px-3.5 py-2.5 rounded border border-[#e0e0e0] text-[12px] font-medium focus:outline-none focus:border-[#1b5e20] bg-[#fafafa] text-[#1d293d] placeholder-[#90a1b9]"
                                                    x-model="links[q.id]"
                                                />
                                                <p class="text-[10px] font-medium text-[#90a1b9] mt-1.5">* Pastikan tautan dapat diakses publik (Anyone with the link)</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

            </div>
        </div>

        {{-- ✏️ Sticky Footer → components/dashboard/rubrik/footer.blade.php --}}
        <x-dashboard.rubrik.footer />

    </div>
</x-layouts.dashboard>
