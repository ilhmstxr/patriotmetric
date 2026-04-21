<x-layouts.reviewer>
    <x-slot:title>DETAIL PENILAIAN - {{ request('id', 'Universitas Indonesia') }}</x-slot:title>

    @php $isDone = request('status') === 'done'; @endphp
    <div x-data="{
        isDone: {{ $isDone ? 'true' : 'false' }},
        answers: {
            'kebijakan_1': 'Dibutuhkan dan diimplementasikan dalam dua kegiatan dari Tridharma',
            'kebijakan_2': 'Ada kebijakan, pedoman, sosialisasi, Satgas Pencegahan dan Penanganan Kekerasan',
            'kebijakan_3': 'Kebijakan penggunaan produk lokal diterapkan di terapkan secara konsisten dan mendapat dukungan penuh',
            'kelembagaan_1': 'Ada unit kerja, program kerja, kegiatan implementasi, evaluasi program',
            'kelembagaan_2': '15',
            'patriotisme_1': '2 mahasiswa aktif',
            'patriotisme_2': 'Terdapat 11 - 15 UKM'
        },
        links: {
            'kebijakan_1': 'https://drive.google.com/file/d/dummy-link-1/view',
            'kebijakan_2': 'https://drive.google.com/file/d/dummy-link-2/view',
            'kebijakan_3': 'https://drive.google.com/file/d/dummy-link-3/view',
            'kelembagaan_1': 'https://drive.google.com/file/d/dummy-link-4/view',
            'kelembagaan_2': 'https://drive.google.com/file/d/dummy-link-5/view',
            'patriotisme_1': 'https://drive.google.com/file/d/dummy-link-6/view',
            'patriotisme_2': 'https://drive.google.com/file/d/dummy-link-7/view'
        },
        reviewerScores: JSON.parse(localStorage.getItem('reviewerScores_{{ request('id', '1') }}')) || {},
        init() {
            this.$watch('reviewerScores', value => {
                localStorage.setItem('reviewerScores_{{ request('id', '1') }}', JSON.stringify(value));
            });
        },
        mockDatabase: [
          {
            category: 'KEBIJAKAN (01)',
            weight: '25%',
            questions: [
              {
                id: 'kebijakan_1',
                code: '01',
                title: 'Kebijakan/implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma',
                description: 'Berdasarkan pedoman dan/atau implementasi terdapat unsur bela negara di tingkat akademik.',
                evidenceRequirements: [
                  '1. Dokumen Kebijakan berupa SK',
                  '2. Bukti implementasi (disertasi, tesis, penelitian, atau pengabdian)'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada kebijakan namun tetap belum diimplementasikan',
                  'Dibutuhkan dan diimplementasikan dalam mana kegiatan dari Tridharma',
                  'Dibutuhkan dan diimplementasikan dalam dua kegiatan dari Tridharma',
                  'Dibutuhkan dan diimplementasikan dalam seluruh kegiatan Tridharma serta kegiatan penunjang',
                  'Dibutuhkan dan diimplementasikan dalam seluruh kegiatan Tridharma dan kegiatan penunjang'
                ]
              },
              {
                id: 'kebijakan_2',
                code: '02',
                title: 'Kebijakan pencegahan dan penanganan kekerasan',
                description: 'Meliputi kelengkapan instrumen pencegahan dan penanganan kekerasan di kampus.',
                evidenceRequirements: [
                  '1. Dokumen Kebijakan / Pedoman',
                  '2. SK Satgas PPKS',
                  '3. Dokumentasi Sosialisasi',
                  '4. Laporan/jurnal tindak lanjut'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Ada kebijakan, pedoman pencegahan dan penanganan kekerasan tetapi belum diimplementasikan',
                  'Ada kebijakan, pedoman, sosialisasi, langkah pencegahan dan penanganan kekerasan',
                  'Ada kebijakan, pedoman, sosialisasi, Satgas Pencegahan dan Penanganan Kekerasan',
                  'Ada kebijakan, pedoman, sosialisasi, Satgas, jurnal pelaporan/tindak lanjut',
                  'Lengkap beserta tindak lanjut laporan (termasuk pendampingan, perlindungan, pemulihan korban dan sanksi)'
                ]
              },
              {
                id: 'kebijakan_3',
                code: '03',
                title: 'Kebijakan bagi sivitas akademika untuk bangga menggunakan produk lokal dalam pelaksanaan pembelajaran',
                description: 'Contoh: penggunaan Batik, pakaian adat.',
                evidenceRequirements: [
                  '1. Dokumen Edaran Rektor/Dekan',
                  '2. Foto implementasi di lingkungan kampus'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
                  'Kebijakan ada/tidak tidak diterapkan dalam praktik.',
                  'Kebijakan ada, tetapi penerapannya tidak konsisten dan tidak diawasi/dikendalikan',
                  'Kebijakan ada dan diterapkan tetapi hanya dalam beberapa bagian/karakteristik tertentu',
                  'Kebijakan penggunaan produk lokal diterapkan luas, tetapi belum sepenuhnya didukung oleh seluruh sivitas akademik',
                  'Kebijakan penggunaan produk lokal diterapkan di terapkan secara konsisten dan mendapat dukungan penuh'
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
                description: 'Unit khusus yang berdedikasi membangun karakter kepemimpinan dan patriotisme sivitas.',
                evidenceRequirements: [
                  '1. SK Pembentukan Unit Kerja',
                  '2. Dokumen Program Kerja',
                  '3. Laporan Pelaksanaan Program'
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
                description: 'Mendata total persentase proporsi dosen/mahasiswa dengan tema terkait bela negara.',
                evidenceRequirements: [
                  '1. Daftar Kelompok Riset / PkM',
                  '2. Publikasi Hasil Jurnal Terkait'
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
                description: 'Jumlah mahasiswa aktif yang terdaftar secara resmi sebagai KOMCAD sampai masa TS.',
                evidenceRequirements: [
                  '1. Daftar Mahasiswa KOMCAD',
                  '2. Sertifikat/Kartu Anggota KOMCAD'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
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
                description: 'Jumlah total UKM resmi yang aktif beroperasi di perguruan tinggi.',
                evidenceRequirements: [
                  '1. SK Rektor/Direktur tentang UKM',
                  '2. Laporan Kegiatan Tahunan UKM'
                ],
                type: 'multiple-choice',
                options: [
                  'Tidak Ada',
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
    }" class="flex-1 flex flex-col h-full bg-[#f8fafc] font-['Plus_Jakarta_Sans',sans-serif]">
        
      {{-- Page Header --}}
      <div class="bg-white border-b border-[#e2e8f0] px-[20px] md:px-[40px] py-[20px] md:py-[28px] flex items-center justify-between shadow-sm">
        <div>
          <a href="{{ route('reviewer.index') }}" class="inline-flex items-center gap-[6px] text-[#62748e] hover:text-[#1b5e20] text-[13px] font-semibold mb-[8px] transition-colors">
            <i data-lucide="arrow-left" class="w-[14px] h-[14px]"></i> Kembali ke Daftar Plotting
          </a>
          <h1 class="font-bold text-[#1d293d] text-[20px] md:text-[26px] tracking-tight flex flex-col md:flex-row items-start md:items-center gap-[8px] md:gap-[12px]">
            Detail Penilaian - {{ request('id', 'Universitas Indonesia') }}
            @if($isDone)
            <span class="inline-flex items-center gap-[6px] bg-green-100 text-green-700 px-[12px] py-[4px] rounded-full text-[13px] md:text-[14px] font-bold">
                <i data-lucide="check-circle-2" class="w-[16px] h-[16px]"></i> Selesai Dinilai
            </span>
            @endif
          </h1>
          <p class="text-[#62748e] text-[13px] md:text-[15px] mt-[6px]">Review isian rubrik, periksa bukti dokumen, dan berikan skor final untuk institusi ini.</p>
        </div>
      </div>

      {{-- Content Area --}}
      <div class="flex-1 overflow-y-auto p-[20px] md:p-[32px] relative">
        <div class="space-y-[24px] md:space-y-[32px] max-w-[1000px]">
          <template x-for="(categoryData, cIdx) in mockDatabase" :key="cIdx">
            <div class="space-y-[16px]">
              {{-- Category Header --}}
              <div class="flex items-center justify-between border-b-[2px] border-[#e2e8f0] pb-[8px] mb-[16px]">
                <h2 class="text-[18px] font-bold text-[#1d293d] uppercase" x-text="categoryData.category"></h2>
                <span class="text-[14px] font-semibold text-[#62748e] bg-white border border-[#e2e8f0] px-[12px] py-[4px] rounded-full shadow-sm" x-text="'Bobot: ' + categoryData.weight"></span>
              </div>

              {{-- Questions --}}
              <template x-for="q in categoryData.questions" :key="q.id">
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-[20px] md:p-[24px] flex flex-col md:flex-row gap-[20px] md:gap-[24px] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1),0px_1px_2px_-1px_rgba(0,0,0,0.1)] mb-[16px]">
                  
                  {{-- Left Column: Question & Evidence --}}
                  <div class="flex-[1.2] space-y-[16px]">
                    <div class="flex gap-[16px]">
                      <div class="w-[40px] h-[40px] rounded-[8px] bg-[#f8fafc] border border-[#e2e8f0] flex items-center justify-center font-bold text-[#1d293d] text-[16px] shrink-0" x-text="q.code">
                      </div>
                      <div>
                        <h3 class="font-bold text-[#1d293d] text-[15px] leading-[22px]" x-text="q.title"></h3>
                        <p class="text-[13px] font-medium text-[#62748e] mt-[6px] leading-[20px]" x-text="q.description"></p>
                      </div>
                    </div>

                    <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-[8px] p-[16px] ml-0 md:ml-[56px]">
                      <h4 class="text-[12px] font-bold text-[#45556c] mb-[8px] uppercase tracking-[0.4px]">Syarat Bukti:</h4>
                      <ul class="text-[13px] font-medium text-[#62748e] space-y-[6px]">
                        <template x-for="(req, rIdx) in q.evidenceRequirements" :key="rIdx">
                          <li class="flex gap-[6px] items-start">
                            <span class="mt-[2px] w-[4px] h-[4px] bg-[#90A1B9] rounded-full shrink-0"></span>
                            <span class="leading-[18px]" x-text="req.replace(/^\d+\.\s*/, '')"></span>
                          </li>
                        </template>
                      </ul>
                    </div>
                  </div>

                  {{-- Right Column: Answers & Link --}}
                  <div class="flex-[1.8] flex flex-col space-y-[20px] md:border-l border-[#e2e8f0] md:pl-[24px]">
                    {{-- Peserta's Answer --}}
                    <div>
                        <h4 class="text-[12px] font-bold text-[#45556c] uppercase tracking-[0.4px] mb-[8px]">Jawaban Peserta:</h4>
                        
                        <template x-if="q.type === 'multiple-choice'">
                            <div class="space-y-[10px]">
                                <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                    <div 
                                      :class="answers[q.id] === opt ? 'bg-blue-50 border border-blue-200 text-[#1d293d] font-semibold shadow-sm' : 'bg-[#f8fafc] border border-transparent text-[#94a3b8] opacity-70'"
                                      class="w-full text-left px-[16px] py-[12px] rounded-[8px] text-[14px] leading-[20px] flex items-center justify-between"
                                    >
                                      <span x-text="opt"></span>
                                      <template x-if="answers[q.id] === opt">
                                          <i data-lucide="check-circle-2" class="w-[20px] h-[20px] text-blue-600 shrink-0"></i>
                                      </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="q.type !== 'multiple-choice'">
                            <div class="bg-blue-50 border border-blue-200 rounded-[8px] p-[12px] text-blue-900 font-semibold text-[14px]">
                                <p x-text="answers[q.id] || 'Belum diisi'"></p>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Peserta's Link Evidence --}}
                    <div>
                        <h4 class="text-[12px] font-bold text-[#45556c] uppercase tracking-[0.4px] flex items-center gap-[6px] mb-[8px]">
                            <i data-lucide="link" class="w-[14px] h-[14px]"></i>
                            Tautan BUKTI / Dokumen:
                        </h4>
                        <template x-if="links[q.id]">
                            <a :href="links[q.id]" target="_blank" class="inline-flex items-center justify-between w-full bg-white border border-[#cad5e2] text-[#1b5e20] hover:border-[#1b5e20] hover:bg-[#f2fcf3] px-[16px] py-[12px] rounded-[8px] transition-colors group">
                                <span class="text-[14px] font-semibold truncate flex-1" x-text="links[q.id]"></span>
                                <i data-lucide="external-link" class="w-[16px] h-[16px] text-[#90a1b9] group-hover:text-[#1b5e20] shrink-0 ml-[12px]"></i>
                            </a>
                        </template>
                        <template x-if="!links[q.id]">
                            <div class="w-full bg-[#f8fafc] border border-[#e2e8f0] text-[#90a1b9] px-[16px] py-[12px] rounded-[8px] text-[14px] font-medium flex items-center justify-center gap-[8px]">
                                <i data-lucide="link-2-off" class="w-[16px] h-[16px]"></i> Tidak ada link disematkan
                            </div>
                        </template>
                    </div>

                    {{-- Reviewer Validation & Scoring --}}
                    <div class="pt-[16px] border-t border-dashed border-[#cbd5e1] mt-auto">
                        <h4 class="text-[12px] font-bold text-[#1b5e20] uppercase tracking-[0.4px] mb-[8px] flex items-center gap-[6px]">
                            <i data-lucide="check-square" class="w-[14px] h-[14px]"></i> Score Final Reviewer
                        </h4>
                        <div class="flex items-center gap-[12px]">
                            <input
                              type="number"
                              min="0"
                              max="5"
                              placeholder="0 - 5"
                              class="w-[100px] px-[16px] py-[10px] rounded-[8px] border-2 text-[16px] font-bold focus:outline-none focus:border-[#1b5e20] text-center"
                              :class="isDone ? 'bg-[#f1f5f9] border-[#cbd5e1] text-[#94a3b8]' : 'border-[#1b5e20]/30 text-[#1b5e20] bg-white'"
                              :disabled="isDone"
                              x-model="reviewerScores[q.id]"
                            />
                            <span class="text-[13px] text-[#64748b] font-medium">Beri skor 0 hingga 5 berdasarkan panduan.</span>
                        </div>
                    </div>
                  </div>

                </div>
              </template>
            </div>
          </template>
        </div>
      </div>

      {{-- Footer sticky area --}}
      <template x-if="!isDone">
          <div class="bg-white border-t border-[#e2e8f0] px-[20px] md:px-[32px] py-[16px] flex flex-col sm:flex-row justify-between items-center gap-[16px] shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="flex items-center gap-[8px] text-[12px] md:text-[14px] font-medium text-[#62748e]">
              <span class="w-[8px] h-[8px] rounded-full bg-amber-400 animate-pulse shrink-0"></span>
              Pekerjaan Anda otomatis disimpan sebagai Draft. Cek ulang sebelum menyelesaikan!
            </div>
            <button class="w-full sm:w-auto bg-[#1b5e20] hover:bg-[#15461c] text-white px-[24px] h-[44px] rounded-[8px] text-[13px] md:text-[14px] font-bold flex items-center justify-center gap-[8px] transition-colors shadow-[0px_1px_3px_0px_rgba(0,0,0,0.1)]">
              <i data-lucide="check-circle" class="w-[18px] h-[18px]"></i>
              Selesaikan & Simpan Penilaian
            </button>
          </div>
      </template>
    </div>
</x-layouts.reviewer>
