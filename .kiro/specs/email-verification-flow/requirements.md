# Requirements Document

## Introduction

Fitur Email Verification Flow menambahkan proses verifikasi email setelah registrasi peserta di Patriot Metric. Setelah peserta mengisi form pendaftaran, sistem mengirim email konfirmasi berisi token link. Peserta harus mengklik link tersebut untuk mengaktifkan akun. Selain itu, status "PUBLISHED" ditampilkan di halaman reviewer dan status baru "UNVERIFIED" ditambahkan sebagai status awal assessment.

## Glossary

- **System**: Aplikasi Patriot Metric (backend Laravel + frontend Alpine.js)
- **Peserta**: User dengan role PESERTA yang mendaftar melalui form registrasi
- **Reviewer**: User dengan role REVIEWER yang menilai assessment peserta
- **Assessment**: Record penilaian yang dibuat saat peserta mendaftar, memiliki status lifecycle
- **Email_Verification_Token**: Token unik yang dikirim ke email peserta untuk memvalidasi kepemilikan email
- **Confirmation_Email**: Pesan email yang berisi link verifikasi dengan token
- **Check_Email_Page**: Halaman yang ditampilkan setelah registrasi, menginformasikan peserta untuk mengecek email
- **Verification_Link**: URL yang berisi token verifikasi, diklik peserta dari email untuk mengaktifkan akun
- **Registration_Form**: Form pendaftaran institusi di `/daftar` (`resources/views/auth/daftar.blade.php`)
- **Reviewer_Detail_Page**: Halaman detail penilaian peserta di `/reviewer/detail` yang menampilkan status assessment

## Requirements

### Requirement 1: Status UNVERIFIED pada Assessment Baru

**User Story:** Sebagai sistem, saya ingin assessment baru yang dibuat saat registrasi memiliki status UNVERIFIED, sehingga peserta harus memverifikasi email sebelum dapat mengakses fitur assessment.

#### Acceptance Criteria

1. WHEN a Peserta completes the Registration_Form, THE System SHALL create a new Assessment with status "UNVERIFIED"
2. WHEN a Peserta completes the Registration_Form, THE System SHALL create the User record with status "ACTIVE" and role "PESERTA"
3. WHILE the Assessment status is "UNVERIFIED", THE System SHALL prevent the Peserta from accessing the `/verifikasi` page
4. WHILE the Assessment status is "UNVERIFIED", THE System SHALL redirect the Peserta to the Check_Email_Page

### Requirement 2: Halaman Cek Email Setelah Registrasi

**User Story:** Sebagai peserta, saya ingin melihat halaman konfirmasi setelah registrasi yang menginformasikan bahwa saya perlu mengecek email, sehingga saya tahu langkah selanjutnya.

#### Acceptance Criteria

1. WHEN a Peserta successfully submits the Registration_Form, THE System SHALL redirect the Peserta to the Check_Email_Page
2. THE Check_Email_Page SHALL display a message informing the Peserta to check the registered email for a Confirmation_Email
3. THE Check_Email_Page SHALL display the email address that was used during registration
4. THE Check_Email_Page SHALL provide a button to resend the Confirmation_Email
5. WHEN the Peserta clicks the resend button, THE System SHALL send a new Confirmation_Email to the registered email address

### Requirement 3: Pengiriman Email Konfirmasi

**User Story:** Sebagai peserta, saya ingin menerima email konfirmasi setelah mendaftar, sehingga saya dapat memverifikasi kepemilikan email institusi saya.

#### Acceptance Criteria

1. WHEN a Peserta successfully submits the Registration_Form, THE System SHALL send a Confirmation_Email to the registered email address
2. THE Confirmation_Email SHALL contain a Verification_Link with a unique Email_Verification_Token
3. THE Email_Verification_Token SHALL have an expiration time of 60 minutes from creation
4. THE Confirmation_Email SHALL include the institution name and a clear call-to-action button to verify the email
5. IF the Confirmation_Email fails to send, THEN THE System SHALL log the error and display an option to resend on the Check_Email_Page

### Requirement 4: Validasi Token dan Aktivasi Akun

**User Story:** Sebagai peserta, saya ingin mengklik link di email untuk memverifikasi akun saya, sehingga saya dapat mengakses halaman verifikasi data institusi.

#### Acceptance Criteria

1. WHEN a Peserta clicks the Verification_Link, THE System SHALL validate the Email_Verification_Token
2. WHEN the Email_Verification_Token is valid and not expired, THE System SHALL change the Assessment status from "UNVERIFIED" to "ACTIVE"
3. WHEN the Email_Verification_Token is valid and not expired, THE System SHALL redirect the Peserta to the `/masuk` page with a success message
4. IF the Email_Verification_Token is expired, THEN THE System SHALL display an error message and provide an option to resend the Confirmation_Email
5. IF the Email_Verification_Token is invalid or already used, THEN THE System SHALL display an error message indicating the link is invalid
6. THE System SHALL mark the Email_Verification_Token as used after successful verification to prevent reuse

### Requirement 5: Status PUBLISHED di Halaman Reviewer

**User Story:** Sebagai reviewer, saya ingin melihat status "Published" pada assessment yang sudah dipublikasikan, sehingga saya dapat membedakan assessment yang sudah final dan dipublikasikan.

#### Acceptance Criteria

1. THE Reviewer_Detail_Page SHALL display a "Published" badge with emerald color styling when the Assessment status is "PUBLISHED"
2. THE Reviewer_Detail_Page SHALL display the PUBLISHED status with a globe icon indicator

### Requirement 6: Integrasi Status UNVERIFIED di Alur Login

**User Story:** Sebagai peserta dengan status UNVERIFIED, saya ingin diarahkan ke halaman cek email saat login, sehingga saya tahu bahwa saya perlu menyelesaikan verifikasi email terlebih dahulu.

#### Acceptance Criteria

1. WHEN a Peserta with an UNVERIFIED Assessment logs in, THE System SHALL redirect the Peserta to the Check_Email_Page
2. WHEN a Peserta with an UNVERIFIED Assessment logs in, THE System SHALL return "UNVERIFIED" as the Assessment_status in the login response
3. THE System SHALL store the "UNVERIFIED" Assessment_status in localStorage for frontend routing decisions
