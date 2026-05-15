# Patriot Metric — Project Context for Claude

## Gambaran Proyek

**Patriot Metric** adalah aplikasi web penilaian rubrik untuk perguruan tinggi, dibangun dengan Laravel 12 + Filament 4 (admin panel) + Livewire 3. Frontend publik menggunakan Blade + Vanilla JS dengan arsitektur **SPA-lite**: halaman dirender server-side (Blade), lalu data diambil via Fetch/Axios ke REST API (`/api/...`) menggunakan token Sanctum yang disimpan di `localStorage`.

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Admin Panel | Filament 4 |
| Frontend | Blade, Vanilla JS (Fetch API) |
| Realtime/Komponen | Livewire 3 |
| Auth | Laravel Sanctum (token-based, bukan session cookie untuk API) |
| Build Tool | Vite |
| Database | MySQL (SQLite untuk dev lokal) |
| Queue | Laravel Queue (via `php artisan queue:listen`) |
| Dev Server | `composer run dev` (concurrently: serve + queue + vite) |

---

## Cara Menjalankan

```bash
# Install dependencies
composer install && npm install

# Setup environment
cp .env.example .env && php artisan key:generate

# Migrasi + seed
php artisan migrate --seed

# Jalankan semua server (Laravel + Queue + Vite)
composer run dev
# → Laravel di http://localhost:8000
# → Vite dev server (hot reload asset)
# → Queue worker aktif

# Jalankan test
composer run test
```

---

## Struktur Direktori Penting

```
app/
├── Filament/Resources/     # Admin panel resources (Filament 4)
│   ├── Assessments/        # Manajemen Assessment
│   ├── Categories/         # Manajemen Kategori rubrik
│   ├── PengaturanCms/      # Pengaturan CMS (toggle fitur, periode aktif)
│   ├── Pertanyaans/        # Manajemen butir pertanyaan
│   ├── Reviewers/          # Manajemen akun reviewer
│   ├── Rubriks/            # Manajemen rubrik
│   ├── SubmissionTimelines/ # Jadwal buka/tutup form
│   └── Users/              # Manajemen akun peserta
├── Http/
│   └── Controllers/
│       ├── AuthController.php          # Register, login, logout, /me, updateProfile
│       ├── AssessmentController.php    # Pengisian rubrik peserta (save-answer, finalize, dll)
│       ├── ReviewerController.php      # Penilaian reviewer (save-scores, finalize)
│       ├── VerificationController.php  # Verifikasi identitas institusi
│       ├── ProfileController.php       # Profil peserta
│       └── PengaturanCmsController.php # CMS settings
├── Models/
│   ├── User.php, Institusi.php, Assessment.php
│   ├── Identitas.php, Agama.php
│   ├── Kategori.php, Pertanyaan.php, OpsiJawaban.php
│   ├── ResponAssessment.php, Reviewer.php
│   └── SubmissionTimeline.php, PengaturanCms.php
├── Repositories/           # Repository pattern (data access layer)
├── Services/               # Business logic layer
│   ├── AssessmentService.php   # Service utama, paling kompleks
│   ├── ReviewService.php       # Logic penilaian reviewer
│   ├── RubrikService.php       # Logic rubrik
│   └── SubmissionService.php   # Logic submit & timeline
├── DTO/                    # Data Transfer Objects
└── Traits/
    └── ApiResponse.php     # Trait untuk format respons API konsisten

resources/views/
├── welcome.blade.php       # Landing page publik
├── auth/                   # Login, daftar, verifikasi
├── dashboard/              # Halaman peserta (rubrik, hasil, panduan)
├── reviewer/               # Halaman reviewer (index, panduan, detail)
└── components/             # Blade components (header, sidebar, dll)

routes/
├── web.php     # Halaman (Blade views, no middleware auth)
├── api.php     # REST API (auth:sanctum untuk protected routes)
└── console.php # Scheduled commands

database/
├── migrations/ # 16 migrasi aktif
└── seeders/
```

---

## Arsitektur Autentikasi

- **Token Sanctum** disimpan di `localStorage` (`pm_token`).
- Setiap request API dari frontend menyertakan header `Authorization: Bearer <token>`.
- **Tidak ada session cookie** untuk API — murni token-based.
- Login mengembalikan `redirect_to` berdasarkan role dan status assessment.
- Role sistem: `ADMIN`, `REVIEWER`, `PESERTA`.

---

## Alur Utama Aplikasi

### Alur Peserta
1. **Daftar** (`/daftar`) → registrasi akun + institusi (1 institusi = 1 akun, email harus `.ac.id`)
2. **Verifikasi** (`/verifikasi`) → upload bukti, isi data identitas institusi, tunggu persetujuan admin
3. **Dashboard Rubrik** (`/dashboard/rubrik`) → isi form penilaian rubrik (auto-save per jawaban)
4. **Submit** → finalize assessment
5. **Hasil** (`/dashboard/hasil`) → lihat skor setelah dipublikasikan

### Alur Reviewer
1. Login → redirect ke `/reviewer`
2. **Index** (`/reviewer`) → daftar peserta yang di-assign
3. **Detail** (`/reviewer/peserta/{id}`) → isi validasi skor + catatan per butir pertanyaan
4. **Finalize** → submit hasil penilaian

### Alur Admin (Filament)
- URL admin panel: `/admin`
- Kelola user, institusi, rubrik, pertanyaan, periode, CMS settings

---

## Model & Relasi Database

```
users ──< assessments >── institusis
               │
               ├──< identitas >── agama[]
               ├──< respon_assessments >── pertanyaans >── opsi_jawaban[]
               └── reviewer_id → reviewers → users

kategoris ──< pertanyaans ──< opsi_jawaban
submission_timelines (per tahun_periode)
pengaturan_cms (key-value settings)
```

### Status Assessment
`ACTIVE` → `IN_PROGRESS` → `SUBMITTED` → `GRADED` → `PUBLISHED`

---

## Tipe Pertanyaan Rubrik

| Tipe | Keterangan |
|---|---|
| `pilihan_ganda` | Multiple choice dengan bobot nilai per opsi |
| `isian_singkat` | Input teks/angka, dinilai manual oleh reviewer |
| `otomatis_sistem` | Dihitung otomatis dari data identitas (numerik) |

Pertanyaan B13 adalah tipe khusus: input 4-skala dengan payload JSON bertingkat di `jawaban_teks`.

---

## CMS Settings (Key-Value)

| Key | Fungsi |
|---|---|
| `active_period` | Tahun periode aktif (misalnya: `2026`) |
| `is_peserta_edit_enabled` | Toggle edit jawaban rubrik peserta |
| `is_peserta_profile_edit_enabled` | Toggle edit profil peserta |

---

## Konvensi Kode

### PHP / Laravel
- **Repository Pattern**: Semua query DB melalui `app/Repositories/`, bukan langsung di Controller.
- **Service Layer**: Business logic di `app/Services/`, Controller hanya orchestrate.
- **DTO**: Gunakan DTO di `app/DTO/` untuk transfer data antar layer.
- **API Response**: Selalu gunakan trait `ApiResponse` — method `successResponse()` dan `errorResponse()`.
- **Bahasa**: Variabel, method, komentar kode dalam **Bahasa Inggris**. Pesan error/validasi ke user dalam **Bahasa Indonesia**.
- **Nama tabel**: campuran (ikuti konvensi yang sudah ada, misal `institusis`, `kategoris`, `pertanyaans`).

### JavaScript (Frontend)
- **Tidak ada framework** — Vanilla JS murni dengan Fetch API.
- Token disimpan di `localStorage` dengan key `pm_token`.
- Semua request API ke `/api/...` menyertakan `Authorization: Bearer` header.
- Auto-save menggunakan `debounce` — simpan per jawaban, bukan bulk.

### Blade Views
- Komponen shared ada di `resources/views/components/`.
- Setiap halaman publik menggunakan layout yang sama (header navigation di `components/`).
- Dashboard peserta dan reviewer memiliki layout tersendiri.

---

## File Penting yang Perlu Diperhatikan

- `app/Services/AssessmentService.php` — service terbesar (~38KB), inti logic penilaian
- `app/Http/Controllers/AuthController.php` — autentikasi + `/me` endpoint (complex logic re-period)
- `app/Http/Controllers/ReviewerController.php` — save-scores dengan validasi note reviewer (min 20 karakter)
- `app/Http/Controllers/VerificationController.php` — verifikasi institusi, upload dokumen
- `database/migrations/` — selalu buat migrasi baru, jangan edit migrasi yang sudah ada
- `database.md` — dokumentasi skema database terkini (termasuk kolom yang tidak terpakai)

---

## Development Notes

- **Jangan edit migrasi lama** — selalu buat file migrasi baru untuk perubahan skema.
- **Queue harus berjalan** untuk fitur tertentu — gunakan `composer run dev` yang sudah include queue:listen.
- **Filament Panel** berada di `/admin` — dikonfigurasi di `app/Providers/Filament/AdminPanelProvider.php`.
- **Vite** digunakan untuk bundling asset — perubahan CSS/JS memerlukan dev server Vite aktif.
- File `dokumen/`, `bruno-api/`, `.kiro/` adalah folder tooling lokal dan **tidak di-commit** (ada di `.gitignore`).
- `bruno-api/` berisi koleksi Bruno API untuk testing endpoint — gunakan sebagai referensi kontrak API.
