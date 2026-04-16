# 1. GET /api/submitter/steps
CHECK 

**Kegunaan:** Mengambil daftar kategori (Stepper) dan status progres pengisian.

### Route -> Controller
* **Route:** `GET /api/submitter/steps` mengarah ke `SubmitterAssessmentController@getSteps`.
* **Logic:** Mengidentifikasi `user_id` yang aktif dan mencari `assessment_id` untuk periode berjalan.

### Controller -> DTO
Controller membuat `AssessmentContextDTO` yang berisi `assessment_id` dan `user_id`.

### DTO -> Service
Memanggil `SubmitterAssessmentService@getStepperProgress(AssessmentContextDTO $dto)`.

### Business Logic
Mengambil semua kategori, lalu menghitung perbandingan jumlah soal vs jumlah jawaban yang sudah di-submit untuk setiap kategori.

### Service -> Repository
Memanggil `CategoryRepository@getAllWithProgress($assessmentId)`.

**Eloquent:**
```php
Category::withCount(['questions', 'answers' => fn($q) => $q->where('assessment_id', $id)])->get().
```

### Indikator Detail Implementasi
* **Auth Check:** Pastikan middleware `auth:sanctum` atau sejenisnya aktif untuk mendapatkan `Auth::id()`.
* **Active Period:** Query `assessment_id` harus memfilter berdasarkan tahun saat ini dan status yang belum dipublikasikan.
* **Progress Calculation:** Hasil `withCount` harus dikonversi menjadi persentase atau status (misal: `completed` jika `questions_count == answers_count`).

---

# 2. GET /api/submitter/questions/{cat_id}
CHECK 

**Kegunaan:** Mengambil soal kategori tertentu beserta jawaban yang sudah tersimpan (pre-filled).

### Route -> Controller
* Mengarah ke `SubmitterAssessmentController@getQuestionsByCategory`.
* Menerima parameter `{cat_id}`.

### Controller -> DTO
Membuat `CategoryRequestDTO` (berisi `category_id` dan `assessment_id`).

### DTO -> Service
Memanggil `SubmitterAssessmentService@getQuestionsWithAnswers(CategoryRequestDTO $dto)`.

### Business Logic
Memastikan kategori valid dan mengambil relasi soal beserta opsi jawabannya.

### Service -> Repository
Memanggil `QuestionRepository@getByCategoryWithExistingAnswers($catId, $assessmentId)`.

**Eloquent:**
```php
Question::where('category_id', $catId)->with(['options', 'answers' => fn($q) => $q->where('assessment_id', $id)])->get().
```

### Indikator Detail Implementasi
* **Pre-filling Data:** Pastikan `existing_answer` disertakan dalam response agar UI tidak kosong saat user kembali ke kategori sebelumnya.
* **Resource Mapping:** Gunakan Laravel API Resource untuk memformat `options` (pilihan ganda) agar mudah di-render di sisi frontend.
* **Category Validation:** Jika `cat_id` tidak ditemukan, kembalikan response `404 Not Found`.

---

# 3. POST /api/submitter/save-progress
CHECK
**Kegunaan:** Menyimpan jawaban (Klaim & Bukti) per kategori (Atomic Save).

### Route -> Controller
* Mengarah ke `SubmitterAssessmentController@saveProgress`.
* **Validation:** `SaveProgressRequest` memvalidasi `category_id`, array `answers`, dan format `evidence_url`.

### Controller -> DTO
Mentransformasi request body menjadi `SubmitterProgressDTO`.

### DTO -> Service
Memanggil `SubmitterAssessmentService@persistProgress(SubmitterProgressDTO $dto)`.

### Business Logic
* * Cek status asesmen (Jika sudah finalize, lempar `403 Forbidden`).
* Melakukan pembersihan data (`Sanitization`) pada URL bukti.
* Menyiapkan data untuk operasi mass insert/update.

### Service -> Repository
Memanggil `AssessmentAnswerRepository@upsertAnswers(array $data)`.

**Eloquent:**
```php
DB::transaction menjalankan AssessmentAnswer::updateOrCreate untuk setiap indicator_id dalam array.
```

### Indikator Detail Implementasi
* **Atomic Persistence:** Gunakan `DB::beginTransaction()` and `DB::commit()` untuk menjamin jika satu jawaban gagal, seluruh progress kategori tersebut tidak rusak.
* **URL Validation:** Tambahkan validasi Regex pada `evidence_url` untuk memastikan input berupa link Google Drive yang valid.
* **Update or Create:** Pastikan `indicator_id + assessment_id` menjadi kunci penentu apakah data akan di-update atau dibuat baru.

---

# 4. GET /api/submitter/preview-category/{cat_id}
CHECK
**Kegunaan:** Melihat estimasi skor kasar khusus untuk kategori yang baru diselesaikan.

### Route -> Controller
Mengarah ke `SubmitterAssessmentController@getCategoryPreview.

### Controller -> DTO
Membuat `PreviewRequestDTO` (berisi `assessment_id` dan `category_id`).

### DTO -> Service
Memanggil `ScoringService@calculateCategoryPreview(PreviewRequestDTO $dto)`.

### Business Logic
Mengambil semua `claim_value` di kategori tersebut, menjumlahkan berdasarkan bobot, dan mengonversi ke skala 1-5.

### Service -> Repository
Memanggil `AssessmentAnswerRepository@getAnswersByCategory($assessmentId, $catId)`.

**Eloquent:**
```php
AssessmentAnswer::whereHas('question', fn($q) => $q->where('category_id', $catId))->get().
```

### Indikator Detail Implementasi
* **Temporary Score:** Skor ini bersifat estimasi. Berikan labeling pada response seperti "Estimasi Skala 4".
* **Weighting Logic:** Pastikan Service memiliki akses ke bobot masing-masing kategori agar hitungan akurat sesuai rubrik.

---

# 5. POST /api/submitter/finalize
CHECK
**Kegunaan:** Mengunci seluruh data kuesioner (Final Lock).

### Route -> Controller
Mengarah ke `SubmitterAssessmentController@finalize.

### Controller -> DTO
Membuat `FinalizeAssessmentDTO` (berisi `assessment_id`).

### DTO -> Service
Memanggil `SubmitterAssessmentService@lockAssessment(FinalizeAssessmentDTO $dto)`.

### Business Logic
* * Validasi kelengkapan: Cek apakah ada soal yang belum terjawab di semua kategori.
* Jika belum lengkap, lempar `422 Unprocessable Entity`.
* Jika lengkap, ubah status assessments menjadi `SUBMITTED`.

### Service -> Repository
Memanggil `AssessmentRepository@updateStatus($id, 'SUBMITTED')`.

**Eloquent:**
```php
Assessment::where('id', $id)->update(['status' => 'submitted', 'submitted_at' => now()]).
```

### Indikator Detail Implementasi
* **Completeness Check:** Lakukan pengecekan silang antara total pertanyaan di database dengan total entri di tabel `assessment_answers`.
* **Immutable State:** Setelah status menjadi `SUBMITTED`, pastikan semua API POST (save) untuk `assessment_id` tersebut mengembalikan error agar data tidak bisa dimanipulasi lagi.
* **Timestamping:** Simpan waktu penguncian di kolom `submitted_at` sebagai bukti audit waktu pengerjaan.