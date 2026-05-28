# Contoh Penggunaan Bruno API

## Apa itu Bruno?

[Bruno](https://www.usebruno.com/) adalah API client (alternatif Postman) yang menyimpan koleksi request sebagai file `.bru` di dalam repo. Folder `bruno-api/` berisi koleksi endpoint Patriot Metric yang bisa langsung dijalankan.

---

## Cara Pakai

### 1. Install Bruno

Download dari [usebruno.com](https://www.usebruno.com/) atau install via:

```bash
npm install -g @usebruno/cli
```

### 2. Buka Koleksi

Di Bruno GUI: **Open Collection** → pilih folder `bruno-api/`.

### 3. Jalankan Server Lokal

```bash
composer run dev
```

Pastikan Laravel berjalan di `http://127.0.0.1:8000`.

---

## Endpoint yang Tersedia

### Alur Peserta (Submitter)

| # | Request | Method | URL |
|---|---------|--------|-----|
| 1 | Baseline (isi data institusi) | POST | `/api/baseline/{userId}` |
| 2 | Ambil soal per kategori | GET | `/api/peserta/questions/{cat_id}` |
| 3 | Save progress jawaban | POST | `/api/peserta/save-progress` |
| 4 | Finalize assessment | POST | `/api/peserta/finalize` |

### Alur Peserta - Gemini (Sequential Testing)

| # | Request | Method | URL |
|---|---------|--------|-----|
| 1 | Baseline | POST | `/api/baseline/{userId}` |
| 2 | Get All Questions | GET | `/api/peserta/questions/{cat_id}` |
| 3 | Autosave jawaban | POST | (autosave endpoint) |
| 4 | Lock jawaban | POST | (lock endpoint) |
| 5 | Preview nilai | GET | (preview endpoint) |

### Alur Reviewer

| # | Request | Method | URL |
|---|---------|--------|-----|
| 1 | Lihat assignment | GET | `/api/assessment/reviewer/tasks` |
| 2 | Melihat assignment detail | GET | (detail endpoint) |
| 3 | Save verification | POST | `/api/reviewer/save-verification` |
| 4 | Finalize verification | POST | (finalize endpoint) |
| 5 | Verifikasi assessment | POST | (verifikasi endpoint) |

---

## Contoh Request: Baseline (Multipart Form)

Request ini mengirim data identitas institusi + upload dokumen legal:

```
POST http://127.0.0.1:8000/api/baseline/2
Content-Type: multipart/form-data

Fields:
  nama_institusi: "Universitas Contoh"
  jenis_institusi: "PTN"
  nama_pic: "Dr. Budi"
  jabatan_pic: "Wakil Rektor"
  no_hp_pic: "08123456789"
  jml_mahasiswa: 10000
  jml_dosen: 100
  jml_tendik: 100
  jml_prodi: 25
  jml_ukm: 40
  jml_fakultas: 12
  visi: "Menjadi universitas unggul"
  misi: "Menghasilkan lulusan berkualitas"
  jml_agama[islam]: 8000
  jml_agama[kristen]: 1000
  jml_agama[katolik]: 500
  jml_agama[hindu]: 200
  jml_agama[budha]: 100
  jml_agama[konghucu]: 50

Files:
  legal_documents[surat_pengantar]: @file(path/to/file.pdf)
  legal_documents[sk_pendirian_pt]: @file(path/to/file.pdf)
  legal_documents[sk_rektor_bela_negara]: @file(path/to/file.pdf)
  legal_documents[struktur_organisasi]: @file(path/to/file.pdf)
  legal_documents[pakta_integritas]: @file(path/to/file.pdf)
  legal_documents[dokumen_akreditasi]: @file(path/to/file.pdf)
  legal_documents[surat_pernyataan_pic]: @file(path/to/file.pdf)
```

**Response:**
```json
{
  "success": true,
  "message": "Data baseline berhasil disimpan",
  "data": null
}
```

---

## Menjalankan via CLI

Kalau mau jalankan dari terminal tanpa GUI:

```bash
# Jalankan semua request di folder submitter
npx @usebruno/cli run bruno-api/submitter

# Jalankan satu request spesifik
npx @usebruno/cli run "bruno-api/submitter/finalize assesment.bru"

# Jalankan folder reviewer
npx @usebruno/cli run bruno-api/reviewer
```

---

## Panduan Penulisan & Dokumentasi API di Bruno

### Konvensi Penamaan File `.bru`

- Gunakan nama deskriptif dalam Bahasa Indonesia/Inggris yang menjelaskan aksi endpoint.
- Untuk alur sequential, tambahkan prefix angka: `1 baseline.bru`, `2 Get All Questions.bru`, dst.
- Gunakan spasi sebagai pemisah kata (Bruno mendukung ini).

Contoh penamaan yang baik:
```
submitter/
├── 1 baseline.bru
├── 2 Get All Questions.bru
├── 3 save progress.bru
├── 4 finalize assesment.bru
└── 5 preview skor.bru

reviewer/
├── get reviewer task.bru
├── save-verification.bru
└── finalize verification.bru
```

### Struktur File `.bru`

Setiap file `.bru` memiliki format berikut:

```bru
meta {
  name: nama-request
  type: http
  seq: 1
}

post {
  url: http://127.0.0.1:8000/api/endpoint
  body: json
  auth: bearer
}

auth:bearer {
  token: {{token}}
}

headers {
  Accept: application/json
  Content-Type: application/json
}

body:json {
  {
    "field_1": "value",
    "field_2": 123
  }
}

docs {
  Deskripsi endpoint di sini.

  REQUEST
  - field_1 (string, required): Penjelasan field
  - field_2 (int, required): Penjelasan field

  RESPONSE
  {
    "success": true,
    "message": "Pesan sukses",
    "data": { ... }
  }
}
```

### Konvensi Penamaan Endpoint API

Berdasarkan pola yang digunakan di project ini:

| Prefix | Role | Contoh |
|--------|------|--------|
| `/api/auth/` | Autentikasi | `/api/auth/register`, `/api/auth/login` |
| `/api/profile/` | Profil & baseline | `/api/profile/baseline` |
| `/api/peserta/` | Aksi peserta | `/api/peserta/save-progress`, `/api/peserta/finalize` |
| `/api/reviewer/` | Aksi reviewer | `/api/reviewer/save-verification` |
| `/api/assessment/` | Data assessment | `/api/assessment/reviewer/tasks` |

Aturan penamaan:
- Gunakan **kebab-case** untuk multi-word: `save-progress`, `save-verification`
- Gunakan **noun** untuk resource GET: `/api/peserta/questions/{cat_id}`
- Gunakan **verb-noun** untuk aksi POST: `/api/peserta/save-progress`, `/api/peserta/finalize`
- Parameter path menggunakan `{camelCase}`: `{userId}`, `{cat_id}`

### Cara Mengisi Request Body

#### JSON Body

```bru
body:json {
  {
    "category_id": 1,
    "answers": [
      {
        "indicator_id": "q1",
        "claim_value": "5",
        "evidence_url": "https://drive.google.com/..."
      }
    ]
  }
}
```

#### Multipart Form (Upload File)

```bru
body:multipart-form {
  nama_institusi: Universitas Contoh
  jenis_institusi: PTN
  jml_mahasiswa: 10000
  jml_agama[islam]: 8000
  jml_agama[kristen]: 1000
  legal_documents[surat_pengantar]: @file(C:\path\to\file.pdf)
  legal_documents[sk_pendirian_pt]: @file(C:\path\to\file.pdf)
}
```

#### Tanpa Body

```bru
get {
  url: http://127.0.0.1:8000/api/peserta/questions/1
  body: none
  auth: bearer
}
```

### Cara Menulis Dokumentasi (Blok `docs`)

Setiap request **wajib** memiliki blok `docs` yang menjelaskan:

1. **Deskripsi singkat** — apa yang dilakukan endpoint ini
2. **REQUEST** — daftar parameter beserta tipe dan keterangan
3. **RESPONSE** — contoh response JSON

Format standar:

```bru
docs {
  POST /api/peserta/save-progress

  Menyimpan jawaban peserta secara otomatis (auto-save per kategori).
  Dipanggil saat peserta klik "Next" atau navigasi antar kategori.

  REQUEST
  - category_id (int, required): ID kategori yang sedang dikerjakan
  - answers (array, required): Array jawaban
    - answers[].indicator_id (string): ID indikator/pertanyaan
    - answers[].claim_value (string): Nilai klaim yang dipilih
    - answers[].evidence_url (string): URL bukti pendukung (Google Drive)

  RESPONSE (200 OK)
  {
    "success": true,
    "message": "Progress berhasil disimpan",
    "data": null
  }

  RESPONSE (422 Unprocessable)
  {
    "success": false,
    "message": "Validasi gagal",
    "errors": {
      "answers.0.claim_value": ["Field claim_value wajib diisi"]
    }
  }
}
```

### Contoh Lengkap: File `.bru` yang Terdokumentasi dengan Baik

```bru
meta {
  name: save progress
  type: http
  seq: 3
}

post {
  url: http://127.0.0.1:8000/api/peserta/save-progress
  body: json
  auth: bearer
}

auth:bearer {
  token: {{token}}
}

headers {
  Accept: application/json
  Content-Type: application/json
}

body:json {
  {
    "category_id": 1,
    "answers": [
      {
        "indicator_id": "q1",
        "claim_value": "5",
        "evidence_url": "https://drive.google.com/file/d/xxx"
      },
      {
        "indicator_id": "q2",
        "claim_value": "3",
        "evidence_url": "https://drive.google.com/file/d/yyy"
      }
    ]
  }
}

docs {
  POST /api/peserta/save-progress

  Menyimpan jawaban peserta secara atomik per kategori.
  Menggunakan updateOrCreate sehingga aman dipanggil berulang.

  Auth: Bearer Token (Sanctum)
  Role: PESERTA
  Kondisi: Assessment status harus ACTIVE atau IN_PROGRESS

  REQUEST
  - category_id (int, required): ID kategori
  - answers (array, required): Daftar jawaban
    - indicator_id (string): ID pertanyaan
    - claim_value (string): Nilai klaim
    - evidence_url (string): Link bukti

  RESPONSE (200)
  {
    "success": true,
    "message": "Progress berhasil disimpan",
    "data": null
  }
}
```

### Penggunaan Environment Variable

Buat file `environments/local.bru` di dalam folder `bruno-api/`:

```bru
vars {
  base_url: http://127.0.0.1:8000
  token: <isi-setelah-login>
}
```

Lalu gunakan di request:

```bru
post {
  url: {{base_url}}/api/peserta/save-progress
  auth: bearer
}

auth:bearer {
  token: {{token}}
}
```

---

## Tips

- Semua request yang butuh auth harus menyertakan header `Authorization: Bearer <token>` — tambahkan di tab Auth Bruno setelah login.
- Folder `submitter-gemini/` berisi variasi request yang diurutkan secara sequential (1→5) untuk testing alur lengkap.
- File `.bru` bisa diedit langsung sebagai teks jika perlu tweak cepat tanpa buka Bruno GUI.
- Pastikan `composer run dev` sudah berjalan sebelum menjalankan request.
- Selalu isi blok `docs` di setiap file `.bru` agar tim lain bisa memahami kontrak API tanpa buka source code.
- Gunakan environment variable (`{{base_url}}`, `{{token}}`) agar tidak perlu hardcode URL dan token di setiap request.
