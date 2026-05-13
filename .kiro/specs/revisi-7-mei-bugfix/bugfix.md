# Bugfix Requirements Document

## Introduction

Dokumen ini mencakup kumpulan bug dan perbaikan dari daftar revisi 7 Mei pada sistem penilaian/assessment Patriot Metric. Sistem ini memiliki dua sisi utama: sisi peserta (pengumpul data) dan sisi reviewer (penilai). Bug-bug ini mencakup berbagai area: logika status pengumpulan, tampilan UI, performa (lazy loading/caching), validasi input, dan manajemen akun. Perbaikan ini bersifat kritis karena mempengaruhi alur penilaian, pengalaman pengguna, dan integritas data.

---

## Bug Analysis

---

### Bug 1: Lock Readonly Rubrik Berdasarkan Waktu, Bukan Status

#### Current Behavior (Defect)

1.1 WHEN status pengumpulan adalah `IN_PROGRESS` THEN sistem mengunci rubrik menjadi readonly berdasarkan kondisi waktu (bukan status)
1.2 WHEN status pengumpulan berubah menjadi `SUBMITTED` THEN sistem tidak mengubah state readonly rubrik karena logika penguncian tidak memantau perubahan status

#### Expected Behavior (Correct)

2.1 WHEN status pengumpulan adalah `IN_PROGRESS` THEN sistem SHALL membiarkan rubrik dapat diedit (tidak readonly)
2.2 WHEN status pengumpulan berubah menjadi `SUBMITTED` THEN sistem SHALL mengunci rubrik menjadi readonly secara otomatis berdasarkan perubahan status tersebut

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN status pengumpulan adalah `GRADED` atau `PUBLISHED` THEN sistem SHALL CONTINUE TO menampilkan rubrik dalam kondisi readonly
3.2 WHEN status pengumpulan adalah `ACTIVE` THEN sistem SHALL CONTINUE TO menampilkan rubrik sesuai logika akses yang berlaku

---

### Bug 2: Posisi Disclaimer di Halaman Hasil Penilaian

#### Current Behavior (Defect)

1.1 WHEN pengguna membuka halaman Hasil Penilaian THEN sistem menampilkan disclaimer di posisi yang salah (bukan di dalam Banner Total Penilaian)

#### Expected Behavior (Correct)

2.1 WHEN pengguna membuka halaman Hasil Penilaian THEN sistem SHALL menampilkan disclaimer di posisi kiri atas di dalam komponen Banner Total Penilaian

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN pengguna membuka halaman Hasil Penilaian THEN sistem SHALL CONTINUE TO menampilkan konten disclaimer yang sama (teks tidak berubah)
3.2 WHEN pengguna membuka halaman Hasil Penilaian THEN sistem SHALL CONTINUE TO menampilkan Banner Total Penilaian beserta nilai totalnya

---

### Bug 3: Gambar Profile Kanan Atas Reload Setiap Ganti Halaman

#### Current Behavior (Defect)

1.1 WHEN pengguna berpindah halaman THEN sistem me-reload ulang gambar profile di pojok kanan atas meskipun tidak ada perubahan data profile
1.2 WHEN tidak ada fitur ganti logo instansi THEN sistem tetap melakukan request ulang ke server untuk mengambil gambar profile setiap navigasi

#### Expected Behavior (Correct)

2.1 WHEN pengguna berpindah halaman THEN sistem SHALL mempertahankan gambar profile yang sudah dimuat sebelumnya tanpa melakukan request ulang (lazy loading / caching)
2.2 WHEN gambar profile sudah pernah dimuat dalam sesi yang sama THEN sistem SHALL menggunakan cache gambar tersebut tanpa reload

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN pengguna pertama kali membuka aplikasi THEN sistem SHALL CONTINUE TO memuat dan menampilkan gambar profile dengan benar
3.2 WHEN sesi pengguna berakhir dan login ulang THEN sistem SHALL CONTINUE TO memuat gambar profile yang sesuai

---

### Bug 4: Header Reviewer Selalu Reload Saat Ganti Halaman

#### Current Behavior (Defect)

1.1 WHEN reviewer berpindah ke halaman lain THEN sistem me-render ulang seluruh komponen header reviewer setiap kali navigasi terjadi

#### Expected Behavior (Correct)

2.1 WHEN reviewer berpindah ke halaman lain THEN sistem SHALL mempertahankan state header reviewer tanpa melakukan full reload komponen
2.2 WHEN navigasi antar halaman terjadi di sisi reviewer THEN sistem SHALL hanya me-render ulang konten halaman, bukan header

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN reviewer pertama kali membuka halaman THEN sistem SHALL CONTINUE TO menampilkan header dengan data yang benar
3.2 WHEN data reviewer berubah (misalnya logout) THEN sistem SHALL CONTINUE TO memperbarui header sesuai perubahan tersebut

---

### Bug 5: Poin Guide Soal Melebihi Nilai Maksimal 5

#### Current Behavior (Defect)

1.1 WHEN reviewer melihat guide di tiap soal pada halaman penilaian THEN sistem menampilkan nilai poin yang melebihi batas maksimal 5
1.2 WHEN data poin guide diambil dari sumber data THEN sistem tidak memvalidasi bahwa nilai poin tidak boleh melebihi 5

#### Expected Behavior (Correct)

2.1 WHEN sistem menampilkan poin guide di tiap soal THEN sistem SHALL memastikan nilai yang ditampilkan tidak melebihi 5
2.2 WHEN nilai poin guide melebihi 5 THEN sistem SHALL menampilkan nilai maksimal 5 atau memberikan indikasi error data

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN nilai poin guide adalah antara 0 dan 5 THEN sistem SHALL CONTINUE TO menampilkan nilai tersebut dengan benar
3.2 WHEN reviewer mengisi penilaian THEN sistem SHALL CONTINUE TO memproses skor sesuai logika yang ada

---

### Bug 6: Navbar Berkedip (Ngebling) Saat Scroll ke Atas di Sisi Reviewer

#### Current Behavior (Defect)

1.1 WHEN reviewer melakukan scroll ke atas pada halaman THEN navbar berkedip/flicker (ngebling) karena logika show/hide navbar berbeda dengan implementasi di sisi peserta
1.2 WHEN logika scroll navbar reviewer tidak konsisten dengan sisi peserta THEN terjadi efek visual yang mengganggu

#### Expected Behavior (Correct)

2.1 WHEN reviewer melakukan scroll ke atas THEN navbar SHALL ditampilkan dengan smooth tanpa efek berkedip, menggunakan logika yang sama dengan sisi peserta
2.2 WHEN reviewer melakukan scroll ke bawah THEN navbar SHALL disembunyikan dengan smooth sesuai logika yang sama dengan sisi peserta

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN pengguna di sisi peserta melakukan scroll THEN sistem SHALL CONTINUE TO menampilkan/menyembunyikan navbar sesuai logika yang sudah berjalan dengan benar
3.2 WHEN halaman pertama kali dimuat THEN sistem SHALL CONTINUE TO menampilkan navbar dalam kondisi visible

---

### Bug 7: Tidak Ada Warning Validasi Minimal 20 Karakter di Catatan Reviewer

#### Current Behavior (Defect)

1.1 WHEN reviewer mengisi catatan penilaian dengan kurang dari 20 karakter THEN sistem tidak menampilkan pesan warning apapun kepada reviewer
1.2 WHEN reviewer mencoba menyimpan catatan dengan input kurang dari 20 karakter THEN sistem gagal menyimpan tanpa memberikan feedback visual yang jelas

#### Expected Behavior (Correct)

2.1 WHEN reviewer mengisi catatan penilaian dengan kurang dari 20 karakter THEN sistem SHALL menampilkan pesan warning yang menginformasikan bahwa minimal 20 karakter diperlukan
2.2 WHEN jumlah karakter catatan mencapai atau melebihi 20 karakter THEN sistem SHALL menyembunyikan pesan warning tersebut

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN reviewer mengisi catatan dengan 20 karakter atau lebih THEN sistem SHALL CONTINUE TO menerima dan menyimpan catatan tersebut
3.2 WHEN validasi karakter sudah terpenuhi THEN sistem SHALL CONTINUE TO memproses penyimpanan catatan sesuai alur yang ada

---

### Bug 8: Tombol Simpan Draft di Sisi Reviewer Masih Ada

#### Current Behavior (Defect)

1.1 WHEN reviewer membuka halaman penilaian THEN sistem masih menampilkan tombol "Simpan Draft" meskipun fitur auto save sudah tersedia
1.2 WHEN tombol simpan draft masih ada THEN antarmuka reviewer menjadi redundan dan membingungkan

#### Expected Behavior (Correct)

2.1 WHEN reviewer membuka halaman penilaian THEN sistem SHALL tidak menampilkan tombol "Simpan Draft" karena fitur auto save sudah menangani penyimpanan otomatis
2.2 WHEN reviewer melakukan perubahan pada penilaian THEN sistem SHALL CONTINUE TO menyimpan perubahan secara otomatis melalui fitur auto save

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN reviewer melakukan perubahan pada penilaian THEN sistem SHALL CONTINUE TO menyimpan data secara otomatis (auto save tetap berfungsi)
3.2 WHEN reviewer mengisi form penilaian THEN sistem SHALL CONTINUE TO memproses dan menyimpan data penilaian dengan benar

---

### Bug 9: Fitur Ganti Password Tidak Berfungsi

#### Current Behavior (Defect)

1.1 WHEN pengguna mengakses fitur ganti password dan mengisi form dengan benar THEN sistem tidak berhasil mengubah password (fitur tidak berfungsi)
1.2 WHEN pengguna submit form ganti password THEN sistem tidak memberikan feedback sukses maupun error yang sesuai

#### Expected Behavior (Correct)

2.1 WHEN pengguna mengisi form ganti password dengan password lama yang benar dan password baru yang valid THEN sistem SHALL berhasil mengubah password dan menampilkan notifikasi sukses
2.2 WHEN pengguna mengisi password lama yang salah THEN sistem SHALL menampilkan pesan error yang sesuai tanpa mengubah password

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN pengguna login dengan password lama setelah berhasil ganti password THEN sistem SHALL CONTINUE TO menolak login dengan password lama
3.2 WHEN pengguna login dengan password baru setelah berhasil ganti password THEN sistem SHALL CONTINUE TO mengizinkan login dengan password baru

---

### Bug 10: Avatar Reviewer Menggunakan Asset Gambar, Bukan Inisial 2 Huruf

#### Current Behavior (Defect)

1.1 WHEN reviewer login dan melihat profile di halaman reviewer THEN sistem menampilkan avatar menggunakan asset gambar, bukan inisial nama
1.2 WHEN asset gambar tidak tersedia atau gagal dimuat THEN avatar tidak menampilkan fallback yang sesuai

#### Expected Behavior (Correct)

2.1 WHEN reviewer login dan melihat profile THEN sistem SHALL menampilkan avatar berupa 2 huruf pertama dari nama reviewer (inisial), bukan asset gambar
2.2 WHEN nama reviewer tersedia THEN sistem SHALL mengambil 2 karakter pertama nama untuk ditampilkan sebagai avatar teks

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN reviewer melihat halaman profil THEN sistem SHALL CONTINUE TO menampilkan informasi nama reviewer dengan benar
3.2 WHEN data reviewer diperbarui THEN sistem SHALL CONTINUE TO memperbarui tampilan sesuai data terbaru

---

### Bug 11: Tidak Ada Tombol Preview Template File di Halaman Verifikasi

#### Current Behavior (Defect)

1.1 WHEN pengguna berada di halaman verifikasi dan melihat daftar file yang harus diupload THEN sistem tidak menyediakan tombol untuk melihat preview template file yang harus diisi
1.2 WHEN pengguna tidak tahu format file yang harus diupload THEN tidak ada cara untuk melihat contoh/template file tersebut

#### Expected Behavior (Correct)

2.1 WHEN pengguna berada di halaman verifikasi dan melihat item file yang harus diupload THEN sistem SHALL menampilkan tombol preview di setiap item file tersebut
2.2 WHEN pengguna mengklik tombol preview THEN sistem SHALL membuka file PDF template yang tersedia di folder publik (`/storage/templates/`) pada tab browser baru
2.3 WHEN file PDF template tidak ditemukan di path yang ditentukan THEN sistem SHALL menampilkan pesan error yang informatif

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN pengguna mengupload file di halaman verifikasi THEN sistem SHALL CONTINUE TO memproses upload file sesuai alur yang ada
3.2 WHEN pengguna melihat daftar file yang sudah diupload THEN sistem SHALL CONTINUE TO menampilkan status upload dengan benar

---

### Bug 12: Status Awal User Saat Create Tidak Sesuai

#### Current Behavior (Defect)

1.1 WHEN admin membuat user baru melalui sistem THEN status awal user yang ditetapkan tidak sesuai dengan ketentuan terbaru
1.2 WHEN user baru dibuat THEN status default yang diset tidak mencerminkan alur onboarding yang benar

#### Expected Behavior (Correct)

2.1 WHEN admin membuat user baru THEN sistem SHALL menetapkan status awal user sesuai dengan ketentuan yang telah diperbarui
2.2 WHEN user baru pertama kali login THEN sistem SHALL menampilkan alur yang sesuai dengan status awal yang benar

#### Unchanged Behavior (Regression Prevention)

3.1 WHEN user yang sudah ada (existing) menggunakan sistem THEN sistem SHALL CONTINUE TO mempertahankan status mereka tanpa perubahan
3.2 WHEN admin mengelola user yang sudah ada THEN sistem SHALL CONTINUE TO memproses perubahan status sesuai alur yang berlaku

---

## Bug Condition Summary

### Pseudocode Bug Conditions

```pascal
// Bug 1: Lock Readonly Rubrik
FUNCTION isBugCondition_1(pengumpulan)
  INPUT: pengumpulan of type Pengumpulan
  OUTPUT: boolean
  RETURN pengumpulan.status = 'SUBMITTED' AND rubrik.isReadonly = false
END FUNCTION

FOR ALL pengumpulan WHERE isBugCondition_1(pengumpulan) DO
  result ← getRubrikState'(pengumpulan)
  ASSERT result.isReadonly = true
END FOR

FOR ALL pengumpulan WHERE NOT isBugCondition_1(pengumpulan) DO
  ASSERT getRubrikState(pengumpulan) = getRubrikState'(pengumpulan)
END FOR
```

```pascal
// Bug 5: Poin Melebihi Maksimal 5
FUNCTION isBugCondition_5(poin)
  INPUT: poin of type number
  OUTPUT: boolean
  RETURN poin > 5
END FUNCTION

FOR ALL poin WHERE isBugCondition_5(poin) DO
  result ← displayPoin'(poin)
  ASSERT result <= 5
END FOR

FOR ALL poin WHERE NOT isBugCondition_5(poin) DO
  ASSERT displayPoin(poin) = displayPoin'(poin)
END FOR
```

```pascal
// Bug 7: Warning Validasi Catatan Reviewer
FUNCTION isBugCondition_7(catatan)
  INPUT: catatan of type string
  OUTPUT: boolean
  RETURN LENGTH(catatan) < 20
END FUNCTION

FOR ALL catatan WHERE isBugCondition_7(catatan) DO
  result ← validateCatatan'(catatan)
  ASSERT result.showWarning = true AND result.warningMessage != null
END FOR

FOR ALL catatan WHERE NOT isBugCondition_7(catatan) DO
  ASSERT validateCatatan(catatan) = validateCatatan'(catatan)
END FOR
```

```pascal
// Bug 9: Ganti Password
FUNCTION isBugCondition_9(passwordForm)
  INPUT: passwordForm of type PasswordChangeForm
  OUTPUT: boolean
  RETURN passwordForm.oldPassword = currentUser.password AND passwordForm.newPassword != null
END FUNCTION

FOR ALL passwordForm WHERE isBugCondition_9(passwordForm) DO
  result ← changePassword'(passwordForm)
  ASSERT result.success = true AND currentUser.password = passwordForm.newPassword
END FOR

FOR ALL passwordForm WHERE NOT isBugCondition_9(passwordForm) DO
  ASSERT changePassword(passwordForm) = changePassword'(passwordForm)
END FOR
```
