# 📝 Final Checklist & Testing Patriot Metric

Daftar ini merangkum hal-masing yang perlu dicek ulang dan dites berdasarkan temuan dari riwayat pengujian browser sebelumnya untuk memastikan sistem stabil.

## 1. Perbaikan & Verifikasi API (Sanctum)
- [ ] **Middleware Check**: Pastikan semua route di `api.php` sudah terbungkus `auth:sanctum` (Terutama group `assessment/peserta` yang sebelumnya 404).
- [ ] **Rubrik Data Loading**: Tes akun `peserta@test.com` untuk memastikan pertanyaan rubrik muncul (sebelumnya gagal karena token tidak terdeteksi di backend).
- [ ] **Status IN_PROGRESS**: Verifikasi di database bahwa `peserta@test.com` sudah memiliki record di tabel `pengumpulans` dengan status `IN_PROGRESS`.

## 2. Pengujian Login & Role
- [ ] **Reviewer Login**: Coba login `reviewer@admin.com` dengan password `reviewer` (sebelumnya gagal karena salah input password `password`).
- [ ] **Role Isolation**: 
    - [ ] Pastikan Reviewer **tidak bisa** buka `/dashboard` (harus tertendang balik ke `/reviewer`).
    - [ ] Pastikan Peserta **tidak bisa** buka `/reviewer`.

## 3. Pengujian Guard & Redirection
- [ ] **New User Redirect**: 
    - [ ] Register user baru, pastikan masuk ke `/verifikasi`.
    - [ ] Coba ketik manual `/dashboard` saat status masih `ACTIVE`, pastikan tertendang balik ke `/verifikasi` (Cek sinkronisasi `localStorage` vs `header.blade.php`).
- [ ] **Auth Page Guard**: Pastikan user yang sudah login tidak bisa buka lagi halaman `/masuk` atau `/daftar`.

## 4. Pembersihan State (Frontend)
- [ ] **Header Persistence**: Pastikan email `reviewer@admin.com` tidak nyangkut lagi di header saat login sebagai peserta (Cek apakah `sessionStorage.clear()` di `masuk.blade.php` sudah bekerja).
- [ ] **Logout Deep Clean**: Pastikan setelah klik "Keluar", `auth_token`, `auth_user`, dan `rubrik_data_cache` benar-benar hilang dari storage browser.

## 5. UI & Content
- [ ] **Custom 404**: Cek halaman 404 apakah sudah muncul dengan Header & Footer aplikasi yang benar.
- [ ] **Font Size Verifikasi**: Cek visual di halaman `/verifikasi` untuk memastikan label sudah 15px dan heading 22px.
- [ ] **Agama Baru**: Pastikan opsi "Kepercayaan Terhadap Tuhan Yang Maha Esa" muncul di form verifikasi.

## 6. Integrasi File
- [ ] **WebP Conversion**: Tes upload logo di halaman verifikasi dan cek apakah file yang tersimpan di storage sudah berformat `.webp`.
