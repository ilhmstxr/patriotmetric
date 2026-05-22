<?php

namespace Database\Seeders;

use App\Models\ComproContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComproContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds all 7 compro pages with content extracted from blade templates.
     * Uses firstOrCreate for idempotency — existing records are never overwritten.
     */
    public function run(): void
    {
        $this->seedCmsImages();

        DB::transaction(function () {
            $this->seedWelcomePage();
            $this->seedProfilePage();
            $this->seedVisiMisiPage();
            $this->seedTimPage();
            $this->seedPenghargaanPage();
            $this->seedPanduanPage();
            $this->seedPengumumanPage();
        });

        // Clear all compro content cache after seeding
        $pages = ['welcome', 'profile', 'visi-misi', 'tim', 'penghargaan', 'panduan', 'pengumuman'];
        foreach ($pages as $page) {
            \Illuminate\Support\Facades\Cache::forget("compro_content.{$page}");
        }
    }

    /**
     * Copy default CMS images from public/assets/images to cms disk.
     */
    private function seedCmsImages(): void
    {
        $disk = Storage::disk('cms');
        $sourceDir = public_path('assets/images');

        $files = [
            '46257018a5d0ac00852b82184ae3ed30ef9a74e4.webp',
            'bg.webp',
            'b4f942a6770a3928dc2f82d398369a3d39ba1fde.webp',
            '199dc2ebf1e9cecf5218f4b20951209708831231.webp',
            'blank-profile-picture-973460_1280.webp',
        ];

        foreach ($files as $file) {
            $sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $file;
            if (file_exists($sourcePath) && !$disk->exists("images/{$file}")) {
                $disk->put("images/{$file}", file_get_contents($sourcePath));
            }
        }
    }

    /**
     * Helper method to create a content record (idempotent).
     */
    private function createContent(string $page, string $section, string $key, string $type, mixed $value, int $order = 0): void
    {
        ComproContent::firstOrCreate(
            ['page' => $page, 'section' => $section, 'key' => $key],
            ['type' => $type, 'value' => is_array($value) ? json_encode($value) : $value, 'order' => $order]
        );
    }

    /**
     * Seed Welcome (Homepage) content.
     */
    private function seedWelcomePage(): void
    {
        $page = 'welcome';

        // Hero Section
        $this->createContent($page, 'hero', 'judul', 'text', 'Membangun Karakter Bangsa dari Kampus', 1);
        $this->createContent($page, 'hero', 'deskripsi', 'text', 'Sebuah sistem pemeringkatan nasional yang didedikasikan untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi.', 2);
        $this->createContent($page, 'hero', 'background_image', 'image', 'images/46257018a5d0ac00852b82184ae3ed30ef9a74e4.webp', 3);

        // About Section
        $this->createContent($page, 'about', 'judul', 'text', 'Patriot Metric', 1);
        $this->createContent($page, 'about', 'deskripsi', 'richtext', '<p>Patriot Metric University Ranking merupakan sistem pemeringkatan kinerja yang mengintegrasikan pengukuran capaian akademik, tata kelola institusi,dan pembentukan karakter Bela Negara ke dalam satu kerangka nilai yang utuh, melalui aspek kebijakan, kelembagaan, dan patriotisme peserta didik. </p>
        <ul><li>Refleksi kinerja institusi berbasis nilai
</li><li>Penguatan arah kebijakan dan tata kelola
</li><li>Internalisasi nilai kebangsaan secara terukur
</li><li>Jejaring dan pembelajaran antar institusi
</li></ul>', 2);
        $this->createContent($page, 'about', 'video_url', 'url', 'https://www.youtube.com/embed/nB4YzOhnkBo?si=KXFTn2dRpO-TDdKc', 3);

        // Institusi Partisipan Section
        $this->createContent($page, 'institusi', 'judul', 'text', 'Institusi yang Telah Berpartisipasi', 1);
        $this->createContent($page, 'institusi', 'deskripsi', 'text', 'Bergabung bersama perguruan tinggi terbaik Indonesia dalam mewujudkan kampus berkarakter bela negara.', 2);
        $this->createContent($page, 'institusi', 'daftar_baris_1', 'repeater', [
            ['nama' => 'UPN "Veteran" Jawa Timur', 'logo' => ''],
            ['nama' => 'Universitas Negeri Surabaya', 'logo' => ''],
            ['nama' => 'Universitas 17 Agustus', 'logo' => ''],
        ], 3);
        $this->createContent($page, 'institusi', 'daftar_baris_2', 'repeater', [
            ['nama' => 'UPN "Veteran" Yogyakarta', 'logo' => ''],
            ['nama' => 'Universitas Bhayangkara Jakarta Raya', 'logo' => ''],
            ['nama' => 'Universitas Mega Buana Palopo', 'logo' => ''],
        ], 4);

        // Timeline Section
        $this->createContent($page, 'timeline', 'judul', 'text', 'Timeline Patriot Metric', 1);
        $this->createContent($page, 'timeline', 'deskripsi', 'text', 'Jadwal dan tahapan proses pemeringkatan institusi Anda.', 2);
        $this->createContent($page, 'timeline', 'daftar', 'repeater', [
            ['nomor' => '01', 'tanggal' => '1 - 31 Agustus', 'judul' => 'Pembukaan Registrasi', 'deskripsi' => 'Periode pendaftaran institusi melalui portal Patriot Metric.'],
            ['nomor' => '02', 'tanggal' => '1 - 15 September', 'judul' => 'Validasi Akun', 'deskripsi' => 'Verifikasi data institusi dan PIC yang telah didaftarkan.'],
            ['nomor' => '03', 'tanggal' => '16 Sep - 31 Okt', 'judul' => 'Mulai Pengisian Rubrik', 'deskripsi' => 'Periode pengisian rubrik penilaian dan unggah bukti pendukung.'],
            ['nomor' => '04', 'tanggal' => '1 - 30 November', 'judul' => 'Validasi Penilaian Rubrik', 'deskripsi' => 'Tim penilai melakukan verifikasi data melalui Patriot Metric.'],
            ['nomor' => '05', 'tanggal' => '1 - 15 Desember', 'judul' => 'Pengolahan', 'deskripsi' => 'Pengolahan data dan kalkulasi skor pemeringkatan nasional.'],
            ['nomor' => '06', 'tanggal' => '17 Agustus', 'judul' => 'Penghargaan', 'deskripsi' => 'Pengumuman hasil dan upacara penghargaan nasional.'],
        ], 3);

        // Instagram Section
        $this->createContent($page, 'instagram', 'judul', 'text', 'Ikuti Aktivitas Kami', 1);
        $this->createContent($page, 'instagram', 'deskripsi', 'text', 'Pantau perkembangan terbaru Patriot Metric melalui Instagram kami.', 2);
        $this->createContent($page, 'instagram', 'posts', 'repeater', [
            ['url' => 'https://www.instagram.com/reel/DQ5YayFEtfN/', 'gambar' => '', 'alt_text' => 'Post Instagram Patriot Metric 1'],
            ['url' => 'https://www.instagram.com/p/DQssRuxksft/', 'gambar' => '', 'alt_text' => 'Post Instagram Patriot Metric 2'],
        ], 3);
    }

    /**
     * Seed Profile page content.
     */
    private function seedProfilePage(): void
    {
        $page = 'profile';

        // Hero Section
        $this->createContent($page, 'hero', 'judul', 'text', 'UPN VETERAN JATIMPATRIOT METRIC <br> <span class="text-[20px] sm:text-[28px] md:text-[32px] font-semibold text-white/90">UNIVERSITY RANKING</span>', 1);
        $this->createContent($page, 'hero', 'deskripsi', 'text', 'Sebuah sistem pemeringkatan nasional yang didedikasikan untuk mengukur, membina, dan mengapresiasi nilai-nilai bela negara di lingkungan pendidikan tinggi.', 2);
        $this->createContent($page, 'hero', 'background_image', 'image', 'images/bg.webp', 3);

        // Latar Belakang Section
        $this->createContent($page, 'latar-belakang', 'judul', 'text', 'Latar Belakang', 1);
        $this->createContent($page, 'latar-belakang', 'deskripsi', 'richtext', '<p>Di tengah arus globalisasi, nilai patriotisme
menghadapi tantangan serius, mulai dari
menurunnya pemahaman sejarah, derasnya arus
disinformasi dan radikalisme digital yang memicu
polarisasi, hingga meningkatnya individualisme
yang menggerus kepedulian sosial.</p><p>
<strong>Patriot Metric</strong> hadir sebagai jawaban atas
kebutuhan tersebut. Gagasan ini berangkat dari
identitas historis UPN “Veteran” Jawa Timur
sebagai kampus Bela Negara yang lahir dari
semangat para pejuang kemerdekaan, dengan
semboyan <i>Widya Mwat Yasa</i> – ilmu pengetahuan
yang diabdikan untuk pembangunan bangsa.</p>
<p><strong>Patriot Metric</strong> merupakan instrumen evaluasi yang objektif dan
terstandarisasi, agar pembinaan kesadaran bela negara, khususnya
dalam konteks nasionalisme dan patriotisme, dapat dianalisis,
dievaluasi, dan dikembangkan secara berkelanjutan.</p>
<p>Instrumen Patriot Metric dirancang berbasis konstruk psikososial
sehingga tidak hanya dipahami secara normatif, tetapi juga dapat
memotivasi institusi pendidikan tinggi melalui internalisasi,
implementasi, dan pengembangan nilai-nilai bela negara serta
karakter kebangsaan dalam Tridharma Perguruan Tinggi.</p>
', 2);





        // Tujuan Utama Section
    $this->createContent($page, 'tujuan-utama', 'judul', 'text', 'Tujuan Pemeringkatan', 1);
        $this->createContent($page, 'tujuan-utama', 'deskripsi', 'text', ' ', 2);
        $this->createContent($page, 'tujuan-utama', 'daftar', 'repeater', [
            ['nomor' => '01', 'judul' => 'Menjadi Instrumen
Evaluasi Pembinaan
Kesadaran Bela Negara', 'deskripsi' => 'Menilai sejauh mana upaya
pembinaan karakter bela negara
telah diinternalisasikan melalui
kebijakan dan program
perguruan tinggi.'],
            ['nomor' => '02', 'judul' => 'Memperkuat Ekosistem
Perguruan Tinggi Berbasis
Nilai-nilai Bela Negara', 'deskripsi' => 'Tercipta ekosistem perguruan
tinggi yang lebih kuat dalam
menanamkan nilai-nilai bela
negara dan tanggung jawab
sosial melalui berbagai aspek
Tridharma Perguruan Tinggi.'],
            ['nomor' => '03', 'judul' => 'Mendorong Sinergi
Antar-Perguruan Tinggi', 'deskripsi' => 'Perguruan Tinggi dapat saling
memotivasi, bersama-sama
membangun generasi muda
yang berdaya saing global,
adaptif, dan patriotik.'],
        ], 3);
    }

    /**
     * Seed Visi & Misi page content.
     */
    private function seedVisiMisiPage(): void
    {
        $page = 'visi-misi';

        // Hero Section
        $this->createContent($page, 'hero', 'judul', 'text', 'Visi & Misi', 1);
        $this->createContent($page, 'hero', 'deskripsi', 'text', 'Arah dan strategi Patriot Metric dalam membangun ekosistem bela negara di perguruan tinggi Indonesia.', 2);

        // Visi Section
        $this->createContent($page, 'visi', 'teks', 'text', 'Menjadi platform pemeringkatan dan barometer paling prestisius di Indonesia dalam mengukur, mengembangkan, dan mengapresiasi implementasi nilai-nilai bela negara di perguruan tinggi.', 1);

        // Misi Section
        $this->createContent($page, 'misi', 'judul', 'text', 'Misi Strategis', 1);
        $this->createContent($page, 'misi', 'daftar', 'repeater', [
            ['nomor' => '01', 'judul' => 'Internalisasi', 'deskripsi' => 'Proses penanaman dan integrasi nilai-nilai bela negara ke dalam budaya, kebijakan, kurikulum, serta program pengembangan mahasiswa.'],
            ['nomor' => '02', 'judul' => 'Implementasi', 'deskripsi' => 'Perwujudan nyata dari proses internalisasi dalam bentuk tindakan, aktivitas, dan keterlibatan aktif sivitas akademika dalam kegiatan bertema kebangsaan dan bela negara.'],
            ['nomor' => '03', 'judul' => 'Pengembangan', 'deskripsi' => 'Upaya inovatif untuk memperkaya dan memperluas penerapan nilai-nilai bela negara, baik melalui penelitian, pengabdian kepada masyarakat, maupun kemitraan strategis.'],
        ], 2);
    }

    /**
     * Seed Tim page content.
     */
    private function seedTimPage(): void
    {
        $page = 'tim';

        // Hero Section
        $this->createContent($page, 'hero', 'judul', 'text', 'Tim Kami', 1);
        $this->createContent($page, 'hero', 'deskripsi', 'text', 'Para profesional yang berdedikasi dalam mengembangkan dan mengelola sistem pemeringkatan Patriot Metric.', 2);

        // Team Grid Section
        $this->createContent($page, 'team-grid', 'daftar', 'repeater', [
            ['nama' => 'Lorem Ipsum', 'role' => 'Lorem Ipsum', 'foto' => 'images/blank-profile-picture-973460_1280.webp'],
            ['nama' => 'Lorem Ipsum', 'role' => 'Lorem Ipsum', 'foto' => 'images/blank-profile-picture-973460_1280.webp'],
            ['nama' => 'Lorem Ipsum', 'role' => 'Lorem Ipsum', 'foto' => 'images/blank-profile-picture-973460_1280.webp'],
            ['nama' => 'Lorem Ipsum', 'role' => 'Lorem Ipsum', 'foto' => 'images/blank-profile-picture-973460_1280.webp'],
        ], 1);
    }

    /**
     * Seed Penghargaan page content.
     */
    private function seedPenghargaanPage(): void
    {
        $page = 'penghargaan';

        // Hero Section
        $this->createContent($page, 'hero', 'judul', 'text', 'Galeri Penghargaan', 1);
        $this->createContent($page, 'hero', 'deskripsi', 'text', 'Penghormatan tertinggi bagi institusi yang telah membuktikan dedikasinya dalam membangun karakter patriotik dan bela negara.', 2);
        $this->createContent($page, 'hero', 'background_image', 'image', 'images/b4f942a6770a3928dc2f82d398369a3d39ba1fde.webp', 3);

        // Daftar Penerima Section
        $this->createContent($page, 'daftar-penerima', 'judul', 'text', 'Daftar Institusi Peraih Penghargaan', 1);
        $this->createContent($page, 'daftar-penerima', 'daftar', 'repeater', [
            ['nama' => 'Lorem Ipsum', 'logo' => 'images/199dc2ebf1e9cecf5218f4b20951209708831231.webp', 'rating' => 5],
            ['nama' => 'Lorem Ipsum', 'logo' => 'images/199dc2ebf1e9cecf5218f4b20951209708831231.webp', 'rating' => 5],
            ['nama' => 'Lorem Ipsum', 'logo' => 'images/199dc2ebf1e9cecf5218f4b20951209708831231.webp', 'rating' => 4.5],
            ['nama' => 'Lorem Ipsum', 'logo' => 'images/199dc2ebf1e9cecf5218f4b20951209708831231.webp', 'rating' => 4],
            ['nama' => 'Lorem Ipsum', 'logo' => 'images/199dc2ebf1e9cecf5218f4b20951209708831231.webp', 'rating' => 5],
            ['nama' => 'Lorem Ipsum', 'logo' => 'images/199dc2ebf1e9cecf5218f4b20951209708831231.webp', 'rating' => 4],
        ], 2);
    }

    /**
     * Seed Panduan page content.
     */
    private function seedPanduanPage(): void
    {
        $page = 'panduan';

        // Hero Section
        $this->createContent($page, 'hero', 'judul', 'text', 'Panduan Penggunaan Sistem', 1);
        $this->createContent($page, 'hero', 'deskripsi', 'text', 'Langkah mudah dan terstruktur untuk mendaftarkan dan menilai institusi Anda di Patriot Metric.', 2);
        $this->createContent($page, 'hero', 'tombol_teks', 'text', 'Pedoman Patriot Metric UPN Veteran Jatim', 3);
        $this->createContent($page, 'hero', 'tombol_link', 'url', 'https://bit.ly/PEDOMANPATRIOTMETRIC', 4);

        // Steps Section
        $this->createContent($page, 'steps', 'daftar', 'repeater', [
            ['label' => 'Langkah 1', 'judul' => 'Input Data', 'deskripsi' => 'Peserta mengisi formulir pemeringkatan secara daring dan mengunggah dokumen pendukung.', 'icon' => 'user-plus'],
            ['label' => 'Langkah 2', 'judul' => 'Validasi', 'deskripsi' => 'Proses validasi oleh Tim Evaluator untuk memastikan keabsahan data, termasuk wawancara & visitasi lapangan.', 'icon' => 'file-check'],
            ['label' => 'Langkah 3', 'judul' => 'Penilaian', 'deskripsi' => 'Penilaian untuk setiap indikator berbentuk skor angka dan diolah secara statistik.', 'icon' => 'trending-up'],
            ['label' => 'Langkah 4', 'judul' => 'Pengumuman', 'deskripsi' => 'Hasil akhir ditetapkan berdasarkan skor kumulatif dan disampaikan dalam bentuk peringkat bintang.', 'icon' => 'check-circle'],
        ], 1);

        // FAQ Section
        $this->createContent($page, 'faq', 'judul', 'text', 'Tanya Jawab (FAQ)', 1);
        $this->createContent($page, 'faq', 'daftar', 'repeater', [
            ['pertanyaan' => 'Siapa yang berhak mendaftarkan institusi?', 'jawaban' => 'Pendaftaran dapat dilakukan oleh perwakilan resmi (PIC) yang ditunjuk oleh rektorat atau pimpinan perguruan tinggi dengan melampirkan Surat Tugas resmi.'],
            ['pertanyaan' => 'Berapa lama proses validasi berlangsung?', 'jawaban' => 'Proses validasi berlangsung selama 15 hari kerja setelah pendaftaran diterima dan dokumen dinyatakan lengkap.'],
            ['pertanyaan' => 'Apakah sistem ini berbayar?', 'jawaban' => 'Tidak, sistem Patriot Metric sepenuhnya gratis dan terbuka untuk seluruh perguruan tinggi di Indonesia.'],
        ], 2);
    }

    /**
     * Seed Pengumuman page content.
     */
    private function seedPengumumanPage(): void
    {
        $page = 'pengumuman';

        // Hero Section
        $this->createContent($page, 'hero', 'judul', 'text', 'Pengumuman', 1);
        $this->createContent($page, 'hero', 'deskripsi', 'text', 'Informasi terbaru seputar Patriot Metric.', 2);

        // Artikel Section
        $this->createContent($page, 'artikel', 'daftar', 'repeater', [
            ['tanggal' => '2025-08-01', 'judul' => 'Cara Mendaftarkan Institusi Anda di Portal Patriot Metric', 'excerpt' => 'Panduan lengkap langkah-langkah pendaftaran institusi di portal Patriot Metric. Mulai dari pembuatan akun, pengisian data institusi, hingga penunjukan PIC yang bertanggung jawab.', 'gambar' => ''],
            ['tanggal' => '2025-09-01', 'judul' => 'Proses Validasi & Verifikasi Data Institusi', 'excerpt' => 'Ketahui dokumen apa saja yang diperlukan untuk validasi akun institusi dan bagaimana proses verifikasi dilakukan oleh tim kami untuk memastikan keabsahan data.', 'gambar' => ''],
            ['tanggal' => '2025-09-16', 'judul' => 'Tips Pengisian Rubrik Penilaian yang Efektif', 'excerpt' => 'Strategi dan tips agar pengisian rubrik penilaian berjalan optimal. Termasuk jenis bukti pendukung yang direkomendasikan dan format yang diterima sistem.', 'gambar' => ''],
            ['tanggal' => '2025-11-01', 'judul' => 'Mekanisme Penilaian oleh Tim Reviewer', 'excerpt' => 'Bagaimana tim penilai memverifikasi dan mengevaluasi data institusi. Transparansi proses penilaian dan kriteria yang digunakan dalam evaluasi.', 'gambar' => ''],
            ['tanggal' => '2025-12-01', 'judul' => 'Metodologi Kalkulasi Skor Pemeringkatan', 'excerpt' => 'Penjelasan sistem scoring dan bobot penilaian Patriot Metric. Bagaimana skor akhir dihitung dari berbagai dimensi penilaian bela negara.', 'gambar' => ''],
            ['tanggal' => '2026-08-17', 'judul' => 'Upacara Penghargaan Nasional Patriot Metric', 'excerpt' => 'Informasi seputar acara pengumuman dan penyerahan penghargaan bagi institusi terbaik dalam implementasi nilai-nilai bela negara di lingkungan kampus.', 'gambar' => ''],
        ], 1);
    }
}
