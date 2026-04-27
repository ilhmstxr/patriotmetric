<?php

namespace Database\Seeders;

use App\Models\OpsiJawaban;
use App\Models\pertanyaan;
use Illuminate\Database\Seeder;

class PertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        pertanyaan::truncate(); // optional, but let's just create
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();


        $pertanyaan = [
            [
                'kode_pertanyaan' => 'A.1',
                'category_id' => 1,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK rektor, ST, laporan kegiatan, dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Kebijakan tentang Implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma dan Penunjang',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Ada kebijakan tertulis tetapi belum diimplementasikan'],
                    ['urutan' => 2, 'teks_jawaban' => 'Ada kebijakan dan diimplementasikan dalam satu kegiatan dari Tridharma'],
                    ['urutan' => 3, 'teks_jawaban' => 'Ada kebijakan dan diimplementasikan dalam dua kegiatan dari Tridharma'],
                    ['urutan' => 4, 'teks_jawaban' => 'Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma'],
                    ['urutan' => 5, 'teks_jawaban' => 'Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma serta kegiatan penunjang'],
                ],
            ],
            [
                'kode_pertanyaan' => 'A.2',
                'category_id' => 1,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen SK/ST, laporan kegiatan',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Kebijakan tentang Pencegahan dan Penanganan Kekerasan',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang pencegahan dan penanganan kekerasan di tingkat universitas tetapi belum diimplementasikan'],
                    ['urutan' => 2, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang pencegahan dan penanganan kekerasan; sudah dibentuknya unit'],
                    ['urutan' => 3, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang pencegahan dan penanganan kekerasan; sudah dibentuknya unit dan satgas'],
                    ['urutan' => 4, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang pencegahan dan penanganan kekerasan; sudah dibentuknya unit dan satgas; dan sosialisasi pelatihan pencegahan dan penanganan kekerasan'],
                    ['urutan' => 5, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang pencegahan dan penanganan kekerasan; sudah dibentuknya unit dan satgas; sosialisasi; dan kanal pelaporan/bilik aduan'],
                ],
            ],
            [
                'kode_pertanyaan' => 'A.3',
                'category_id' => 1,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen laporan kegiatan, Surat tugas',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Kebijakan tentang Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika tetapi belum diimplementasikan'],
                    ['urutan' => 2, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika; terdapat unit/tim pelaksana'],
                    ['urutan' => 3, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika; sudah dilaksanakan sosialisasi Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika; dan terdapat unit/tim pelaksana'],
                    ['urutan' => 4, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika; sudah dilaksanakan sosialisasi Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika; terdapat unit/tim pelaksana; dan dilaksanakannya kegiatan implementasi (tes urin, dsb)'],
                    ['urutan' => 5, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika; sudah dilaksanakan sosialisasi Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika; terdapat unit/tim pelaksana; dilaksanakannya kegiatan implementasi (tes urin, dsb); serta telah dilaksanakan monitoring dan evaluasi secara berkala'],
                ],
            ],
            [
                'kode_pertanyaan' => 'A.4',
                'category_id' => 1,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen: SOP, SK/ST, laporan kegiatan, dokumentasi (foto dan video kegiatan)',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Kebijakan tentang mitigasi bencana serta satgas yang aktif dalam penanggulangan dan kesiapsiagaan bencana',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang mitigasi bencana tetapi belum diimplementasikan'],
                    ['urutan' => 2, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang mitigasi bencana; sudah dibentuknya satgas penganggulangan dan kesiapsiagaan bencana'],
                    ['urutan' => 3, 'teks_jawaban' => 'Adanya dokumen kebijakan tentang mitigasi bencana; sudah dibentuknya satgas; dan sudah dilakukan sosialisasi tentang mitigasi bencana'],
                    ['urutan' => 4, 'teks_jawaban' => 'Adanya dokumen kebijakan, satgas terlatih, sosialisasi tentang mitigasi bencana; serta memiliki kelengkapan sarana prasarana'],
                    ['urutan' => 5, 'teks_jawaban' => 'Adanya dokumen kebijakan, satgas terlatih, sosialisasi tentang mitigasi bencana; memiliki kelengkapan sarana dan prasarana, serta training berkala'],
                ],
            ],
            [
                'kode_pertanyaan' => 'A.5',
                'category_id' => 1,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Surat Edaran, SK',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Kebijakan tentang Penggunaan Produk Dalam Negeri oleh Civitas Akademika',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Ada kebijakan tetapi belum diimplementasikan'],
                    ['urutan' => 2, 'teks_jawaban' => 'Ada kebijakan tetapi penerapannya tidak konsisten dan tidak diawasi dengan baik.'],
                    ['urutan' => 3, 'teks_jawaban' => 'Ada kebijakan tetapi hanya diterapkan oleh salah satu dari sivitas akademika (dosen saja / tendik saja / mahasiswa saja)'],
                    ['urutan' => 4, 'teks_jawaban' => 'Ada kebijakan dan diterapkan oleh 2 dari 3 bagian sivitas akademika', 'value' => 'Ada kebijakan dan diterapkan oleh 2 dari 3 bagian sivitas akademika'],
                    ['urutan' => 5, 'teks_jawaban' => 'Ada kebijakan dan diterapkan oleh seluruh sivitas akademika (dosen, tendik, dan mahasiswa)'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.1',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK, ST, Laporan kegiatan, sertifikat',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Unit kerja yang berfokus pada pengembangan karakter bela negara (leadership, patriotisme, dsb) dan sudah beroperasi',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'lama beroperasi ≤ 6 bulan', 'value' => '6'],
                    ['urutan' => 2, 'teks_jawaban' => 'lama beroperasi ≤ 12 bulan', 'value' => '12'],
                    ['urutan' => 3, 'teks_jawaban' => 'lama beroperasi ≤ 24 bulan', 'value' => '24'],
                    ['urutan' => 4, 'teks_jawaban' => 'lama beroperasi ≤ 36 bulan', 'value' => '36'],
                    ['urutan' => 5, 'teks_jawaban' => 'lama beroperasi > 36 bulan', 'value' => '36'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.2',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'RPS, Portofolio mata kuliah',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Implementasi penyelenggaraan MKWK (Mata Kuliah Wajib Kurikulum : Pancasila, Agama, Bahasa Indonesia, Kewarganegaraan)',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '2 SKS', 'value' => '2'],
                    ['urutan' => 2, 'teks_jawaban' => '≤ 4 SKS', 'value' => '4'],
                    ['urutan' => 3, 'teks_jawaban' => '≤ 6 SKS', 'value' => '6'],
                    ['urutan' => 4, 'teks_jawaban' => '≤ 8 SKS', 'value' => '8'],
                    ['urutan' => 5, 'teks_jawaban' => '> 8 SKS', 'value' => '9'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.3',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'RPS, Portofolio mata kuliah',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah mata kuliah wajib institusi berkarakter bela negara (contoh: mata kuliah olahraga/kesehatan mental, bahasa inggris, bela negara, dsb)',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1 mata kuliah', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2 mata kuliah', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3 mata kuliah', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '4 mata kuliah', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => '>4 mata kuliah', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.4',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Surat Keputusan (SK), Proposal dan Laporan Penelitian Terkait Bela Negara, Publikasi Ilmiah Hasil Riset Bela Negara, Dokumen Kerja Sama (MoU/MoA), Alokasi Anggaran Riset',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah kelompok riset berkarakter bela negara',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1 kelompok riset', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2 kelompok riset', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3 kelompok riset', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '4 kelompok riset', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => '>4 kelompok riset', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.5',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK, Laporan Kegiatan dan Dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Pelaksanaan skema Kuliah Kerja Nyata (KKN)',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Terlaksana 1 skema KKN', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => 'Terlaksana 2 skema KKN', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => 'Terlaksana 3 skema KKN', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => 'Terlaksana 4 skema KKN', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => 'Terlaksana > 4 skema KKN', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.6',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'ST Pengabdian Masyarakat, Daftar karya dan produk inovasi yang dihasilkan, Laporan Kegiatan, Dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Persentase jumlah luaran pengabdian masyarakat berupa karya inovatif, seperti pengembangan teknologi atau prototipe, desain produk terhadap jumlah dosen',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '≤ 25%', 'value' => '25'],
                    ['urutan' => 2, 'teks_jawaban' => '≤ 50%', 'value' => '50'],
                    ['urutan' => 3, 'teks_jawaban' => '≤ 75%', 'value' => '75'],
                    ['urutan' => 4, 'teks_jawaban' => '≤ 100%', 'value' => '100'],
                    ['urutan' => 5, 'teks_jawaban' => '>100%', 'value' => '101'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.7',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'MoU/PKS/IA, Laporan kegiatan, Publikasi kegiatan di media',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Persentase jumlah kolaborasi dengan UMKM atau pelaku usaha lokal untuk mendukung program tridharma terhadap jumlah prodi',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '≤ 1%', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '≤ 2%', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '≤ 3%', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '≤ 4%', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => '> 4%', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.8',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Laporan Kegiatan, Sertifikat',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Keikutsertaan dosen dan atau tenaga kependidikan dalam pelatihan bela negara',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 5, 'teks_jawaban' => 'Ada'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.9',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK, ST, Laporan kegiatan, sertifikat',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Pusat konseling bagi sivitas akademik',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'lama beroperasi ≤ 6 bulan', 'value' => '6'],
                    ['urutan' => 2, 'teks_jawaban' => 'lama beroperasi ≤ 12 bulan', 'value' => '12'],
                    ['urutan' => 3, 'teks_jawaban' => 'lama beroperasi ≤ 24 bulan', 'value' => '24'],
                    ['urutan' => 4, 'teks_jawaban' => 'lama beroperasi ≤ 36 bulan', 'value' => '36'],
                    ['urutan' => 5, 'teks_jawaban' => 'lama beroperasi > 36 bulan', 'value' => '36'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.10',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen: SK/ST, kartu anggota KOMCAD, dokumen identas sivitas akademika (kartu tanda mahasiswa, kartu pegawai)',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah pegawai institusi sebagai anggota komponen cadangan (KOMCAD)',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1 pegawai institusi yang menjadi KOMCAD', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2 pegawai institusi yang menjadi KOMCAD', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3 pegawai institusi yang menjadi KOMCAD', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '4 pegawai institusi yang menjadi KOMCAD', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => '>4 pegawai institusi yang menjadi KOMCAD', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.11',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Surat Edaran, Dokumentasi Kegiatan',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Penyelenggaraan Upacara Hari Besar Nasional.',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Hanya upacara 17 Agustus', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => 'Hanya Upacara 17 Agustus dan Hari Pendidikan Nasional', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => 'Upacara 17 Agustus, Hari Pendidikan Nasional + 1 Hari Besar Nasional lainnya', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => 'Upacara 17 Agustus, Hari Pendidikan Nasional + 2 Hari Besar Nasional lainnya', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => 'Upacara 17 Agustus, Hari Pendidikan Nasional + 2 Hari Besar Nasional lainnya + Upacara bendera bulanan', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.12',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'laporan atau publikasi di web/media sosial terkait pelaksanaan  sosialisasi/seminar/workshop',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah kegiatan sosialisasi/seminar/workshop tentang langkah-langkah menjaga kebersihan, keamanan dan pengelolaan lingkungan, serta anti-radikalisme, seperti pelatihan deteksi dini risiko keamanan atau mitigasi bencana',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3-4', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '5-12', 'value' => '5'],
                    ['urutan' => 5, 'teks_jawaban' => '>12', 'value' => '13'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.13',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen laporan kegiatan, terpublikasi di media massa (cetak, online)/website kampus',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Skor jumlah penyelenggaraan kompetisi yang bertema nilai-nilai bela negara',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '≤ 25', 'value' => '25'],
                    ['urutan' => 2, 'teks_jawaban' => '≤ 50', 'value' => '50'],
                    ['urutan' => 3, 'teks_jawaban' => '≤ 75', 'value' => '75'],
                    ['urutan' => 4, 'teks_jawaban' => '≤ 100', 'value' => '100'],
                    ['urutan' => 5, 'teks_jawaban' => '>100', 'value' => '101'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.14',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK, sertifikat, dokumentasi kegiatan',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah penghargaan atau insentif atas prestasi sivitas akademika dari perguruan tinggi',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '01-10', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '11-30', 'value' => '11'],
                    ['urutan' => 3, 'teks_jawaban' => '31-60', 'value' => '31'],
                    ['urutan' => 4, 'teks_jawaban' => '61-100', 'value' => '61'],
                    ['urutan' => 5, 'teks_jawaban' => '>100', 'value' => '101'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.15',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen SK/ST/PERTOR, foto/video, laporan',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Fasilitas akomodasi yang layak untuk peserta didik penyandang disabilitas di lingkungan pendidikan tinggi',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'memiliki 1 dari 5 fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)', 'value' => 'memiliki 1 dari 5 fasilitasi (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)'],
                    ['urutan' => 2, 'teks_jawaban' => 'memiliki 2 dari 5 fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)', 'value' => 'memiliki 2 dari 5 fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)'],
                    ['urutan' => 3, 'teks_jawaban' => 'memiliki 3 dari 5 fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)', 'value' => 'memiliki 3 dari 5 fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)'],
                    ['urutan' => 4, 'teks_jawaban' => 'memiliki 4 dari 5 fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)', 'value' => 'memiliki 4 dari 5 fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)'],
                    ['urutan' => 5, 'teks_jawaban' => 'memiliki seluruh fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)', 'value' => 'memiliki seluruh fasilitasi 
                    (1. unit pelayanan disabilitas (wajib); 
                    2. Aksesibilitas pada bangunan gedung; 
                    3. media/alat pembelajaran; 
                    4. ruang pusat sumber; 
                    5. sarana dan prasarana)'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.16',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Poster, video, atau infografis',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah ragam dan variasi media kampanye nilai-nilai cinta tanah air (termasuk pentingnya menjaga kebersihan, keamanan lingkungan, penggunaan produk lokal) yang disebarluaskan di area kampus',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1 variasi', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2 variasi', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3 variasi', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '4 variasi', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => '>4 variasi', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.17',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Denah ruang, video lokasi, daftar inventaris',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Fasilitas yang disediakan universitas seperti ruang atau galeri yang digunakan untuk menampilkan karya seni atau hasil kreatifitas mahasiswa dan kegiatan kebudayaan, seperti: museum mini, galeri seni, studio, ruang latihan musik dan tari, atau ruang pertunjukan tradisional',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1-2 ruangan', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '3-4 ruangan', 'value' => '3'],
                    ['urutan' => 3, 'teks_jawaban' => '5-6 ruangan', 'value' => '5'],
                    ['urutan' => 4, 'teks_jawaban' => '7-8 ruangan', 'value' => '7'],
                    ['urutan' => 5, 'teks_jawaban' => '≥ 9 ruangan', 'value' => '9'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.18',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK Penetapan Ruang, Dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Persentase ruang (sekretariat) untuk organisasi mahasiswa seperti Himpunan Mahasiswa dan UKM terhadap jumlah oganisasi mahasiswa',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '<25%', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '<50%', 'value' => '26'],
                    ['urutan' => 3, 'teks_jawaban' => '<75%', 'value' => '51'],
                    ['urutan' => 4, 'teks_jawaban' => '<100%', 'value' => '76'],
                    ['urutan' => 5, 'teks_jawaban' => '100%', 'value' => '100'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.19',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK, Dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Monumen tentang pahlawan nasional di area perguruan tinggi',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '4', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => '>4', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'B.20',
                'category_id' => 2,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK, laporan, data identitas agama mahasiswa, dan Dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Persentase jumlah UKM Keagamaan terhadap jumlah agama dan aliran kepercayaan yang dianut oleh mahasiswa',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '<25%', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '<50%', 'value' => '26'],
                    ['urutan' => 3, 'teks_jawaban' => '<75%', 'value' => '51'],
                    ['urutan' => 4, 'teks_jawaban' => '<100%', 'value' => '76'],
                    ['urutan' => 5, 'teks_jawaban' => '100%', 'value' => '100'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.1',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen: SK/ST, kartu anggota KOMCAD, dokumen identas (kartu tanda mahasiswa)',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah mahasiswa aktif sebagai anggota komponen cadangan (KOMCAD)',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1 mahasiswa aktif yang menjadi KOMCAD', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2 mahasiswa aktif yang menjadi KOMCAD', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3 mahasiswa aktif yang menjadi KOMCAD', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '4 mahasiswa aktif yang menjadi KOMCAD', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => '>4 mahasiswa aktif yang menjadi KOMCAD', 'value' => '5'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.2',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen SK/ST, laporan kegiatan',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah mahasiswa yang terlibat aktif dalam pencegahan kekerasan di perguruan tinggi',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'jumlah mahasiswa < jumlah fakultas'],
                    ['urutan' => 2, 'teks_jawaban' => 'jumlah mahasiswa = jumlah fakultas PT'],
                    ['urutan' => 3, 'teks_jawaban' => 'jumlah mahasiswa yang terlibat > jumlah fakultas dan < dibandingkan jumlah prodi PT'],
                    ['urutan' => 4, 'teks_jawaban' => 'jumlah mahasiswa yang terlibat sama banyak dibandingkan jumlah prodi PT'],
                    ['urutan' => 5, 'teks_jawaban' => 'jumlah mahasiswa yang terlibat lebih banyak dibandingkan jumlah prodi PT'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.3',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen: SK/ST, laporan kegiatan, dokumentasi (foto dan video kegiatan)',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Keterlibatan mahasiswa pada upaya mitigasi bencana dan atau dalam penanggulangan dan atau kesiapsiagaan bencana (non KKN)',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 2, 'teks_jawaban' => 'Skala dalam kampus'],
                    ['urutan' => 3, 'teks_jawaban' => 'Skala regional kota/kabupaten di luar kampus'],
                    ['urutan' => 4, 'teks_jawaban' => 'Skala Regional Provinsi'],
                    ['urutan' => 5, 'teks_jawaban' => 'Skala nasional/nternasional'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.4',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Laporan Kegiatan dan Dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Mahasiswa yang terlibat pada Kuliah Kerja Nyata (KKN) di daerah 3T/ bencana/ konflik',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 5, 'teks_jawaban' => 'Ada'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.5',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Daftar pengabdian masyarakat, Laporan Kegiatan, berita/publikasi online',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Persentase jumlah mahasiswa yang terlibat dalam pengabdian masyarakat di luar Kuliah Kerja Nyata (KKN) oleh mahasiswa terhadap jumlah mahasiswa aktif',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '0 < x ≤ 0,25%'],
                    ['urutan' => 2, 'teks_jawaban' => '0,25% < x ≤ 0,5%'],
                    ['urutan' => 3, 'teks_jawaban' => '0,5% < x ≤ 0,75%'],
                    ['urutan' => 4, 'teks_jawaban' => '0,75% < x ≤ 1%'],
                    ['urutan' => 5, 'teks_jawaban' => '>1%'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.6',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK REKTOR / PERTOR / SURAT TUGAS --> SK Rektor dan LPJ Terbaru',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah Unit Kegiatan Mahasiswa (UKM)',
                'tipe' => 'otomatis_sistem',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'terdapat 1-5 UKM', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => 'terdapat 6-10 UKM', 'value' => '6'],
                    ['urutan' => 3, 'teks_jawaban' => 'terdapat 11-15 UKM', 'value' => '11'],
                    ['urutan' => 4, 'teks_jawaban' => 'terdapat 16-20 UKM', 'value' => '16'],
                    ['urutan' => 5, 'teks_jawaban' => 'terdapat >20 UKM', 'value' => '21'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.7',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Daftar Nama Mahasiswa Peserta UKM yang di tandatangani oleh Pembina UKM dan disahkan oleh Rektor/Wakil Rektor',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Persentase mahasiswa yang mengikuti UKM terhadap jumlah mahasiswa',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1-20%'],
                    ['urutan' => 2, 'teks_jawaban' => '20<X≤40%'],
                    ['urutan' => 3, 'teks_jawaban' => '40<X≤60%'],
                    ['urutan' => 4, 'teks_jawaban' => '60<X≤80%'],
                    ['urutan' => 5, 'teks_jawaban' => '80<X≤100%'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.8',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumen: SK/ST, laporan kegiatan, dokumentasi (foto dan video kegiatan)',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah mahasiswa yang mengikuti UKM resimen mahasiswa (MENWA)',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1-5 mahasiswa', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '6-10 mahasiswa', 'value' => '6'],
                    ['urutan' => 3, 'teks_jawaban' => '11-15 mahasiswa', 'value' => '11'],
                    ['urutan' => 4, 'teks_jawaban' => '16-20 mahasiswa', 'value' => '16'],
                    ['urutan' => 5, 'teks_jawaban' => '21-25 mahasiswa', 'value' => '21'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.9',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Kartu Anggota yang Masih Aktif',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Persentase mahasiswa yang mengikuti organisasi (sesuai bidang keilmuan masing-masing) diluar kampus terhadap jumlah mahasiswa',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '<1%', 'value' => '<1%'],
                    ['urutan' => 2, 'teks_jawaban' => '1% < x ≤ 2%', 'value' => '1% < x ≤ 2%'],
                    ['urutan' => 3, 'teks_jawaban' => '2%< x ≤ 3%', 'value' => '2%< x ≤ 3%'],
                    ['urutan' => 4, 'teks_jawaban' => '3% < x ≤ 4%', 'value' => '3% < x ≤ 4%'],
                    ['urutan' => 5, 'teks_jawaban' => '>4%', 'value' => '>4%'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.10',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK, sertifikat, dokumentasi kegiatan',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah prestasi mahasiswa dalam kompetisi atau perlombaan',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Total 1–20 poin prestasi', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => 'Total 21–60 poin prestasi', 'value' => '21'],
                    ['urutan' => 3, 'teks_jawaban' => 'Total 61–120 poin prestasi', 'value' => '61'],
                    ['urutan' => 4, 'teks_jawaban' => 'Total 121–200 poin prestasi.', 'value' => '121'],
                    ['urutan' => 5, 'teks_jawaban' => 'Total lebih dari 200 poin prestasi.', 'value' => '201'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.11',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'SK/ & LPJ Kegiatan',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah mahasiswa yang mendirikan startup yang tergubung dalam inkubator bisnis Perguruan Tinggi',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1-5 mahasiswa yang terlibat', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '6-10 mahasiswa yang terlibat', 'value' => '6'],
                    ['urutan' => 3, 'teks_jawaban' => '11-15 mahasiswa yang terlibat', 'value' => '11'],
                    ['urutan' => 4, 'teks_jawaban' => '16-20 mahasiswa yang terlibat', 'value' => '16'],
                    ['urutan' => 5, 'teks_jawaban' => '>20 yang terlibat', 'value' => '21'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.12',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Daftar kegiatan dan link bukti dokumentasi di Media Massa',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Penyelenggaraan kegiatan yang berkaitan dengan seni budaya oleh mahasiswa',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3-4', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '5-12', 'value' => '5'],
                    ['urutan' => 5, 'teks_jawaban' => '>12', 'value' => '13'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.13',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Publikasi karya di media massa',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah karya (seni, desain, dan tulis) mahasiswa yang menyuarakan nilai-nilai kebangsaan dan toleransi di media massa (website kampus, berita online, koran, majalah, TV) dan atau jurnal penelitian/pengabdian',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1-5', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '6-10', 'value' => '6'],
                    ['urutan' => 3, 'teks_jawaban' => '11-15', 'value' => '11'],
                    ['urutan' => 4, 'teks_jawaban' => '16-20', 'value' => '16'],
                    ['urutan' => 5, 'teks_jawaban' => '>20', 'value' => '21'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.14',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Publikasi kegiatan di media massa  (website kampus, berita online, koran, majalah, TV)',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Jumlah kegiatan mahasiswa dalam menjaga lingkungan di dalam kampus dan terprogram',
                'tipe' => 'isian_singkat',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => '1', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => '2', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => '3-4', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => '5-12', 'value' => '5'],
                    ['urutan' => 5, 'teks_jawaban' => '>12', 'value' => '13'],
                ],
            ],
            [
                'kode_pertanyaan' => 'C.15',
                'category_id' => 3,
                'deskripsi' => 'dummy - deskripsi',
                'kebutuhan_bukti' => 'Dokumentasi',
                'skor_maksimal' => 0,
                'teks_pertanyaan' => 'Keterlibatan mahasiswa dalam Upacara Hari Besar Nasional.',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => [
                    ['urutan' => 0, 'teks_jawaban' => 'Tidak ada'],
                    ['urutan' => 1, 'teks_jawaban' => 'Hanya upacara 17 Agustus', 'value' => '1'],
                    ['urutan' => 2, 'teks_jawaban' => 'Upacara 17 Agustus + 1 upacara hari besar nasional lainnya', 'value' => '2'],
                    ['urutan' => 3, 'teks_jawaban' => 'Upacara 17 Agustus + 2 upacara hari besar nasional lainnya', 'value' => '3'],
                    ['urutan' => 4, 'teks_jawaban' => 'Upacara 17 Agustus + 3 upacara hari besar nasional lainnya', 'value' => '4'],
                    ['urutan' => 5, 'teks_jawaban' => 'Lebih dari Upacara 17 Agustus + 3 upacara hari besar nasional lainnya', 'value' => '5'],
                ],
            ],
        ];


        foreach ($pertanyaan as $p) {
            $opsiJawabanData = $p['opsi_jawaban'] ?? null;
            unset($p['opsi_jawaban']);

            $createdPertanyaan = pertanyaan::create($p);

            if (is_array($opsiJawabanData)) {
                foreach ($opsiJawabanData as $opsi) {
                    OpsiJawaban::create([
                        'pertanyaan_id' => $createdPertanyaan->id,
                        'opsi_jawaban' => (string) $opsi['urutan'],
                        'keterangan' => $opsi['teks_jawaban'],
                    ]);
                }
            }
        }
    }
}
