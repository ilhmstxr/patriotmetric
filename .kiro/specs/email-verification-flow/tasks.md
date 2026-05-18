# Implementation Plan: Email Verification Flow

## Overview

Implementasi fitur verifikasi email setelah registrasi peserta. Alur: peserta daftar → assessment dibuat dengan status UNVERIFIED → email konfirmasi dikirim → peserta klik link → assessment menjadi ACTIVE. Mencakup database migration, service layer, controller, mailable, email template, halaman frontend baru, dan update pada alur registrasi/login yang sudah ada.

## Tasks

- [ ] 1. Database migration dan model
  - [ ] 1.1 Buat migration untuk tabel `email_verification_tokens`
    - Buat file migration baru dengan kolom: `id`, `user_id` (foreign key ke users), `token` (string 64, unique), `expires_at` (timestamp), `used_at` (timestamp nullable), `timestamps`
    - Tambahkan composite index pada `(token, expires_at)` dan index pada `user_id`
    - _Requirements: 3.2, 3.3, 4.6_

  - [ ] 1.2 Buat migration untuk menambah status UNVERIFIED pada enum `assessments.status`
    - Alter kolom `status` pada tabel `assessments` untuk menambahkan value `UNVERIFIED` di awal enum
    - Pastikan default tetap `ACTIVE` agar data existing tidak terpengaruh
    - _Requirements: 1.1_

  - [ ] 1.3 Buat Eloquent model `EmailVerificationToken`
    - Buat file `app/Models/EmailVerificationToken.php`
    - Definisikan `$fillable`, `casts()` untuk `expires_at` dan `used_at` sebagai datetime
    - Tambahkan relasi `user()` (BelongsTo)
    - Tambahkan method helper: `isExpired()`, `isUsed()`, `isValid()`
    - _Requirements: 3.2, 3.3, 4.1, 4.6_

- [ ] 2. Service layer: EmailVerificationService
  - [ ] 2.1 Buat `app/Services/EmailVerificationService.php`
    - Implementasi method `generateAndSendVerification(User $user, string $institutionName): void`
      - Generate token 64 karakter dengan `Str::random(64)`
      - Simpan ke tabel `email_verification_tokens` dengan `expires_at = now() + 60 minutes`
      - Dispatch `EmailVerificationMail` via Mail facade
    - Implementasi method `verifyToken(string $token): array`
      - Cari token di database, gunakan `lockForUpdate()` dalam transaction
      - Return `['success' => bool, 'user_id' => ?int, 'reason' => ?string]`
      - Handle kasus: valid, expired, invalid/used
    - Implementasi method `resendVerification(User $user): void`
      - Invalidate token lama (set `used_at = now()` untuk semua token user)
      - Generate dan kirim token baru
    - Implementasi method `invalidateExistingTokens(int $userId): void`
    - _Requirements: 3.1, 3.2, 3.3, 4.1, 4.2, 4.6_

  - [ ]* 2.2 Tulis unit test untuk EmailVerificationService
    - Test token generation (panjang 64 karakter)
    - Test expiration calculation (60 menit dari sekarang)
    - Test validasi token: valid, expired, used, invalid
    - Test invalidasi token lama saat resend
    - Test race condition handling dengan lockForUpdate
    - _Requirements: 3.2, 3.3, 4.1, 4.2, 4.5, 4.6_

- [ ] 3. Mailable dan email template
  - [ ] 3.1 Buat Mailable class `app/Mail/EmailVerificationMail.php`
    - Implement `ShouldQueue` interface untuk async sending
    - Constructor menerima `User $user`, `string $verificationUrl`, `string $institutionName`
    - Definisikan `envelope()` dengan subject "Verifikasi Email - Patriot Metric"
    - Definisikan `content()` yang merujuk ke Blade view
    - _Requirements: 3.1, 3.4_

  - [ ] 3.2 Buat Blade email template `resources/views/emails/verification.blade.php`
    - Tampilkan nama institusi, pesan instruksi, dan tombol CTA "Verifikasi Email"
    - Tombol CTA berisi link verifikasi (`$verificationUrl`)
    - Tambahkan fallback text link untuk email client yang tidak support HTML button
    - Tambahkan informasi bahwa link berlaku 60 menit
    - _Requirements: 3.2, 3.4_

- [ ] 4. Controller: EmailVerificationController
  - [ ] 4.1 Buat `app/Http/Controllers/EmailVerificationController.php`
    - Implementasi method `verify(string $token): RedirectResponse`
      - Panggil `EmailVerificationService::verifyToken()`
      - Jika sukses: update assessment status UNVERIFIED → ACTIVE, set `email_verified_at` pada user, redirect ke `/masuk?verified=1`
      - Jika expired: redirect ke `/verifikasi-gagal?reason=expired`
      - Jika invalid/used: redirect ke `/verifikasi-gagal?reason=invalid`
    - Implementasi method `resend(Request $request): JsonResponse`
      - Protected route (auth:sanctum)
      - Panggil `EmailVerificationService::resendVerification()`
      - Return JSON response sukses/gagal
      - Tambahkan throttle middleware (max 3 per 5 menit)
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 2.5_

  - [ ]* 4.2 Tulis unit test untuk EmailVerificationController
    - Test verify dengan token valid → redirect ke /masuk?verified=1
    - Test verify dengan token expired → redirect ke /verifikasi-gagal?reason=expired
    - Test verify dengan token invalid → redirect ke /verifikasi-gagal?reason=invalid
    - Test resend dengan user authenticated → sukses
    - Test resend throttle (max 3 per 5 menit)
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

- [ ] 5. Checkpoint - Pastikan semua test pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 6. Update AuthController dan UserService untuk alur registrasi
  - [ ] 6.1 Update `UserService::register()` untuk membuat assessment dengan status UNVERIFIED
    - Ubah status default assessment dari `ACTIVE` ke `UNVERIFIED` saat registrasi
    - Setelah create assessment, panggil `EmailVerificationService::generateAndSendVerification()`
    - Wrap email sending dalam try-catch agar registrasi tetap berhasil meski email gagal
    - _Requirements: 1.1, 1.2, 3.1, 3.5_

  - [ ] 6.2 Update `AuthController::register()` response
    - Tambahkan `Assessment_status: 'UNVERIFIED'` dan `redirect_to: '/cek-email'` di response
    - Return token Sanctum agar frontend bisa store di localStorage
    - _Requirements: 1.1, 2.1_

  - [ ] 6.3 Update `AuthController::login()` untuk handle status UNVERIFIED
    - Cek assessment status setelah login
    - Jika UNVERIFIED: set `redirect_to = '/cek-email'` dan return `Assessment_status = 'UNVERIFIED'`
    - Update query `findActiveAssessmentByUserId` agar juga menemukan assessment UNVERIFIED
    - _Requirements: 6.1, 6.2, 6.3_

  - [ ]* 6.4 Tulis integration test untuk alur registrasi + verifikasi
    - Test POST /api/auth/register → assessment dibuat dengan status UNVERIFIED
    - Test POST /api/auth/register → email_verification_tokens record dibuat
    - Test POST /api/auth/login dengan UNVERIFIED → response berisi redirect /cek-email
    - Test full flow: register → verify token → assessment status ACTIVE
    - _Requirements: 1.1, 1.2, 3.1, 6.1, 6.2_

- [ ] 7. Routes: Tambahkan API dan web routes baru
  - [ ] 7.1 Tambahkan routes di `routes/api.php`
    - Public route: `GET /api/auth/verify-email/{token}` → `EmailVerificationController::verify`
    - Protected route: `POST /api/auth/resend-verification` → `EmailVerificationController::resend` (dengan throttle middleware)
    - _Requirements: 4.1, 2.5_

  - [ ] 7.2 Tambahkan routes di `routes/web.php`
    - `GET /cek-email` → return view `auth.cek-email`
    - `GET /verifikasi-gagal` → return view `auth.verifikasi-gagal`
    - _Requirements: 2.1, 4.4_

- [ ] 8. Frontend: Halaman baru dan update guards
  - [ ] 8.1 Buat halaman `resources/views/auth/cek-email.blade.php`
    - Layout konsisten dengan halaman auth lainnya (masuk, daftar)
    - Tampilkan pesan "Cek email Anda untuk link verifikasi"
    - Tampilkan alamat email yang digunakan saat registrasi (dari localStorage `auth_user`)
    - Tombol "Kirim Ulang Email" dengan Alpine.js state management
    - Implementasi cooldown 60 detik setelah klik resend (countdown timer)
    - Loading state saat proses resend
    - Feedback message sukses/error
    - Guard: redirect ke `/verifikasi` jika Assessment_status bukan UNVERIFIED
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

  - [ ] 8.2 Buat halaman `resources/views/auth/verifikasi-gagal.blade.php`
    - Tampilkan pesan error berdasarkan query param `reason` (expired/invalid)
    - Untuk expired: "Link verifikasi sudah kedaluwarsa" + tombol kirim ulang (redirect ke /cek-email atau login dulu)
    - Untuk invalid: "Link tidak valid atau sudah digunakan"
    - Link kembali ke halaman login
    - _Requirements: 4.4, 4.5_

  - [ ] 8.3 Update guard di `resources/views/auth/daftar.blade.php`
    - Setelah registrasi berhasil, simpan token, user, dan Assessment_status ke localStorage
    - Redirect ke `/cek-email` (bukan `/masuk`)
    - _Requirements: 1.1, 2.1_

  - [ ] 8.4 Update guard di `resources/views/auth/masuk.blade.php`
    - Setelah login, cek `Assessment_status` dari response
    - Jika UNVERIFIED: simpan ke localStorage dan redirect ke `/cek-email`
    - Handle query param `?verified=1` untuk tampilkan pesan sukses verifikasi
    - _Requirements: 6.1, 6.2, 6.3_

  - [ ] 8.5 Update guard di `resources/views/auth/verifikasi.blade.php`
    - Tambahkan pengecekan Assessment_status di localStorage
    - Jika UNVERIFIED: redirect ke `/cek-email`
    - Hanya izinkan akses jika status ACTIVE
    - _Requirements: 1.3, 1.4_

- [ ] 9. Checkpoint - Pastikan semua test pass dan alur lengkap
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 10. Verifikasi status PUBLISHED di reviewer detail
  - [ ] 10.1 Verifikasi badge PUBLISHED di `resources/views/reviewer/detail.blade.php`
    - Pastikan badge "Published" dengan warna emerald dan globe icon sudah ada dan berfungsi
    - Jika belum ada, tambahkan kondisi untuk status PUBLISHED di bagian status badge
    - _Requirements: 5.1, 5.2_

- [ ] 11. Final checkpoint - Pastikan semua fitur terintegrasi
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Unit tests validate specific examples and edge cases
- The project uses Laravel 11, Sanctum, Alpine.js, MySQL, Blade templates
- Email sending uses Laravel's built-in Mail with ShouldQueue for async dispatch
- No new packages required — all functionality uses Laravel 11 built-in features
- Token cleanup (expired tokens) can be added as a scheduled command later

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.2", "1.3"] },
    { "id": 1, "tasks": ["2.1", "3.1", "3.2"] },
    { "id": 2, "tasks": ["2.2", "4.1"] },
    { "id": 3, "tasks": ["4.2", "6.1"] },
    { "id": 4, "tasks": ["6.2", "6.3", "7.1", "7.2"] },
    { "id": 5, "tasks": ["6.4", "8.1", "8.2"] },
    { "id": 6, "tasks": ["8.3", "8.4", "8.5"] },
    { "id": 7, "tasks": ["10.1"] }
  ]
}
```
