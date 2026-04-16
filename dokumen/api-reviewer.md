# 1. GET /api/reviewer/assignments
CHECK
**Kegunaan:** Menampilkan daftar Institusi yang telah Final Submit dan ditugaskan ke Reviewer tersebut.

### Route -> Controller
* **Route:** `GET /api/reviewer/assignments` mengarah ke `ReviewerController@getAssignments`.
* **Logic:** Mengambil `reviewer_id` dari session/token yang aktif.

### Controller -> DTO
Membuat `ReviewerContextDTO` (berisi `reviewer_id`).

### DTO -> Service
Memanggil `ReviewerService@getAssignedInstitutions(ReviewerContextDTO $dto)`.

### Business Logic
Memfilter assessments yang memiliki status `SUBMITTED` atau `REVIEWING` dan sesuai dengan plotting ID Reviewer.

### Service -> Repository
Memanggil `AssessmentRepository@getByReviewer($reviewerId)`.

**Eloquent:**
```php
Assessment::where('reviewer_id', $id)->whereIn('status', ['submitted', 'reviewing'])->with('institution')->get().
```

### Indikator Detail Implementasi
* **Plotting Check:** Pastikan ada tabel pivot atau kolom `reviewer_id` di tabel assessments.
* **Status Filter:** Reviewer dilarang melihat data yang masih berstatus `DRAFT` (milik submitter).

---

# 2. GET /api/reviewer/questions/{sub_id}/{cat_id}
CHECK 
**Kegunaan:** Mengambil data perbandingan (Klaim Submitter vs Input Reviewer) per kategori.

### Route -> Controller
* Mengarah ke `ReviewerController@getQuestionsByCategory`.
* Menerima `submission_id` (ID Assessment) dan `category_id`.

### Controller -> DTO
Membuat `ReviewCategoryRequestDTO`.

### DTO -> Service
Memanggil `ReviewerService@getComparisonData(ReviewCategoryRequestDTO $dto)`.

### Business Logic
Mengambil soal, jawaban submitter (opsi & link), serta data verifikasi reviewer yang mungkin sudah dicicil sebelumnya.

### Service -> Repository
Memanggil `AssessmentAnswerRepository@getWithReviewerContext($subId, $catId)`.

**Eloquent:**
```php
AssessmentAnswer::where('assessment_id', $subId)->whereHas('question', fn($q) => $q->where('category_id', $catId))->with('question.options')->get().
```

### Indikator Detail Implementasi
* **Side-by-Side Data:** Response harus menyertakan `claim_value` and `evidence_url` dari submitter secara utuh.
* **Guideline Display:** Sertakan teks rubrik (skala 1-5) dari tabel pertanyaans sebagai panduan di UI Reviewer.

---

# 3. POST /api/reviewer/save-verification
CHECK
**Kegunaan:** Menyimpan hasil verifikasi (Pilihan Skala & Skor Manual) per kategori.

### Route -> Controller
* Mengarah ke `ReviewerController@saveVerification`.
* **Validation:** `SaveVerificationRequest` memvalidasi `scale_choice` (1-5) and `manual_score` (0-100).

### Controller -> DTO
Mentransformasi payload menjadi `VerificationProgressDTO`.

### DTO -> Service
Memanggil `ReviewerService@persistVerification(VerificationProgressDTO $dto)`.

### Business Logic
* - Update status asesmen menjadi `REVIEWING`.
* Validasi apakah `manual_score` berada dalam batas wajar sesuai `scale_choice`.

### Service -> Repository
Memanggil `AssessmentAnswerRepository@updateReviewData(array $data)`.

**Eloquent:**
```php
AssessmentAnswer::where('id', $id)->update(['reviewer_scale' => $scale, 'manual_score' => $score, 'verified_at' => now()]).
```

### Indikator Detail Implementasi
* **Audit Trail:** Simpan timestamp `verified_at` setiap kali reviewer melakukan perubahan per indikator.
* **Manual Input Overrule:** Ingat, `manual_score` adalah kebenaran mutlak (final score) meskipun sistem memberikan rekomendasi berdasarkan `scale_choice`.

---

# 4. POST /api/reviewer/finalize/{sub_id}
CHECK
**Kegunaan:** Mengunci proses penilaian untuk satu institusi.

### Route -> Controller
Mengarah ke `ReviewerController@finalizeReview`.

### Controller -> DTO
Membuat `FinalizeReviewDTO`.

### DTO -> Service
Memanggil `ReviewerService@lockReview(FinalizeReviewDTO $dto)`.

### Business Logic
* - Pengecekan apakah SEMUA indikator sudah diberikan `manual_score`.
* Hitung total nilai akhir (weighted average) dari seluruh kategori.

### Service -> Repository
Memanggil `AssessmentRepository@updateStatus($id, 'REVIEWED')`.

**Eloquent:**
```php
Assessment::where('id', $id)->update(['status' => 'reviewed', 'final_score' => $calculatedTotal]).
```

### Indikator Detail Implementasi
* **Zero-Gap Validation:** Jika ada satu saja soal yang belum di-verifikasi, gagalkan proses finalize.
* **Scoring Calculation:** Kalkulasi dilakukan di level Service sebelum disimpan ke kolom `final_score` di tabel assessments.

---

# 5. POST /api/admin/publish/{sub_id}

**Kegunaan:** Tahap akhir oleh Admin untuk merilis nilai ke Submitter.

### Route -> Controller -> DTO -> Service
Sama dengan alur di atas, namun mengubah status menjadi `PUBLISHED`.

### Service -> Repository
```php
Assessment::where('id', $id)->update(['status' => 'published']).
```

### Indikator Detail Implementasi
* **Visibility Trigger:** Sebelum status ini `PUBLISHED`, API Submitter (/results) tidak boleh menampilkan skor akhir.

---

# Ringkasan Alur Reviewer

* **Validation of Evidence:** Reviewer bertindak sebagai verifikator atas klaim Submitter. Fokus utama adalah mencocokkan `evidence_url` dengan `claim_value`.
* **Hybrid Scoring:** Sistem mendukung pilihan skala (1-5) untuk kemudahan, namun tetap memberikan kebebasan `manual_score` untuk akurasi desimal.
* **Progressive Locking:** Status `REVIEWING` otomatis terpicu saat verifikasi pertama disimpan, menandakan institusi tersebut sedang dalam proses audit.
* **Integrity Gate:** Finalize reviewer adalah titik di mana nilai tidak bisa diubah lagi oleh Reviewer, memberikan kepastian data bagi Admin Pusat.


api-submitter.md#L131-160
 generate insruksi ini ke dalam file 

ReviewController.php

ReviewDTO.php

ReviewService.php

ReviewRepository.php
dengan konsep controller -> dto -> service -> repo