# Bugfix Requirements Document

## Introduction

Bug pada halaman verifikasi (`resources/views/auth/verifikasi.blade.php`) Section 4 (Pratinjau), bagian "Demografi Agama Mahasiswa". Field "Kepercayaan Terhadap Tuhan YME" menggunakan `md:col-span-2` sehingga terlihat lebih besar dari field agama lainnya. Hal ini menyebabkan user mengira field tersebut adalah baris total dari jumlah agama, bukan field agama tersendiri. Desain keseluruhan juga tidak konsisten dengan halaman reviewer detail yang menggunakan layout list-style.

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN halaman verifikasi Section 4 (Pratinjau Demografi Agama) ditampilkan THEN the system menampilkan "Kepercayaan Terhadap Tuhan YME" dengan `md:col-span-2` sehingga field tersebut memiliki lebar 2 kolom — lebih besar dari field agama lainnya

1.2 WHEN halaman verifikasi Section 4 (Pratinjau Demografi Agama) ditampilkan THEN the system menggunakan grid `grid-cols-2 md:grid-cols-4` dengan card-style boxes yang di-center, tidak konsisten dengan desain reviewer detail page yang menggunakan list-style layout dengan `flex justify-between`

1.3 WHEN user melihat pratinjau demografi agama THEN the system menampilkan "Kepercayaan Terhadap Tuhan YME" seolah-olah merupakan baris total/summary karena ukurannya yang berbeda dari field agama lainnya

### Expected Behavior (Correct)

2.1 WHEN halaman verifikasi Section 4 (Pratinjau Demografi Agama) ditampilkan THEN the system SHALL menampilkan "Kepercayaan Terhadap Tuhan YME" dengan ukuran dan layout yang SAMA persis dengan field agama lainnya (Islam, Kristen, Katolik, Hindu, Buddha, Konghucu) — tanpa `col-span` tambahan

2.2 WHEN halaman verifikasi Section 4 (Pratinjau Demografi Agama) ditampilkan THEN the system SHALL menggunakan layout yang konsisten dengan reviewer detail page, yaitu 2-kolom list-style dengan `flex justify-between` rows dimana semua 7 item agama di-render secara identik

2.3 WHEN user melihat pratinjau demografi agama THEN the system SHALL menampilkan semua 7 field agama (termasuk "Kepercayaan Terhadap Tuhan YME") dengan visual yang identik sehingga jelas bahwa setiap field adalah item agama tersendiri, bukan total/summary

### Unchanged Behavior (Regression Prevention)

3.1 WHEN halaman verifikasi Section 4 ditampilkan THEN the system SHALL CONTINUE TO menampilkan semua 7 data agama (Islam, Kristen, Katolik, Hindu, Buddha, Konghucu, Kepercayaan Terhadap Tuhan YME) dengan nilai yang benar dari formData

3.2 WHEN halaman verifikasi section lain (Section 1, 2, 3) ditampilkan THEN the system SHALL CONTINUE TO menampilkan data pratinjau lainnya tanpa perubahan

3.3 WHEN halaman verifikasi Section 4 bagian selain Demografi Agama ditampilkan THEN the system SHALL CONTINUE TO menampilkan layout dan desain yang tidak berubah
