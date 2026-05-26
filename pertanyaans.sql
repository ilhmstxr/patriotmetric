-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2026 at 04:19 AM
-- Server version: 8.0.45-0ubuntu0.22.04.1
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patriotmetric`
--

--
-- Dumping data for table `pertanyaans`
--

INSERT INTO `pertanyaans` (`id`, `kode_pertanyaan`, `category_id`, `teks_pertanyaan`, `kebutuhan_bukti`, `tipe`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'A.1', 1, 'Kebijakan tentang Implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma dan Penunjang', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li><li><p>Dokumentasi</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:51:51', '2026-05-26 11:01:58'),
(2, 'A.2', 1, 'Kebijakan tentang Pencegahan dan Penanganan Kekerasan', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:51:51', '2026-05-26 11:02:41'),
(3, 'A.3', 1, 'Kebijakan tentang Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika dan Prekursor Narkotika', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:51:52', '2026-05-26 11:03:19'),
(4, 'A.4', 1, 'Kebijakan tentang mitigasi bencana serta satgas yang aktif dalam penanggulangan dan kesiapsiagaan bencana', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li><li><p>Dokumentasi (foto dan video kegiatan)</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:51:53', '2026-05-26 11:05:26'),
(5, 'A.5', 1, 'Kebijakan tentang Penggunaan Produk Dalam Negeri oleh Civitas Akademika', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li><li><p>Dokumentasi</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:51:53', '2026-05-26 11:06:01'),
(6, 'B.1', 2, 'Lama Unit kerja yang berfokus pada pengembangan karakter bela negara (leadership, patriotisme, dsb) dan sudah beroperasi', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li><li><p>sertifikat</p></li></ul>', 'isian_singkat', 'bulan', '2026-05-26 10:51:54', '2026-05-26 11:06:23'),
(7, 'B.2', 2, 'Jumlah implementasi penyelenggaraan MKWK (Mata Kuliah Wajib Kurikulum : Pancasila, Agama, Bahasa Indonesia, Kewarganegaraan)', '<ul><li>RPS</li><li>Portofolio mata kuliah</li></ul>', 'isian_singkat', 'SKS', '2026-05-26 10:51:57', '2026-05-26 10:51:57'),
(8, 'B.3', 2, 'Jumlah mata kuliah wajib institusi berkarakter bela negara (contoh: mata kuliah olahraga/kesehatan mental, bahasa inggris, bela negara, dsb)', '<ul><li>RPS</li><li>Portofolio mata kuliah</li></ul>', 'isian_singkat', 'mata kuliah', '2026-05-26 10:51:57', '2026-05-26 10:51:57'),
(9, 'B.4', 2, 'Jumlah kelompok riset berkarakter bela negara', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Proposal dan Laporan Penelitian Terkait Bela Negara</p></li><li><p>Publikasi Ilmiah Hasil Riset Bela Negara</p></li><li><p>Dokumen Kerja Sama (MoU/MoA)</p></li><li><p>Alokasi Anggaran Riset</p></li></ul>', 'isian_singkat', 'kelompok riset', '2026-05-26 10:51:58', '2026-05-26 11:06:48'),
(10, 'B.5', 2, 'Jumlah pelaksanaan skema Kuliah Kerja Nyata (KKN)', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li><li><p>Dokumentasi</p></li></ul>', 'isian_singkat', 'skema KKN', '2026-05-26 10:51:59', '2026-05-26 11:07:14'),
(11, 'B.6', 2, 'Jumlah luaran pengabdian masyarakat berupa karya inovatif, seperti pengembangan teknologi atau prototipe, desain produk terhadap jumlah dosen', '<ul><li><p>Rekapitulasi Surat Tugas (ST) Pengabdian Masyarakat</p></li><li><p>Daftar karya dan produk inovasi yang dihasilkan</p></li><li><p>Laporan Kegiatan</p></li><li><p>Dokumentasi</p></li></ul>', 'isian_singkat', 'Total luaran Pengabdian', '2026-05-26 10:52:00', '2026-05-26 11:07:46'),
(12, 'B.7', 2, 'Jumlah kolaborasi dengan UMKM atau pelaku usaha lokal untuk mendukung program tridharma terhadap jumlah prodi', '<ul><li>MoU/PKS/IA</li><li>Laporan kegiatan</li><li>Publikasi kegiatan di media</li></ul>', 'isian_singkat', 'total kolaborasi', '2026-05-26 10:52:01', '2026-05-26 10:52:01'),
(13, 'B.8', 2, 'Keikutsertaan dosen dan atau tenaga kependidikan dalam pelatihan bela negara', '<ul><li>Laporan Kegiatan</li><li>Sertifikat</li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:52:01', '2026-05-26 10:52:01'),
(14, 'B.9', 2, 'Lama Pusat konseling bagi sivitas akademik', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan kegiatan</p></li><li><p>sertifikat</p></li></ul>', 'isian_singkat', 'bulan', '2026-05-26 10:52:01', '2026-05-26 11:08:07'),
(15, 'B.10', 2, 'Jumlah pegawai institusi sebagai anggota komponen cadangan (KOMCAD)', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>kartu anggota KOMCAD</p></li><li><p>Dokumen identas sivitas akademika (kartu tanda mahasiswa / kartu pegawai)</p></li></ul>', 'isian_singkat', 'pegawai', '2026-05-26 10:52:02', '2026-05-26 11:09:04'),
(16, 'B.11', 2, 'Penyelenggaraan Upacara Hari Besar Nasional.', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Dokumentasi Kegiatan</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:52:02', '2026-05-26 11:09:32'),
(17, 'B.12', 2, 'Jumlah kegiatan sosialisasi/seminar/workshop tentang langkah-langkah menjaga kebersihan, keamanan dan pengelolaan lingkungan, serta anti-radikalisme, seperti pelatihan deteksi dini risiko keamanan atau mitigasi bencana', '<ul><li>laporan atau publikasi di web/media sosial terkait pelaksanaan  sosialisasi/seminar/workshop</li></ul>', 'isian_singkat', 'kegiatan', '2026-05-26 10:52:03', '2026-05-26 10:52:03'),
(18, 'B.13', 2, 'Jumlah penyelenggaraan kompetisi yang bertema nilai-nilai bela negara', '<ul><li>Dokumen laporan kegiatan</li><li>terpublikasi di media massa (cetak</li><li>online)/website perguruan tinggi</li></ul>', 'isian_singkat', 'kegiatan', '2026-05-26 10:52:03', '2026-05-26 10:52:03'),
(19, 'B.14', 2, 'Jumlah penghargaan atau insentif atas prestasi sivitas akademika dari perguruan tinggi', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>sertifikat</p></li><li><p>dokumentasi kegiatan</p></li></ul>', 'isian_singkat', 'kegiatan', '2026-05-26 10:52:04', '2026-05-26 11:09:55'),
(20, 'B.15', 2, 'Jumlah fasilitas akomodasi yang layak untuk peserta didik penyandang disabilitas di lingkungan pendidikan tinggi', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Dokumentasi Kegiatan (Foto / Video)</p></li><li><p>Laporan Kegiatan</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:52:05', '2026-05-26 11:10:42'),
(21, 'B.16', 2, 'Jumlah ragam dan variasi media kampanye nilai-nilai cinta tanah air (termasuk pentingnya menjaga kebersihan, keamanan lingkungan, penggunaan produk lokal) yang disebarluaskan di area kampus', '<ul><li><p>Poster, video, atau infografis</p></li></ul>', 'isian_singkat', 'variasi', '2026-05-26 10:52:06', '2026-05-26 11:11:06'),
(22, 'B.17', 2, 'Jumlah fasilitas yang disediakan universitas seperti ruang atau galeri yang digunakan untuk menampilkan karya seni atau hasil kreatifitas mahasiswa dan kegiatan kebudayaan, seperti: museum mini, galeri seni, studio, ruang latihan musik dan tari, atau ruang pertunjukan tradisional', '<ul><li>Denah ruang</li><li>video lokasi</li><li>daftar inventaris</li></ul>', 'isian_singkat', 'ruangan', '2026-05-26 10:52:06', '2026-05-26 10:52:06'),
(23, 'B.18', 2, 'Jumlah ruang (sekretariat) untuk organisasi mahasiswa seperti Himpunan Mahasiswa dan UKM terhadap jumlah oganisasi mahasiswa', '<ul><li>Surat Keputusan (SK) Penetapan Ruang</li><li>Dokumentasi</li></ul>', 'isian_singkat', 'ruangan sekretariat', '2026-05-26 10:52:07', '2026-05-26 10:52:07'),
(24, 'B.19', 2, 'Jumlah monumen tentang pahlawan nasional di area perguruan tinggi', '<ul><li>Surat Keputusan (SK)</li><li>Dokumentasi</li></ul>', 'isian_singkat', 'monumen', '2026-05-26 10:52:07', '2026-05-26 10:52:07'),
(25, 'B.20', 2, 'Jumlah UKM Keagamaan terhadap jumlah agama yang dianut oleh mahasiswa', '<ul><li>Surat Keputusan (SK)</li><li>laporan</li><li>data identitas agama mahasiswa</li><li>dan Dokumentasi</li></ul>', 'isian_singkat', 'UKM keagamaan', '2026-05-26 10:52:08', '2026-05-26 10:52:08'),
(26, 'C.1', 3, 'Jumlah mahasiswa aktif sebagai anggota komponen cadangan (KOMCAD)', '<ul><li><p>Dokumen Administrasi / Kelembagaan</p></li><li><p>kartu anggota KOMCAD</p></li><li><p>dokumen identas (kartu tanda mahasiswa)</p></li></ul>', 'isian_singkat', 'mahasiswa', '2026-05-26 10:52:09', '2026-05-26 11:12:13'),
(27, 'C.2', 3, 'Jumlah mahasiswa yang terlibat aktif dalam pencegahan kekerasan di perguruan tinggi', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>laporan kegiatan</p></li></ul>', 'isian_singkat', NULL, '2026-05-26 10:52:10', '2026-05-26 11:12:31'),
(28, 'C.3', 3, 'Keterlibatan mahasiswa pada upaya mitigasi bencana dan atau dalam penanggulangan dan atau kesiapsiagaan bencana (non KKN)', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Laporan Kegiatan</p></li><li><p>Dokumentasi (foto dan video kegiatan)</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:52:10', '2026-05-26 11:13:10'),
(29, 'C.4', 3, 'Mahasiswa yang terlibat pada Kuliah Kerja Nyata (KKN) di daerah 3T/ bencana/ konflik', '<ul><li><p>Laporan Kegiatan</p></li><li><p>Dokumentasi</p></li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:52:11', '2026-05-26 11:13:40'),
(30, 'C.5', 3, 'Jumlah mahasiswa yang terlibat dalam pengabdian masyarakat di luar Kuliah Kerja Nyata (KKN) oleh mahasiswa terhadap jumlah mahasiswa aktif', '<ul><li>Daftar pengabdian masyarakat</li><li>Laporan Kegiatan</li><li>berita/publikasi online</li></ul>', 'isian_singkat', 'mahasiswa', '2026-05-26 10:52:11', '2026-05-26 10:52:11'),
(31, 'C.6', 3, 'Jumlah Unit Kegiatan Mahasiswa (UKM)', '<ul><li><p>Dokumen Administrasi / Internal Kelembagaan</p></li><li><p>Laporan Pertanggungjawaban / Laporan Akhir Kepengurusan Terbaru</p></li></ul>', 'otomatis_sistem', 'UKM', '2026-05-26 10:52:12', '2026-05-26 11:15:20'),
(32, 'C.7', 3, 'Jumlah mahasiswa yang mengikuti UKM terhadap jumlah mahasiswa', '<ul><li>Daftar Nama Mahasiswa Peserta UKM yang di tandatangani oleh Pembina UKM dan disahkan oleh Rektor/Wakil Rektor</li></ul>', 'isian_singkat', 'mahasiswa', '2026-05-26 10:52:12', '2026-05-26 10:52:12'),
(33, 'C.8', 3, 'Jumlah mahasiswa yang mengikuti UKM resimen mahasiswa (MENWA)', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>laporan kegiatan</p></li><li><p>dokumentasi (foto dan video kegiatan)</p></li></ul>', 'isian_singkat', 'mahasiswa', '2026-05-26 10:52:13', '2026-05-26 11:15:48'),
(34, 'C.9', 3, 'Jumlah mahasiswa yang mengikuti organisasi (sesuai bidang keilmuan masing-masing) diluar kampus terhadap jumlah mahasiswa', '<ul><li>Kartu Anggota yang Masih Aktif</li></ul>', 'isian_singkat', 'mahasiswa', '2026-05-26 10:52:13', '2026-05-26 10:52:13'),
(35, 'C.10', 3, 'Jumlah prestasi mahasiswa dalam kompetisi atau perlombaan', '<ul><li><p>Dokumen administrasi / internal kelembagaan</p></li><li><p>Sertifikat</p></li><li><p>Dokumentasi Kegiatan</p></li></ul>', 'isian_singkat', 'prestasi', '2026-05-26 10:52:14', '2026-05-26 11:16:18'),
(36, 'C.11', 3, 'Jumlah mahasiswa yang mendirikan startup yang tergubung dalam inkubator bisnis Perguruan Tinggi', '<ul><li><p>Dokumen Administrasi / Internal Kelembagaan</p></li><li><p>Laporan Pertanggungjawaban Kegiatan</p></li></ul>', 'isian_singkat', 'mahasiswa', '2026-05-26 10:52:15', '2026-05-26 11:16:48'),
(37, 'C.12', 3, 'Penyelenggaraan kegiatan yang berkaitan dengan seni budaya oleh mahasiswa', '<ul><li><p>Daftar kegiatan dan laman / link bukti dokumentasi di Media Massa</p></li></ul>', 'isian_singkat', 'kegiatan', '2026-05-26 10:52:16', '2026-05-26 11:17:14'),
(38, 'C.13', 3, 'Jumlah karya (seni, desain, dan tulis) mahasiswa yang menyuarakan nilai-nilai kebangsaan dan toleransi di media massa (website kampus, berita online, koran, majalah, TV) dan atau jurnal penelitian/pengabdian', '<ul><li>Publikasi karya di media massa</li></ul>', 'isian_singkat', 'karya', '2026-05-26 10:52:16', '2026-05-26 10:52:16'),
(39, 'C.14', 3, 'Jumlah kegiatan mahasiswa dalam menjaga lingkungan di dalam kampus dan terprogram', '<ul><li><p>Publikasi kegiatan di media massa  (website kampus, berita online, koran, majalah, TV)</p></li></ul>', 'isian_singkat', 'kegiatan', '2026-05-26 10:52:17', '2026-05-26 11:18:46'),
(40, 'C.15', 3, 'Keterlibatan mahasiswa dalam Upacara Hari Besar Nasional.', '<ul><li>Dokumentasi</li></ul>', 'pilihan_ganda', NULL, '2026-05-26 10:52:17', '2026-05-26 10:52:17');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
