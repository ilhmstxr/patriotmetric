# Dokumentasi Database PatriotMetric

Berikut adalah dokumentasi skema database berdasarkan ERD dan analisis penggunaan di codebase saat ini.

## Tabel yang Terpakai Aktif

1. **`users`**
   - Menyimpan data autentikasi pengguna (Admin, Reviewer, Peserta).
   - Kolom yang aktif digunakan: `id`, `email`, `password`, `role`, `status`.

2. **`institusis`**
   - Menyimpan daftar institusi/perguruan tinggi yang berpartisipasi.
   - Kolom yang aktif digunakan: `id`, `nama_institusi`, `logo_url`, `jenis_institusi`, `domain_email`.

3. **`reviewers`**
   - Menyimpan profil spesifik untuk pengguna dengan role Reviewer.
   - Kolom yang aktif digunakan: `id`, `user_id`, `nama_lengkap`, `nip`.

4. **`pengumpulans`**
   - Tabel sentral yang menyimpan status asesmen tiap institusi per tahun periode.
   - Kolom yang aktif digunakan: `id`, `institution_id`, `nama_pic`, `jabatan_pic`, `no_hp_pic`, `tahun_periode`, `status` (ACTIVE, IN_PROGRESS, SUBMITTED, GRADED, PUBLISHED), `user_id`, `reviewer_id`, `total_skor_sistem`, `total_skor_akhir`, `skor_rekap_json`.

5. **`identitas`**
   - Menyimpan data profil/demografi dasar institusi untuk suatu periode asesmen.
   - Kolom yang aktif digunakan: `id`, `pengumpulan_id`, `jml_mahasiswa`, `jml_dosen`, `jml_tendik`, `jml_prodi`, `jml_ukm`, `jml_ormawa`, `jml_fakultas`, `visi`, `misi`, `legal_documents`, `is_verified`.

6. **`agama`**
   - Menyimpan data sebaran/demografi agama mahasiswa terkait identitas institusi.
   - Kolom yang aktif digunakan: `id`, `identitas_id`, `agama`, `jumlah`.

7. **`kategoris`**
   - Menyimpan master data kategori pertanyaan rubrik (misalnya: A. Pendidikan, B. Penelitian).
   - Kolom yang aktif digunakan: `id`, `nama_kategori`, `deskripsi`.

8. **`pertanyaans`**
   - Menyimpan master data butir pertanyaan dalam setiap kategori.
   - Kolom yang aktif digunakan: `id`, `kode_pertanyaan`, `category_id`, `teks_pertanyaan`, `kebutuhan_bukti`, `tipe` (pilihan_ganda, isian_singkat, otomatis_sistem), `keterangan`.

9. **`opsi_jawaban`**
   - Menyimpan pilihan ganda dan bobot/nilai (value) dari masing-masing opsi pertanyaan.
   - Kolom yang aktif digunakan: `id`, `pertanyaan_id`, `opsi_jawaban`, `value`, `keterangan`.

10. **`pengumpulan_jawabans`**
    - Menyimpan jawaban riil dari peserta (baik berupa ID opsi yang dipilih maupun teks/angka isian singkat) beserta tautan bukti dan hasil penilaian reviewer.
    - Kolom yang aktif digunakan: `id`, `submission_id`, `pertanyaan_id`, `jawaban_id`, `jawaban_teks`, `tautan_bukti_drive`, `skor_sistem`, `skor_validasi_reviewer`, `note_reviewer`.

11. **`submission_timelines`** *(Baru ditambahkan)*
    - Menyimpan jadwal kapan form rubrik dibuka, ditutup, dikunci manual, dan kapan hasil penilaian dipublikasikan ke peserta.
    - Kolom yang aktif digunakan: `id`, `tahun_periode`, `opens_at`, `closes_at`, `results_published_at`, `is_locked`.

---

## 🚨 Kolom / Tabel yang Tidak Terpakai (Unused)

Berdasarkan analisis *codebase*, berikut adalah entitas yang terdapat di migrasi/skema namun **tidak digunakan** di aplikasi saat ini:

### 1. Kolom `admin_note` di tabel `identitas`
- **Lokasi Migrasi**: `database/migrations/2026_03_13_075233_create_identitas_table.php`
- **Status**: **TIDAK TERPAKAI**
- **Analisis**: Kolom ini didesain untuk menyimpan "Alasan jika ditolak (Revision Loop)", namun dalam *service* form pengisian (`AssessmentService.php`), controller, dan tampilan *frontend* (termasuk Filament panel), atribut ini sama sekali tidak pernah diisi, diakses, atau ditampilkan di antarmuka. Proses validasi atau revisi identitas saat ini belum mengimplementasikan logika penolakan dengan catatan.
- **Rekomendasi**: Bisa dihapus (*drop column*) jika fitur *revision loop* profil tidak akan dikembangkan untuk menyederhanakan skema, atau dibiarkan jika sudah ada rencana spesifik untuk menggunakannya di masa mendatang.
