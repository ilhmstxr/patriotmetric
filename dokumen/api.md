single device login		
Method	Endpoint	Fungsi
POST	/api/auth/register	Registrasi awal (Nama PT, PIC, dll).
POST	/api/auth/login	Login & hapus sesi di perangkat lain (Invalidate old session).
POST	/api/auth/logout	Logout & hapus last_session_id.
		
Method	Endpoint	Fungsi
		
GET	/api/profile	Mengambil data profil dan status (verifying, peserta, dll).
POST	/api/profile/baseline	Mengisi data Jumlah Mhs, Dosen, dll (Hanya bisa sekali/sebelum dikunci).
GET	/api/profile/status	Mengecek status verifikasi dari Admin Pusat.
		
		
Method	Endpoint	Fungsi
GET	/api/assessment/questions	Mengambil daftar soal (A, B, C) beserta rumus/konstanta.
POST	/api/assessment/answers	Menyimpan klaim jawaban + URL Link Drive (Wajib).
POST	/api/assessment/submit	Final Submit: Mengubah status menjadi submitted & kunci editing.
GET	/api/assessment/preview	Mengambil Ranged Score (Skala 1-5) untuk dashboard.
		
		
Method	Endpoint	Fungsi
GET	/api/review/submissions	List institusi yang sudah melakukan Final Submit.
GET	/api/review/submissions/{id}	Detail jawaban institusi tertentu (Klaim + Link Drive).
PATCH	/api/review/answers/{id}	Verdict: Reviewer input angka granular. Memicu kalkulasi skor.
POST	/api/review/publish/{id}	Admin Pusat mempublikasikan hasil agar skor asli muncul di user.

---
## Daftar Controller yang Memanggil Service

Berdasarkan hasil pengecekan di folder `app/Http/Controllers` dan `app/Services`, saat ini **belum ada controller yang memanggil service**.

Seluruh controller yang ada (seperti `AssessmentController`, `AuthController`, dll.) belum menginjeksi atau memanggil class dari `App\Services\`. Logika di dalam controller masih berupa *scaffolding logic* dasar yang mengembalikan file inertia atau `redirect()->back()` (contoh: `AuthController::login` dan `AssessmentController::submit`). Service saat ini baru digunakan oleh sumber lain (seperti Filament Resources atau Console Command).


ReviewService
assignReviewersToSubmissions
getAssignedSubmissions
calculateVerifiedFinalScore
finalizeReview

RubrikService
getRubrikStructure
getCategoryMetadata
validateRubrikConsistency

SubmissionService
getTaskDetails
saveDraft

bug
ReviewService
verifySingleIndicator

SubmissionService
calculateLivePreview
checkCompletionStatus
lockSubmission

---
## Hasil Pengecekan Routing API (Revisi Needed)

Berdasarkan pengecekan antara `dokumen/api.md` dengan `routes/api.php` dan `AssessmentController`, ditemukan beberapa hal yang perlu direvisi agar rancangan API kita sinkron:

1. **Route Auth Belum Dibuat:**
   - Endpoint `POST /api/auth/register`, `POST /api/auth/login`, dan `POST /api/auth/logout` belum didefinisikan di `routes/api.php`.
   
2. **Route Profil Belum Dibuat:**
   - Endpoint `GET /api/profile`, `POST /api/profile/baseline`, dan `GET /api/profile/status` belum didefinisikan. Di `api.php` baru terdapat komentar `// profil` yang masih kosong.

3. **Inkonsistensi Prefix pada Route Reviewer:**
   - Di `dokumen/api.md`, rute untuk Reviewer menggunakan prefix `/api/review/...`.
   - Di `routes/api.php`, rute tersebut di-*nest* di dalam group `prefix('assessment')`, sehingga endpoint-nya akan diakses melalui `/api/assessment/review/...`.

4. **Struktur Endpoint Reviewer Tidak Sesuai Spesifikasi (REST):**
   - Di dokumen, dicantumkan endpoint:
     - `GET /api/review/submissions`
     - `GET /api/review/submissions/{id}`
     - `PATCH /api/review/answers/{id}`
     - `POST /api/review/publish/{id}`
   - Namun di `api.php`, justru berisi `final-score`, `assign`, `assigned`, `calculate`, `finalize`, `verify-indicator`. Method di controller-nya masih membaca ID dari request (`$request->submission_id`), bukan dari *route parameter* `{id}`.

5. **Route Tidak Terdokumentasi:**
   - Ada banyak rute untuk pengolahan rubrik (contoh: `/assessment/rubrik/structure`, `/assessment/rubrik/validate`) dan tambahan fungsi submission (contoh: `/assessment/draft`, `/assessment/details`, `/assessment/preview-score`) di `routes/api.php` yang **belum dicatat** di `dokumen/api.md`. Sebaiknya dokumentasi dilengkapi atau rute disesuaikan.


assessmentcontroller
profilecontroller
reviewcontroller  

---
## Daftar Lengkap Rute API (Sesuai `routes/api.php` Terkini)

Berikut adalah semua endpoint yang telah didefinisikan di dalam sistem `routes/api.php`, lengkap dengan parameter dan responsnya.

### 1. Auth Endpoint
| Method | Endpoint | Fungsi | Parameter | Response |
|--|--|--|--|--|
| **POST** | `/api/auth/register` | Mendaftarkan akun (awal pengisian data PT / PIC) | `name`, `email`, `password` | `201 Created` (Info pendaftaran sukses) |
REQUEST
- nama perguruan
- jenis perguruan

- nama pic
- jabatan pic
- no hp pic
- email pic
- password pic

RESPONSE
acc login
redirect to dashboard submitter

| **POST** | `/api/auth/login` | Autentikasi user & masuk sesi | `email`, `password` | `200 OK` (Token sesi/User Info) |
REQUEST
- email
- password

RESPONSE
acc login
redirect to dashboard submitter

| **POST** | `/api/auth/logout` | Mengakhiri sesi / logout | - | `200 OK` (Pesan logout sukses) |
REQUEST
- id user

RESPONSE
logout

### 2. Profile Endpoint
| Method | Endpoint | Fungsi | Parameter | Response |
|--|--|--|--|--|
| **GET** | `/api/profile` | Mengambil data base profil user/institusi saat ini | - | `200 OK` (Array Data User/Profil) |
REQUEST
- id user 

RESPONSE
- array object profil
- data relasi institusi user

| **POST** | `/api/profile/baseline` | Menyimpan input data baseline (Mhs, Dosen, dll) | Beragam spesifik fields | `201 Created` (Pesan baseline tersimpan) |
REQUEST
- dokumen legalitas (file), surat pernyataan resmi, sk, sk aipt, profil, logo
- visi
- misi
- jumlah dosen
- jumlah tendik
- jumlah mahasiswa
- jumlah fakultas
- jumlah prodi
- dokumen (file), struktur organisasi PT, SK Tim Pemeringkatan


- jumlah mahasiswa (int)
- jumlah dosen (int)
- detail baseline lainnya

RESPONSE
- info bahwa sukses simpan
- trigger reload status

| **GET** | `/api/profile/status` | Mengecek *status verifikasi* akun dari admin | - | `200 OK` (Data payload status spesifik) |
REQUEST
- (none)

RESPONSE
- payload string 'verifying' atau 'verified'

| UPDATE | /api/profile/update | Mengupdate data profil user/institusi saat ini | - | `200 OK` (Array Data User/Profil) |
REQUEST
- id user 
- data yang ingin diupdate
a. nama PT
b. jenis PT
c. jumlah mahasiswa (int)
d. jumlah fakultas (int)
e. jumlah prodi (int)
f. jumlah dosen (int)
g. jumlah tendik (int)
h. visi
i. misi

j. nama pic
k. jabatan pic
l. no hp pic
m. alamat email

RESPONSE
- array object profil
- notifikasi updated
- data relasi institusi user



### 3. Assessment Endpoint
| Method | Endpoint | Fungsi | Parameter | Response |
|--|--|--|--|--|
ADMIN
|method|endpoint|fungsi|parameter|response|


KATEGORI
|post|admin/filament/kategori/create|membuat kategori baru|parameter|response|
REQUEST
- nama kategori
- deskripsi kategori

RESPONSE
- notifikasi sukses

|get|admin/filament/kategori|melihat daftar kategori|parameter|response|

|get|admin/filament/kategori/{id}|melihat detail kategori|parameter|response|

|update|admin/filament/kategori/update|memperbarui kategori|parameter|response|

|delete|admin/filament/kategori/delete|menghapus kategori|parameter|response|


SUBMITTER


REVIEWER



| **GET** | `/api/assessment/questions` | Mengambil struktur/daftar soal dan daftar rumusan | - | `200 OK` (Array struktur soal `questions`) |
REQUEST
- (none)

RESPONSE
- list kumpulan data soal
- range nilai minimum & max

| **POST** | `/api/assessment/answers` | Menyimpan *submit* jawaban reguler (Klaim/Drive Link) | `answers` array | `200 OK` (Pesan info submit sukses) |
REQUEST
- `answers`: array yang berisi [id_indikator, teks jawaban klaim, rentang_skor klaim opsi terpilih, url_drive link]

RESPONSE
- status flag message ok tersimpan

| **POST** | `/api/assessment/submit` | Final Lock/Submit untuk mengakhiri edit *Assessment* | `submission_id` | `200 OK` (Pesan info locked) |
REQUEST
- submission_id (unik identifikasi form yg dikerjakan)

RESPONSE
- status kunci form
- merubah step verifikasi

| **GET** | `/api/assessment/preview` | Mengambil hitungan poin (ranged score) kasar | `submission_id` | `200 OK` (Preview nilai kasar) |
REQUEST
- submission_id

RESPONSE
- preview angka (range 1 to 5 metrics)

| **GET** | `/api/assessment/rubrik/structure` | *Service:* Mengambil patokan *structure* kriteria Rubrik | - | `200 OK` (Array hierarki kriteria) |
REQUEST
- (none)

RESPONSE
- array tree rubrik (parent to childs)

| **GET** | `/api/assessment/rubrik/metadata` | *Service:* Mengambil *metadata* batasan nilai | - | `200 OK` (Data metadata nilai) |
REQUEST
- (none)

RESPONSE
- object config batasan skor (metadata referensi)

| **GET** | `/api/assessment/rubrik/validate` | *Service:* Validasi utuh *consistency* perhitungan rubrik | - | `200 OK` (Pesan lulus konsistensi 100%) |
REQUEST
- (none)

RESPONSE
- boolean isValid
- logs pesan validasi rubrik sehat (%)

| **GET** | `/api/assessment/details` | *Service:* Detail Task lengkap per `submission_id` | `submission_id` | `200 OK` (Data submission complete) |
REQUEST
- submission_id

RESPONSE
- seluruh json row object submission

| **GET** | `/api/assessment/preview-score` | *Service:* Detail Preview Ranged Score secara detail | `submission_id` | `200 OK` (Score Preview Lengkap) |
REQUEST
- submission_id

RESPONSE
- array perhitungan per section kriteria
- output poin angka yang rapi (lengkap)

| **POST** | `/api/assessment/draft` | Menyimpan progres jawaban kuesioner tanpa menge-lock | `answers` array | `200 OK` (Pesan draft tersimpan aman) |
REQUEST
- variabel `answers` berupa array (yang baru sebatas diisi sebagian formnya doang)

RESPONSE
- status message tersimpan dengan aman
- tanpa melakukan penguncian (belum final submit)

### 4. Reviewer Endpoint
| Method | Endpoint | Fungsi | Parameter | Response |
|--|--|--|--|--|
| **GET** | `/api/review/submissions` | Menampilkan seluruh *List Final Submit* (Untuk dikerjakan / disupervisi by Admin) | - | `200 OK` (Array List *Submissions*) |
REQUEST
- (none)

RESPONSE
- list dari institusi yang form-nya sudah Submitted & locked (siap dinilai oleh admin penilai)

| **GET** | `/api/review/submissions/{id}` | Mengambil detail spesifik isi klaim/jawaban milik parameter id | `id` (Di URL Param) | `200 OK` (Data spesifik *Submission* yang akan dinilai) |
REQUEST
- id target institusinya / param dari route

RESPONSE
- seluruh detail row jawaban dari satu target institusi tersebut secara penuh

| **PATCH** | `/api/review/answers/{id}` | *Reviewer* mencocokan / verifikasi *Verdict* dan mengubah skor kuesioner spesifik | `id` (Param), `verdict` / angka desimal | `200 OK` (Pesan Update *Verdict* & kalkulasi re-trigger) |
REQUEST
- id target indikator yang dijawabnya (param id)
- payload field angka verifikasi/verdict score

RESPONSE
- confirmation success status bahwa nilai spesifik ini sudah dicatat sah

| **POST** | `/api/review/publish/{id}` | Publikasikan nilai secara final sehinggal user melihat nilainya | `id` (Di URL Param) | `200 OK` (Pesan publikasi sukses) |
REQUEST
- url id -> submission target

RESPONSE
- publish status string
- pesan "published to users"

| **GET** | `/api/review/final-score` | *Legacy:* Mengambil score yg telah selesai terverifikasi total | `submission_id` | `200 OK` (Pesan detail nilai verified total) |
REQUEST
- submission_id di query get params

RESPONSE
- point object total / skor review admin

| **POST** | `/api/review/assign` | *Legacy:* Pendelegasian (*Assignment*) reviewer untuk submission ini | `reviewer_id`, `submission_ids` array | `200 OK` (Pesan info assignment berhasil) |
REQUEST
- reviewer_id -> id pengawas / staff 
- arrays submission_ids -> beberapa target universitas yang dinilainya

RESPONSE
- info assignment delegate sukses
- return 200

| **GET** | `/api/review/assigned` | *Legacy:* Mengambil list semua entri yang *Assigned* ke reviewer ini | `reviewer_id` | `200 OK` (List of Penugasan) |
REQUEST
- id staff di param request 

RESPONSE
- kumpulan institusi pt yg harus dia review 

| **POST** | `/api/review/calculate` | *Legacy:* Men-trigger kalkulasi *manual* ulang untuk poin yg telah dicek reviewer | `verified_answers` array, `metadata` | `200 OK` (Nilai hasil validasi kalkulasi ulang) |
REQUEST
- verified_answers berupa array validasi (angka point)
- configs metadata

RESPONSE
- raw point calculation

| **POST** | `/api/review/finalize` | *Legacy:* Menyatakan bahwa tahap Review final 100% dan dinonaktifkan proses edit review-nya | `submission_id` | `200 OK` (Review dikunci & dinyatakan rampung final) |
REQUEST
- target submission_id

RESPONSE
- locked status "telah selesai direview sepenuhnya"

| **PATCH** | `/api/review/verify-indicator`| *Legacy:* Verifikasi klaim per-1 indikator detail soal tunggal | `submission_id`, `indicator_id`, `verified_score`, opsi `notes` | `200 OK` (Sukses Verifikasi Single Indicator) |
REQUEST
- submission_id spesifik 
- target indicator_id 
- poin terverifikasi (verified_score)
- komentar notes reviewer (opsional)

RESPONSE
- indikator telah ditandai success



FIXED
1. Submitter Endpoints 
Tahap 1: Autentikasi & Registrasi (The Onboarding)

### 🟢 Tahap 1: Autentikasi & Registrasi (The Onboarding)

#### 1. Register Account & Profile
- **Endpoint**: `POST /api/auth/register`
- **Kegunaan**: Pendaftaran akun PIC dan profil institusi (Step 1).
- **Alur Logika**: 
  - `Route` ➔ `Controller`: Menerima form pendaftaran.
  - `Controller` ➔ `DTO`: `RegisterDTO` (Email, Password, Data Institusi).
  - `DTO` ➔ `Service`: Membuat User & Institusi (Status: `PENDING`).
  - `Service` ➔ `Repository`: `UserRepository@create` & `InstitutionRepository@create`.
- **Penting**: Pastikan email unik dan gunakan `DB::transaction`.

#### 2. Login System
- **Endpoint**: `POST /api/auth/login`
- **Kegunaan**: Masuk ke sistem.
- **Alur Logika**: 
  - `Route` ➔ `Controller`: Input Email & Password.
  - `Controller` ➔ `DTO`: `LoginDTO`.
  - `DTO` ➔ `Service`: Validasi kredensial & cek status institusi.
  - `Service` ➔ `Repository`: `UserRepository@getByEmail`.
- **Output**: Mengembalikan Token (Sanctum) & Status tahap pengerjaan user.

---

### 🔵 Tahap 2: Baseline Data (The Foundation)

#### 3. Input Baseline Data
- **Endpoint**: `POST /api/profile/baseline`
- **Kegunaan**: Input data statistik mahasiswa/dosen (Step 2).
- **Alur Logika**: 
  - `Route` ➔ `Controller`: Data baseline + dokumen pendukung.
  - `Controller` ➔ `DTO`: `BaselineDTO`.
  - `DTO` ➔ `Service`: Cek verifikasi Step 1, update/create data, set status ke `VERIFYING_BASE`.
  - `Service` ➔ `Repository`: `IdentityBaselineRepository@updateOrCreate`.
- **Penting**: Dokumen disimpan dalam format JSON. Data **tidak boleh diedit** jika status sudah `ACTIVE`.

---

### 🟡 Tahap 3: Assessment Rubrik (The Core)

#### 4. Stepper Navigation
- **Endpoint**: `GET /api/submitter/steps`
- **Kegunaan**: Mengambil navigasi Stepper & cek izin akses rubrik.
- **Gatekeeper**: Jika `is_verified` masih `false`, lempar `403 Forbidden`.
- **Service**: `SubmitterService@getStepperProgress` ➔ `CategoryRepository@getAllWithProgress`.
- **Indikator**: Menghitung `questions_count` vs `answers_count`.

#### 5. Get Questions by Category
- **Endpoint**: `GET /api/submitter/questions/{cat_id}`
- **Kegunaan**: Mengambil soal per kategori + jawaban yang sudah ada (Pre-fill).
- **Service**: `SubmitterService@getQuestionsWithAnswers` ➔ `QuestionRepository@getByCategoryWithExistingAnswers`.
- **Penting**: Pastikan opsi pilihan ganda ter-load.

#### 6. Save Progress (Atomic Save)
- **Endpoint**: `POST /api/submitter/save-progress`
- **Kegunaan**: Simpan jawaban otomatis saat navigasi (klik "Next").
- **Payload**: `Array answers` (Indicator ID, Claim Value, Evidence URL).
- **Logic**: Menggunakan `updateOrCreate` untuk menangani pembaruan jawaban.

#### 7. Category Preview
- **Endpoint**: `GET /api/submitter/preview-category/{cat_id}`
- **Kegunaan**: Melihat estimasi skor (skala 1-5) sementara.
- **Scoring**: Menghitung total poin klaim pada kategori tertentu.

---

### 🔴 Tahap 4: Finalisasi & Hasil

#### 8. Final Lock Assessment
- **Endpoint**: `POST /api/submitter/finalize`
- **Kegunaan**: Mengunci seluruh asesmen.
- **Validasi**: Zero-Gap Check (Cek apakah semua indikator sudah terjawab).
- **Effect**: Status berubah menjadi `SUBMITTED`. Setelah ini, semua akses tulis (POST/PUT) akan di-reject (`403`).

#### 9. Published Results
- **Endpoint**: `GET /api/submitter/results`
- **Kegunaan**: Melihat hasil akhir setelah diverifikasi Reviewer & Admin.
- **Logic**: Hanya menampilkan data jika status sudah `PUBLISHED`.
- **Detail**: Menampilkan rincian `manual_score` dan catatan dari Reviewer.

Contoh Request & Response (Submitter)

GET /api/submitter/questions/1
RESPONSE:

{
  "status": "success",
  "data": {
    "category_name": "Variabel A: Kebijakan",
    "questions": [
      {
        "id": "q1",
        "text": "Apakah terdapat SK Rektor terkait Bela Negara?",
        "options": [
          {"value": 0, "label": "Tidak Ada"},
          {"value": 5, "label": "Ada dan Terpublikasi"}
        ],
        "existing_answer": {
          "claim_value": "5",
          "evidence_url": "[https://drive.google.com/](https://drive.google.com/)..."
        }
      }
    ]
  }
}


POST /api/submitter/save-progress
REQUEST:

{
  "category_id": 1,
  "answers": [
    {
      "indicator_id": "q1",
      "claim_value": "5",
      "evidence_url": "[https://drive.google.com/](https://drive.google.com/)..."
    }
  ]
}



2. Reviewer Endpoints (Auditor/Penilai)

Fokus: Memverifikasi klaim vs bukti dan memberikan skor akhir per kategori.


GET /api/reviewer/assignments Menampilkan daftar Institusi yang telah Final Submit dan diplot ke Reviewer ini.

GET /api/reviewer/questions/{sub_id}/{cat_id} Mengambil data klaim submitter vs kolom input verifikasi reviewer.

POST /api/reviewer/save-verification Atomic Verify: Menyimpan hasil verifikasi (Skala 1-5 & Skor Manual).

POST /api/reviewer/finalize/{sub_id} Review Lock: Menyatakan penilaian untuk institusi tersebut selesai.

Contoh Request & Response (Reviewer)

GET /api/reviewer/questions/sub-001/1
RESPONSE:

{
  "status": "success",
  "data": {
    "institution_name": "Universitas Merdeka",
    "questions": [
      {
        "id": "q1",
        "submitter_claim": "5",
        "evidence_url": "[https://drive.google.com/](https://drive.google.com/)...",
        "reviewer_guideline": "Skala 5 jika dokumen SK lengkap dan bertanda tangan.",
        "existing_verification": {
          "scale_choice": 4,
          "manual_score": 80.00
        }
      }
    ]
  }
}


POST /api/reviewer/save-verification
REQUEST:

{
  "submission_id": "sub-001",
  "category_id": 1,
  "verifications": [
    {
      "indicator_id": "q1",
      "scale_choice": 5,
      "manual_score": 100.00,
      "note": "Dokumen sangat lengkap."
    }
  ]
}



oke oke, dimulai dari fase daftar dan verifikasi terlebih dahulu
1. submitter daftar di web, mengisi nama & jenis PT, data nama, jabatan, no hp, email, password => status user akan menjadi PENDING_REGISTRATION
2. verifikasi via admin untuk approve akun submitter => status user akan menjadi PENDING_BASELINE
3. submitter dapat login, lalu mengisi baseline, lalu status user akan berubah menjadi ACTIVE
4. submitter dapat mengisi assessment penilaian
5. submitter dapat mengerjakan assesment penilaian lalu klik simpan / next untuk menyimpan sementara
6. ketika submitter sudah mengisi semua, maka klik button finalize / selesai untuk lock jawabannya (jawabannya tidak bisa dirubah) namun bisa di preview nilai sementara oleh user
7. setelah itu reviewer akan menilai(TBD)


POST /api/auth/register 
POST /api/auth/login 
POST /api/profile/baseline 
GET /api/submitter/steps
GET /api/submitter/{cat_id}
POST /api/submitter/save-progress
GET /api/submitter/preview-category/{cat_id}
POST /api/submitter/finalize
GET /api/submitter/results