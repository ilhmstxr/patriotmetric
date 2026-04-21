<x-layouts.dashboard>
    <x-slot:title>Hasil Penilaian</x-slot:title>

    {{--
        ✏️ DATA KATEGORI: Edit di blok x-data di bawah ini.
        Setiap kategori: { name, score, max, color ('green'|'orange'), items: [...] }
        Setiap item: { no, title, score, max, jawaban, tautan, catatan }
    --}}
    <div x-data="{
        openCategories: { 0: true, 1: true, 2: true },
        toggleCategory(idx) { this.openCategories[idx] = !this.openCategories[idx]; },
        categories: [
            {
                name: 'KEBIJAKAN (01)',
                score: 20,
                max: 25,
                color: 'green',
                items: [
                    {
                        no: 1,
                        title: 'Kebijakan/Implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma',
                        score: 8,
                        max: 10,
                        jawaban: 'Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma',
                        tautan: 'https://drive.google.com/file/d/1234567890/view',
                        catatan: 'Dokumen kebijakan lengkap dan bukti implementasi cukup baik. Perlu ditingkatkan dokumentasi kegiatan penunjang.'
                    },
                    {
                        no: 2,
                        title: 'Kebijakan pencegahan dan penanganan kekerasan',
                        score: 5,
                        max: 5,
                        jawaban: 'Lengkap (beserta tindak lanjut laporan dan/atau pendampingan, perlindungan, pemulihan korban dan saksi)',
                        tautan: 'https://drive.google.com/file/d/0987654321/view',
                        catatan: 'Sangat lengkap. Satgas PPKS aktif dan kanal aduan berfungsi dengan baik.'
                    },
                    {
                        no: 3,
                        title: 'Kebijakan bagi sivitas akademika untuk bangga menggunakan produk lokal dalam pelaksanaan pembelajaran',
                        score: 7,
                        max: 10,
                        jawaban: 'Kebijakan penggunaan batik diterapkan secara luas, tetapi belum sepenuhnya didukung oleh seluruh sivitas akademik',
                        tautan: 'https://drive.google.com/file/d/1122334455/view',
                        catatan: 'Implementasi batik day sudah berjalan. Perlu peningkatan awareness di beberapa fakultas.'
                    }
                ]
            },
            {
                name: 'KELEMBAGAAN (02)',
                score: 45,
                max: 50,
                color: 'green',
                items: [
                    {
                        no: 1,
                        title: 'Unit Kerja yang berfokus pada pengembangan karakter bela negara',
                        score: 25,
                        max: 30,
                        jawaban: 'Ada unit kerja, program kerja, kegiatan implementasi, evaluasi program',
                        tautan: 'https://drive.google.com/file/d/5544332211/view',
                        catatan: 'Unit kerja sudah established. Evaluasi program cukup komprehensif namun perlu ditambahkan perencanaan jangka panjang.'
                    },
                    {
                        no: 2,
                        title: 'Jumlah kelompok riset berkarakter bela negara',
                        score: 20,
                        max: 20,
                        jawaban: '12 kelompok riset',
                        tautan: 'https://drive.google.com/file/d/6677889900/view',
                        catatan: 'Jumlah kelompok riset sangat baik dan terverifikasi. Publikasi juga memadai.'
                    }
                ]
            },
            {
                name: 'PATRIOTISME MAHASISWA (03)',
                score: 20,
                max: 25,
                color: 'orange',
                items: [
                    {
                        no: 1,
                        title: 'Mahasiswa aktif sebagai anggota komponen cadangan (KOMCAD)',
                        score: 10,
                        max: 15,
                        jawaban: '4 mahasiswa aktif',
                        tautan: 'https://drive.google.com/file/d/9988776655/view',
                        catatan: 'Sertifikat dan kartu anggota KOMCAD valid. Dapat ditingkatkan dengan sosialisasi lebih intensif.'
                    },
                    {
                        no: 2,
                        title: 'Jumlah Unit Kegiatan Mahasiswa (UKM)',
                        score: 10,
                        max: 10,
                        jawaban: 'Terdapat > 20 UKM',
                        tautan: 'https://drive.google.com/file/d/4433221100/view',
                        catatan: 'Sangat baik. Total 28 UKM aktif dengan SK Rektor dan laporan kegiatan lengkap.'
                    }
                ]
            }
        ]
    }" class="bg-[#f5f5f5] min-h-full py-5 px-4 md:px-8">

        <div class="max-w-[860px] mx-auto space-y-5">

            {{-- ✏️ Banner Hijau Total Penilaian → components/dashboard/hasil/banner.blade.php --}}
            <x-dashboard.hasil.banner />

            {{-- ✏️ Card Status Penilaian → components/dashboard/hasil/status.blade.php --}}
            <x-dashboard.hasil.status />

            {{-- ✏️ Accordion Rincian Poin → components/dashboard/hasil/kategori.blade.php --}}
            <x-dashboard.hasil.kategori />

        </div>
    </div>
</x-layouts.dashboard>
