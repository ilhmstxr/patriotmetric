# Penyesuaian Tampilan Reviewer (B.13, Hasil Sistem, dan Navigasi SPA)

Rencana ini merinci perbaikan tampilan Reviewer untuk memastikan konsistensi dengan tampilan Peserta serta pengalaman pengguna yang mulus tanpa *reload*.

## Proposed Changes

### Reviewer Interface (Detail Penilaian)
Kami akan memperbarui tampilan di sisi reviewer agar mampu menerjemahkan data JSON yang sebelumnya dikirim oleh peserta.

#### [MODIFY] `resources/views/reviewer/detail.blade.php`
1. **Fungsi Parse JSON:** Menambahkan fungsi helper pada Alpine `x-data` bernama `parseAnswerJson(val)` untuk mengekstrak isi teks jawaban peserta jika formatnya adalah JSON (seperti B.13 atau Indikator dengan kalkulasi sistem).
2. **Tampilan Khusus B.13:**
   - Menambahkan kondisi `x-if` khusus untuk pertanyaan dengan kode `B.13`.
   - Jika `B.13`, maka akan dirender 4 *field* (Lokal, Regional, Nasional, Internasional) secara *read-only* lengkap dengan poin tiap skalanya, menyerupai UI di form peserta.
3. **Tampilan Kalkulasi Sistem (Indikator C dan lainnya):**
   - Jika jawaban JSON mengandung `calculated_percentage` atau `label`, maka UI akan menampilkan 2 informasi: 
     a. **Input Asli (Raw Input):** Angka mentah yang dimasukkan peserta.
     b. **Hasil Sistem:** Persentase dan label/kategori yang dihasilkan oleh rumus otomatis, disorot dengan warna hijau atau biru agar Reviewer langsung melihat hasil bersihnya.
4. **Tampilan Normal (Teks biasa):** Tetap dipertahankan untuk soal-soal biasa (Pilihan Ganda atau isian teks standar).

### Navigasi Reviewer
Kami akan memastikan transisi antar halaman reviewer menggunakan SPA (Single Page Application) Livewire.

#### [MODIFY] `resources/views/reviewer/index.blade.php`
1. **Penambahan `wire:navigate`:** Menambahkan atribut `wire:navigate` pada tag `<a>` untuk tombol "Nilai Sekarang" dan "Lihat Detail" agar saat Reviewer memilih peserta, halaman berpindah instan tanpa memuat ulang (*full page reload*).

## User Review Required
> [!IMPORTANT]
> Tampilan perhitungan sistem akan mengambil persentase/label yang *sudah di-generate dan disimpan* di database saat peserta menekan tombol otomatis (bukan menghitung ulang di sisi reviewer). Hal ini dilakukan untuk menjamin Reviewer melihat angka pasti (snapshot) yang sama persis dengan yang dilihat peserta saat mensubmit.

## Verification Plan
1. Membuka halaman Dashboard Reviewer dan memverifikasi tombol "Nilai Sekarang" tidak menyebabkan *page reload*.
2. Membuka detail peserta yang sudah mengisi B.13 untuk memastikan 4 inputan skala muncul dengan benar.
3. Membuka detail peserta untuk indikator B.18, C.5, dll., untuk memastikan label "Hasil Perhitungan Sistem" muncul.
