# Konfigurasi Email untuk Patriot Metric

## Langkah-langkah Setup Email

### 1. Pilih Provider Email

Anda bisa menggunakan salah satu dari provider berikut:

#### Opsi A: Gmail SMTP (Paling Mudah untuk Development/Testing)

1. Buka Google Account → Security → 2-Step Verification (aktifkan dulu)
2. Buat App Password: Google Account → Security → App Passwords → Generate
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email-anda@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@patriotmetric.id"
MAIL_FROM_NAME="Patriot Metric"
```

#### Opsi B: Mailtrap (Untuk Testing - Email Tidak Benar-benar Terkirim)

1. Daftar di https://mailtrap.io (gratis)
2. Buat inbox baru
3. Copy SMTP credentials dari Mailtrap
4. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@patriotmetric.id"
MAIL_FROM_NAME="Patriot Metric"
```

#### Opsi C: Mailgun / SendGrid / AWS SES (Production)

Untuk production, disarankan menggunakan service email transactional:

**Mailgun:**
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.patriotmetric.id
MAILGUN_SECRET=your-mailgun-api-key
MAIL_FROM_ADDRESS="noreply@patriotmetric.id"
MAIL_FROM_NAME="Patriot Metric"
```

**SendGrid:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@patriotmetric.id"
MAIL_FROM_NAME="Patriot Metric"
```

### 2. Setup Queue Worker (WAJIB)

Email dikirim secara async via queue. Anda HARUS menjalankan queue worker:

```bash
# Jalankan migration dulu (untuk tabel jobs)
php artisan queue:table
php artisan migrate

# Jalankan queue worker
php artisan queue:work
```

Untuk production, gunakan Supervisor untuk menjaga queue worker tetap berjalan.

### 3. Jalankan Migration

```bash
php artisan migrate
```

Ini akan membuat:
- Tabel `email_verification_tokens`
- Menambah status `UNVERIFIED` ke enum `assessments.status`

### 4. Test Email

Untuk testing cepat tanpa setup SMTP, gunakan `MAIL_MAILER=log`:
- Email akan ditulis ke `storage/logs/laravel.log`
- Anda bisa copy link verifikasi dari log untuk testing

```bash
# Lihat log email
tail -f storage/logs/laravel.log
```

### 5. Konfigurasi APP_URL

Pastikan `APP_URL` di `.env` sesuai dengan domain yang diakses user:

```env
# Development
APP_URL=http://localhost:8000

# Production
APP_URL=https://patriotmetric.id
```

Link verifikasi di email akan menggunakan APP_URL ini.

---

## Ringkasan Perubahan .env yang Diperlukan

Minimal yang perlu diubah dari konfigurasi saat ini:

```env
# SEBELUM (hanya log, tidak kirim email)
MAIL_MAILER=log

# SESUDAH (pilih salah satu provider di atas)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email-anda@gmail.com
MAIL_PASSWORD=app-password-anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@patriotmetric.id"
MAIL_FROM_NAME="Patriot Metric"
```

## Troubleshooting

- **Email tidak terkirim?** Pastikan queue worker berjalan: `php artisan queue:work`
- **Token expired?** Default 60 menit. Bisa diubah di `EmailVerificationService.php` line `now()->addMinutes(60)`
- **Rate limit resend?** Max 3 request per 5 menit per user (throttle middleware)
