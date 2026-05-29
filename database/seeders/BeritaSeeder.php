<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        Berita::updateOrCreate(
            ['slug' => 'mendiktisaintek-resmikan-menara-wimaya-upn-veteran-jawa-timur'],
            [
                'judul' => 'Mendiktisaintek Resmikan Menara Wimaya UPN "Veteran" Jawa Timur',
                'excerpt' => 'Menteri Pendidikan Tinggi, Sains, dan Teknologi (Mendiktisaintek) Brian Yuliarto menekankan pentingnya pemanfaatan bersama sumber daya pendidikan tinggi, khususnya laboratorium dan peralatan riset, agar utilisasinya semakin optimal.',
                'konten' => $this->getKontenBerita1(),
                'gambar' => 'assets/berita/1/2-cropped_11zon.webp',
                'tanggal' => '2025-01-27',
                'is_published' => true,
            ]
        );
    }

    private function getKontenBerita1(): string
    {
        return <<<'HTML'
<p>Menteri Pendidikan Tinggi, Sains, dan Teknologi (Mendiktisaintek) Brian Yuliarto menekankan pentingnya pemanfaatan bersama sumber daya pendidikan tinggi, khususnya laboratorium dan peralatan riset, agar utilisasinya semakin optimal. Hal tersebut disampaikan saat meresmikan Menara Wimaya (Twin Tower) Universitas Pembangunan Nasional (UPN) "Veteran" Jawa Timur, Senin (27/1).</p>
<p>Mendiktisaintek mendorong penguatan kolaborasi antarkampus, baik perguruan tinggi negeri maupun swasta. Menurutnya, praktik berbagi fasilitas dan sumber daya telah menjadi hal yang lazim di berbagai negara dan perlu terus diperkuat di Indonesia. "Gedungnya memang ada di sini, tetapi fasilitas dan laboratoriumnya tentu bisa dimanfaatkan juga oleh kampus-kampus lain. Riset dan pengajaran bisa kita lakukan bersama-sama," ujar Menteri Brian.</p>
<img src="/assets/berita/1/4-cropped_11zon.webp" alt="Mendiktisaintek meresmikan Menara Wimaya">
<p>Peresmian Menara Wimaya merupakan bagian dari kunjungan kerja Mendiktisaintek ke UPN "Veteran" Jawa Timur sekaligus bentuk apresiasi atas komitmen kampus dalam membangun fasilitas pendidikan tinggi secara mandiri dan berkelanjutan. Ia menilai pembangunan gedung tersebut sebagai capaian penting yang mencerminkan semangat kolegialitas dan kebersamaan antarkampus.</p>
<p>Selain infrastruktur, Mendiktisaintek juga mendorong pengembangan bahan ajar daring berbasis video yang dapat diakses secara luas sebagai implementasi praktik perguruan tinggi kelas dunia. "Dosen-dosen terbaik bisa membagikan ilmunya ke seluas-luasnya masyarakat, dan kampus-kampus di berbagai daerah dapat memanfaatkannya," katanya.</p>
<p>Dalam kesempatan yang sama, Menteri Brian menegaskan bahwa keunggulan perguruan tinggi tidak hanya ditentukan oleh kemegahan infrastruktur, tetapi juga oleh lingkungan kampus yang tertata, bersih, dan memberikan layanan prima. Lingkungan tersebut dinilai penting dalam membentuk karakter, etos kerja, dan integritas mahasiswa sebagai calon sumber daya manusia unggul.</p>
<img src="/assets/berita/1/3-cropped_11zon.webp" alt="Rektor UPN Veteran Jawa Timur">
<p>Rektor UPN "Veteran" Jawa Timur, Prof. Dr. Ir. Akhmad Fauzi, MMT.,IPU menyampaikan bahwa pembangunan Menara Wimaya merupakan bagian dari transformasi berkelanjutan kampus bela negara dalam memperkuat atmosfer akademik. "Sejalan dengan berkembangnya UPN 'Veteran' Jawa Timur, kami merasa perlu membangun fasilitas pendidikan yang mendukung integrasi akademik, riset, dan inovasi agar tercipta lingkungan belajar yang semakin kuat dan berdampak," ujar Rektor.</p>
<p>Menara Wimaya dibangun secara multi-years selama tiga tahun oleh PT PP, dengan peletakan batu pertama pada 16 Juli 2022 dan rampung pada 2025. Gedung kembar seluas sekitar 29.000 meter persegi dan terdiri atas 13 lantai ini didanai melalui Penerimaan Negara Bukan Pajak (PNBP), serta dirancang sebagai simbol konektivitas dan kolaborasi antarbidang keilmuan melalui sky bridge yang menghubungkan kedua menara.</p>
<p>Selain peresmian Menara Wimaya, UPN "Veteran" Jawa Timur juga meluncurkan dua program strategis, yakni Patriot Metric University Ranking dan U-Bridge Program. Kedua program tersebut diposisikan sebagai penguat transformasi institusi, sejalan dengan pembangunan infrastruktur utama kampus dalam mendukung kebijakan Diktisaintek Berdampak dan upaya mencetak lulusan berkarakter, berdaya saing, serta berkontribusi nyata bagi bangsa.</p>
<img src="/assets/berita/1/1-cropped_11zon.webp" alt="Peluncuran Patriot Metric">
HTML;
    }
}
