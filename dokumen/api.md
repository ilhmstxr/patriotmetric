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
