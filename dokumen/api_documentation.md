# Patriot Metric — API Documentation

**Base URL**: `/api`  
**Authentication**: Semua protected endpoint menggunakan `Bearer Token` (Laravel Sanctum).  
Header yang diperlukan:
```
Authorization: Bearer {token}
Accept: application/json
```

---

## Response Format

Semua endpoint mengembalikan format JSON yang konsisten:

```json
{
    "success": true | false,
    "message": "Pesan deskriptif",
    "data": { ... } | null
}
```

---

## 🔐 Auth Endpoints

### POST `/api/auth/register`
**Auth**: Public  
**Deskripsi**: Mendaftarkan institusi baru ke sistem.

**Request Body**:
```json
{
    "nama_pt": "string (required)",
    "jenis_pt": "PTN | PTS | PTK (required)",
    "nama_pic": "string (required)",
    "jabatan_pic": "string (required)",
    "no_hp_pic": "string (required)",
    "email": "string email (required, unique)",
    "password": "string min:8 (required)",
    "password_confirmation": "string (required)"
}
```

**Response 201**:
```json
{
    "success": true,
    "message": "Registrasi berhasil. Silakan login untuk melanjutkan.",
    "data": { "user": { "id": 1, "email": "..." } }
}
```

**Error 422** (validasi gagal):
```json
{
    "success": false,
    "message": "The email has already been taken.",
    "errors": { "email": ["The email has already been taken."] }
}
```

---

### POST `/api/auth/login`
**Auth**: Public  
**Deskripsi**: Login untuk peserta, reviewer, atau admin.

**Request Body**:
```json
{
    "email": "string (required)",
    "password": "string (required)"
}
```

**Response 200**:
```json
{
    "success": true,
    "message": "Login berhasil.",
    "data": {
        "user": {
            "id": 3,
            "email": "pic@upnjatim.ac.id",
            "role": "peserta",
            "status": "ACTIVE"
        },
        "token": "1|xxxxxxxxxxx",
        "redirect_to": "/dashboard",
        "Assessment_status": "IN_PROGRESS"
    }
}
```

> **Note**: `redirect_to` dapat berupa `/verifikasi`, `/dashboard`, atau `/reviewer` berdasarkan role dan status.

**Error 401**:
```json
{
    "success": false,
    "message": "Email atau password salah."
}
```

---

### POST `/api/auth/logout`
**Auth**: Bearer Token (Required)  
**Deskripsi**: Logout dan invalidate token aktif.

**Request Body**: *(kosong)*

**Response 200**:
```json
{
    "success": true,
    "message": "Logout berhasil.",
    "data": null
}
```

---

### GET `/api/auth/me`
**Auth**: Bearer Token (Required)  
**Deskripsi**: Mengambil data lengkap user yang sedang login, termasuk Assessment aktif dan pengaturan CMS.

**Request Body**: *(kosong)*

**Response 200**:
```json
{
    "success": true,
    "message": "Data user berhasil diambil.",
    "data": {
        "user": {
            "id": 3,
            "email": "pic@upnjatim.ac.id",
            "role": "peserta",
            "status": "IN_PROGRESS"
        },
        "Assessment": {
            "id": 5,
            "status": "IN_PROGRESS",
            "tahun_periode": 2026,
            "nama_pic": "Budi Santoso",
            "jabatan_pic": "Wakil Rektor III",
            "no_hp_pic": "081234567890",
            "email_pic": "pic@upnjatim.ac.id",
            "institusi": { "id": "uuid", "nama_institusi": "UPN Veteran Jatim", "jenis_institusi": "PTN" },
            "identitas": { "jml_fakultas": 7, "jml_prodi": 42, ... },
            "agamas": [ { "agama": "Islam", "jumlah": 15000 }, ... ]
        },
        "is_edit_enabled": true,
        "is_peserta_profile_edit_enabled": true,
        "active_period": "2026"
    }
}
```

> **⭐ Update**: Response kini menyertakan field `is_peserta_profile_edit_enabled` yang mengontrol visibilitas tombol Edit Profil di dashboard peserta.

---

### PUT `/api/auth/profile` ✨ **(Endpoint Baru)**
**Auth**: Bearer Token (Required)  
**Deskripsi**: Memperbarui data PIC (Person in Charge) peserta untuk periode aktif. Endpoint ini hanya aktif jika setting CMS `is_peserta_profile_edit_enabled = true`.

**Request Body**:
```json
{
    "nama_pic": "string (required, max:255)",
    "jabatan_pic": "string (nullable, max:100)",
    "no_hp_pic": "string (required, max:20)"
}
```

**Response 200**:
```json
{
    "success": true,
    "message": "Profil berhasil diperbarui.",
    "data": {
        "nama_pic": "Budi Santoso",
        "jabatan_pic": "Wakil Rektor III",
        "no_hp_pic": "081234567890"
    }
}
```

**Error 403** (fitur dinonaktifkan admin):
```json
{
    "success": false,
    "message": "Fitur edit profil saat ini dinonaktifkan oleh admin."
}
```

**Error 404** (data Assessment tidak ditemukan):
```json
{
    "success": false,
    "message": "Data Assessment tidak ditemukan."
}
```

**Error 422** (validasi gagal):
```json
{
    "success": false,
    "message": "The nama_pic field is required."
}
```

---

## 📋 Assessment Endpoints (Peserta)

> Semua endpoint Assessment membutuhkan Bearer Token.

### GET `/api/assessment/peserta/questions`
**Deskripsi**: Mengambil semua pertanyaan rubrik berikut jawaban draft yang sudah tersimpan.

**Response 200**:
```json
{
    "success": true,
    "message": "Data berhasil diambil.",
    "data": {
        "status": "IN_PROGRESS",
        "is_edit_enabled": true,
        "questions": [
            {
                "id": 10,
                "kode_pertanyaan": "A1",
                "teks_pertanyaan": "Apakah institusi memiliki ...",
                "deskripsi": "Keterangan tambahan",
                "tipe": "pilihan_ganda",
                "kebutuhan_bukti": ["SK Rektor", "Laporan Tahunan"],
                "kategori": { "nama_kategori": "Kategori A" },
                "OpsiJawaban": [
                    { "id": 1, "keterangan": "Ya, ada dan terverifikasi (5 poin)" },
                    { "id": 2, "keterangan": "Ada, namun belum terverifikasi (3 poin)" }
                ],
                "jawaban": [
                    { "jawaban_id": 1, "jawaban_teks": null, "tautan_bukti_drive": "https://drive.google.com/..." }
                ]
            }
        ]
    }
}
```

---

### POST `/api/assessment/peserta/save-draft`
**Deskripsi**: Menyimpan semua jawaban secara batch dan mengubah status Assessment menjadi `SUBMITTED`.

**Request Body**:
```json
{
    "answers": [
        {
            "pertanyaan_id": 10,
            "jawaban_id": 1,
            "jawaban_teks": null,
            "tautan_bukti": "https://drive.google.com/..."
        },
        {
            "pertanyaan_id": 11,
            "jawaban_id": null,
            "jawaban_teks": "42",
            "tautan_bukti": null
        }
    ]
}
```

**Response 200**:
```json
{
    "success": true,
    "message": "Semua jawaban berhasil disimpan dan di-submit.",
    "data": { ... }
}
```

**Error 403** (status sudah SUBMITTED/GRADED atau edit dinonaktifkan):
```json
{
    "success": false,
    "message": "Asesmen sudah dikunci dan tidak dapat diubah."
}
```

---

### POST `/api/assessment/peserta/save-answer`
**Deskripsi**: Menyimpan satu jawaban secara individual (auto-save per soal).

**Request Body**:
```json
{
    "pertanyaan_id": 10,
    "jawaban_id": 1,
    "jawaban_teks": null,
    "tautan_bukti": "https://drive.google.com/..."
}
```

**Response 200**:
```json
{
    "success": true,
    "message": "Jawaban berhasil disimpan.",
    "data": { ... }
}
```

---

### GET `/api/assessment/peserta/hasil`
**Deskripsi**: Mengambil data hasil penilaian peserta (hanya tersedia setelah SUBMITTED/GRADED).

**Response 200**:
```json
{
    "success": true,
    "message": "Data hasil berhasil diambil.",
    "data": {
        "status": "GRADED",
        "total_skor_sistem": 87.5,
        "total_skor_akhir": 85.0,
        "categories": [ ... ]
    }
}
```

---

### GET `/api/assessment/peserta/current-progress`
**Deskripsi**: Mengambil progres pengisian (berapa soal sudah dijawab dari total).

**Response 200**:
```json
{
    "success": true,
    "message": "Data progres pengisian berhasil diambil.",
    "data": {
        "total_questions": 40,
        "answered_questions": 25,
        "percentage": 62.5
    }
}
```

---

## 🔍 Reviewer Endpoints

> Semua endpoint Reviewer membutuhkan Bearer Token dengan role REVIEWER.

### GET `/api/assessment/reviewer/tasks`
**Deskripsi**: Mengambil daftar peserta yang ditugaskan kepada reviewer yang sedang login.

**Response 200**:
```json
{
    "success": true,
    "message": "Data tasks berhasil diambil.",
    "data": [
        {
            "id": 5,
            "status": "IN_PROGRESS",
            "institusi": { "nama_institusi": "UPN Veteran Jatim" },
            "identitas": { ... }
        }
    ]
}
```

---

### GET `/api/assessment/reviewer/tasks/detail/{pesertaId}`
**Deskripsi**: Mengambil detail pertanyaan dan jawaban peserta tertentu untuk proses review.

**Path Parameter**: `pesertaId` — ID `Assessment` milik peserta.

**Response 200**:
```json
{
    "success": true,
    "message": "Detail task berhasil diambil.",
    "data": {
        "peserta": { ... },
        "questions": [ ... ]
    }
}
```

---

## ⚙️ CMS Settings (Pengaturan Admin)

Pengaturan dikontrol melalui tabel `pengaturan_cms` dengan format key-value:

| Key | Value | Keterangan |
|---|---|---|
| `active_period` | `2026` | Tahun periode aktif assessment |
| `is_peserta_edit_enabled` | `true` / `false` | Mengizinkan/melarang peserta mengisi form rubrik |
| `is_peserta_profile_edit_enabled` | `true` / `false` | ⭐ **(Baru)** Mengizinkan/melarang peserta mengedit data profil PIC |

---

## 🗒️ Error Codes Summary

| Code | Arti |
|---|---|
| `200` | Sukses |
| `201` | Data berhasil dibuat |
| `401` | Unauthorized — token tidak valid atau tidak ada |
| `403` | Forbidden — tidak memiliki izin |
| `404` | Data tidak ditemukan |
| `422` | Validasi gagal |
| `500` | Internal Server Error |
