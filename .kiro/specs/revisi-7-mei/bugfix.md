# Bugfix Requirements Document

## Introduction

Dokumen ini mencakup 11 item revisi untuk aplikasi Patriot Metric (Laravel/Blade/Livewire/Alpine.js). Item-item ini mencakup perbaikan bug fungsional, perbaikan perilaku UI/UX, dan penambahan fitur kecil yang belum berjalan dengan benar. Semua item dianalisis menggunakan metodologi bug condition.

---

## Bug Analysis

---

### Item 1: Lock Readonly di Rubrik

#### Current Behavior (Defect)

1.1 WHEN status pengumpulan berubah menjadi SUBMITTED THEN rubrik masih dapat diedit karena lock readonly didasarkan pada waktu, bukan status pengumpulan.
1.2 WHEN status pengumpulan masih IN_PROGRESS dan waktu sudah lewat THEN rubrik menjadi readonly padahal seharusnya masih bisa diedit.

#### Expected Behavior (Correct)

2.1 IF status pengumpulan adalah SUBMITTED THEN sistem SHALL mengunci semua input field pada rubrik menjadi readonly dan menolak setiap percobaan edit.
2.2 WHILE status pengumpulan adalah IN_PROGRESS THEN sistem SHALL membiarkan semua input field pada rubrik tetap dapat diedit, tanpa memperhatikan waktu saat ini.
2.3 IF pengguna mencoba mengirim perubahan rubrik dengan status SUBMITTED melalui request langsung THEN sistem SHALL menolak request tersebut dan mengembalikan HTTP 403.

#### Unchanged Behavior (Regression Prevention)

3.1 IF rubrik sudah dalam kondisi readonly dan status tetap SUBMITTED THEN sistem SHALL menampilkan rubrik dalam mode readonly.
3.2 IF pengguna menyimpan rubrik dengan status IN_PROGRESS THEN sistem SHALL menyimpan perubahan ke database.

---

### Item 2: Bug Hasil Penilaian — Posisi Nama Peserta

#### Current Behavior (Defect)

1.1 WHEN peserta membuka halaman Hasil Penilaian THEN Disclaimer ditampilkan di posisi yang salah, tidak berada di kiri atas di dalam Banner Total Penilaian.

#### Expected Behavior (Correct)

2.1 WHEN peserta membuka halaman Hasil Penilaian THEN sistem SHALL menampilkan disclaimer rata kiri pada baris pertama di dalam Banner Total Penilaian.

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN peserta membuka halaman Hasil Penilaian THEN sistem SHALL menampilkan total nilai, kategori penilaian, dan tanggal penilaian di dalam banner.
3.2 WHEN halaman Hasil Penilaian diakses THEN sistem SHALL menampilkan data yang sesuai dengan record submission milik peserta yang sedang login.

---

### Item 3: Lazy Loading / Caching Gambar Profile

#### Current Behavior (Defect)

1.1 WHEN pengguna berpindah halaman THEN gambar profile di kanan atas di-reload ulang dari server meskipun tidak ada perubahan pada gambar tersebut.
1.2 WHEN tidak ada fitur ganti logo instansi THEN gambar profile tetap melakukan request HTTP baru setiap navigasi halaman.

#### Expected Behavior (Correct)

2.1 WHEN pengguna berpindah halaman THEN sistem SHALL memuat gambar profile dari cache browser tanpa melakukan request ulang ke server selama sesi berlangsung.
2.2 IF gambar profile sudah pernah dimuat dalam sesi yang sama THEN sistem SHALL menggunakan versi yang sudah di-cache dan tidak mengirim request HTTP baru ke server.
2.3 WHEN gambar profile di-render THEN sistem SHALL menyertakan atribut `loading="lazy"` dan header cache-control yang sesuai agar browser dapat meng-cache gambar.

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN pengguna pertama kali membuka aplikasi dalam sesi baru THEN sistem SHALL memuat gambar profile dari server.
3.2 IF gambar profile ditampilkan dari cache THEN sistem SHALL menampilkan gambar yang identik dengan yang tersimpan di server.

---

### Item 4: Header Reviewer Selalu Re-render

#### Current Behavior (Defect)

1.1 WHEN reviewer berpindah ke halaman lain THEN header di sisi reviewer selalu ter-render ulang sepenuhnya, menyebabkan flicker atau loading yang tidak perlu.

#### Expected Behavior (Correct)

2.1 WHEN reviewer berpindah ke halaman lain THEN sistem SHALL mempertahankan state header yang sudah di-render tanpa melakukan re-render penuh pada elemen header.
2.2 WHEN navigasi terjadi di sisi reviewer THEN sistem SHALL hanya memperbarui konten area utama halaman, bukan elemen header.

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN reviewer pertama kali membuka aplikasi THEN sistem SHALL merender header dengan data yang benar.
3.2 IF data di header perlu diperbarui (misalnya notifikasi baru masuk) THEN sistem SHALL memperbarui hanya elemen data tersebut tanpa me-reload seluruh header.

---

### Item 5: Poin Melebihi Nilai Maksimal 5

#### Current Behavior (Defect)

1.1 WHEN reviewer mengisi poin pada soal THEN sistem mengizinkan input nilai lebih dari 5 tanpa ada validasi.
1.2 WHEN nilai lebih dari 5 disimpan THEN sistem menyimpan nilai tersebut ke database tanpa penolakan.

#### Expected Behavior (Correct)

2.1 IF reviewer mencoba menyimpan poin dengan nilai lebih dari 5 THEN sistem SHALL menampilkan pesan error inline "Nilai maksimal adalah 5" sebelum request dikirim ke server.
2.2 IF request penyimpanan poin dengan nilai lebih dari 5 atau kurang dari 0 diterima server THEN sistem SHALL menolak request tersebut dan mengembalikan response error ke client.
2.3 IF reviewer mengisi poin dengan nilai negatif (kurang dari 0) THEN sistem SHALL menampilkan pesan error inline "Nilai minimal adalah 0" sebelum request dikirim ke server.

#### Unchanged Behavior (Regression Prevention)

3.1 IF reviewer mengisi poin dengan nilai integer antara 0 dan 5 (inklusif) THEN sistem SHALL menyimpan nilai tersebut ke database.
3.2 IF poin valid disimpan THEN sistem SHALL menghitung total penilaian sebagai jumlah semua poin valid yang tersimpan untuk submission tersebut.

---

### Item 6: Navbar Berkedip (Ngebling) Saat Scroll di Reviewer

#### Current Behavior (Defect)

1.1 WHEN reviewer melakukan scroll ke atas THEN navbar berkedip/ngebling karena logika scroll berbeda dengan sisi peserta.

#### Expected Behavior (Correct)

2.1 WHILE reviewer melakukan scroll ke atas dan posisi scroll lebih dari 100px dari atas halaman THEN sistem SHALL menampilkan navbar dengan transisi CSS maksimal 300ms tanpa efek berkedip.
2.2 WHILE reviewer melakukan scroll ke bawah lebih dari 25px dari posisi scroll sebelumnya THEN sistem SHALL menyembunyikan navbar dengan transisi CSS maksimal 300ms.
2.3 IF posisi scroll reviewer berada di 0–100px dari atas halaman THEN sistem SHALL selalu menampilkan navbar tanpa animasi tersembunyi.

#### Unchanged Behavior (Regression Prevention)

3.1 WHILE peserta melakukan scroll THEN sistem SHALL menampilkan navbar dengan perilaku yang sudah ada (threshold 25px turun, 100px atas, transisi ≤300ms).
3.2 IF reviewer tidak melakukan scroll THEN sistem SHALL menampilkan navbar dalam posisi default terlihat.

---

### Item 7: Warning Validasi Catatan Reviewer

#### Current Behavior (Defect)

1.1 WHEN reviewer mengisi catatan penilaian dengan kurang dari 20 karakter THEN sistem tidak menampilkan pesan warning atau error apapun kepada reviewer.
1.2 WHEN reviewer mencoba menyimpan catatan dengan kurang dari 20 karakter THEN sistem gagal menyimpan tanpa memberikan feedback yang jelas kepada pengguna.

#### Expected Behavior (Correct)

2.1 IF reviewer mencoba menyimpan catatan penilaian dengan panjang kurang dari 20 karakter THEN sistem SHALL menampilkan pesan warning "Catatan minimal 20 karakter" di bawah field catatan dan memblokir penyimpanan.
2.2 IF catatan penilaian diblokir karena kurang dari 20 karakter THEN sistem SHALL mempertahankan teks yang sudah diketik reviewer di dalam field tanpa menghapusnya.

#### Unchanged Behavior (Regression Prevention)

3.1 IF reviewer mengisi catatan dengan 20 karakter atau lebih THEN sistem SHALL menyimpan catatan tersebut ke database.
3.2 IF catatan valid disimpan THEN sistem SHALL menampilkan catatan tersebut di halaman detail review submission.

---

### Item 8: Hapus Tombol Simpan Draft di Reviewer

#### Current Behavior (Defect)

1.1 WHEN reviewer membuka halaman penilaian THEN tombol "Simpan Draft" ditampilkan meskipun fitur auto save sudah aktif, menyebabkan kebingungan pengguna.

#### Expected Behavior (Correct)

2.1 WHEN reviewer membuka halaman penilaian THEN sistem SHALL tidak merender tombol "Simpan Draft" di halaman tersebut.

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN reviewer mengisi penilaian THEN sistem SHALL menyimpan data secara otomatis melalui fitur auto save yang sudah ada.
3.2 WHEN reviewer meninggalkan halaman penilaian THEN sistem SHALL memastikan data terakhir tersimpan melalui auto save sebelum navigasi selesai.

---

### Item 9: Fitur Ganti Password Tidak Berfungsi

#### Current Behavior (Defect)

1.1 WHEN pengguna mencoba mengganti password melalui fitur ganti password THEN sistem gagal memproses perubahan password dan password tidak berubah.

#### Expected Behavior (Correct)

2.1 IF pengguna mengisi password lama yang cocok dengan password tersimpan, password baru minimal 8 karakter mengandung huruf besar, huruf kecil, angka, dan simbol, serta konfirmasi password baru cocok dengan password baru THEN sistem SHALL memperbarui password pengguna di database dan menampilkan pesan "Password berhasil diubah".
2.2 IF pengguna mengisi password lama yang tidak cocok dengan password tersimpan THEN sistem SHALL menolak perubahan dan menampilkan pesan error "Password lama tidak sesuai".
2.3 IF password baru tidak memenuhi syarat (kurang dari 8 karakter, atau tidak mengandung huruf besar, atau tidak mengandung huruf kecil, atau tidak mengandung angka, atau tidak mengandung simbol) THEN sistem SHALL menolak perubahan dan menampilkan pesan validasi yang menyebutkan syarat yang tidak terpenuhi.
2.4 IF konfirmasi password baru tidak cocok dengan password baru THEN sistem SHALL menolak perubahan dan menampilkan pesan error "Konfirmasi password tidak cocok".
2.5 IF password baru sama dengan password lama THEN sistem SHALL menolak perubahan dan menampilkan pesan error "Password baru tidak boleh sama dengan password lama".

#### Unchanged Behavior (Regression Prevention)

3.1 IF pengguna berhasil mengganti password THEN sistem SHALL mengizinkan login menggunakan password baru.
3.2 IF pengguna berhasil mengganti password THEN sistem SHALL menolak login menggunakan password lama.

---

### Item 10: Avatar Reviewer Menggunakan Initials 2 Huruf

#### Current Behavior (Defect)

1.1 WHEN reviewer membuka aplikasi THEN avatar/profile di sisi reviewer menggunakan asset gambar, bukan inisial nama.
1.2 WHEN asset gambar tidak tersedia atau gagal dimuat THEN avatar menampilkan broken image atau placeholder yang tidak informatif.

#### Expected Behavior (Correct)

2.1 WHEN reviewer membuka aplikasi THEN sistem SHALL menampilkan avatar berupa 2 karakter pertama dari nama reviewer dalam huruf kapital, tanpa menggunakan asset gambar.
2.2 IF nama reviewer hanya terdiri dari 1 karakter THEN sistem SHALL menampilkan 1 karakter tersebut dalam huruf kapital sebagai avatar.
2.3 IF nama reviewer kosong atau null THEN sistem SHALL menampilkan teks fallback "??" sebagai avatar.

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN reviewer melihat avatar THEN sistem SHALL menampilkan inisial yang sesuai dengan nama reviewer yang sedang login.
3.2 WHEN halaman reviewer diakses THEN sistem SHALL menampilkan informasi profil reviewer (nama, email) dengan benar.

---

### Item 11: Tombol Preview Template File di Verifikasi

#### Current Behavior (Defect)

1.1 WHEN peserta membuka halaman verifikasi dan melihat field upload file THEN tidak ada tombol atau cara untuk melihat template file yang harus diupload.
1.2 WHEN peserta tidak tahu format file yang diharapkan THEN peserta tidak dapat melihat contoh/template file yang benar.

#### Expected Behavior (Correct)

2.1 WHEN peserta membuka halaman verifikasi THEN sistem SHALL menampilkan tombol "Lihat Template" di samping setiap field upload file yang memiliki file template terdaftar.
2.2 IF peserta mengklik tombol "Lihat Template" pada sebuah field THEN sistem SHALL membuka tab browser baru dengan URL `public/templates/{nama_file_template}.pdf` yang sesuai dengan field tersebut.
2.3 IF file PDF template tidak ditemukan di path yang ditentukan THEN sistem SHALL menampilkan pesan error "Template tidak tersedia" dan tidak membuka tab baru.
2.4 WHEN field upload file tidak memiliki template terdaftar THEN sistem SHALL tidak menampilkan tombol "Lihat Template" pada field tersebut.

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN peserta mengupload file pada field verifikasi THEN sistem SHALL memproses upload file sesuai dengan validasi tipe dan ukuran yang sudah ada.
3.2 WHEN halaman verifikasi diakses THEN sistem SHALL menampilkan semua field upload file yang diperlukan beserta status upload masing-masing.
3.3 IF tombol "Lihat Template" diklik THEN sistem SHALL membiarkan field upload file tetap berfungsi normal tanpa gangguan pada state form.
