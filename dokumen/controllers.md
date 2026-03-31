# Analisis dan Best Practice Penggunaan Controller

Berdasarkan pengecekan pada `routes/web.php` dan direktori `app/Http/Controllers`, saat ini terdapat **4 Controller baru** yang aktif digunakan untuk melayani route API aplikasi:
1. `AuthController` - Digunakan oleh **Semua Role** (secara umum, Guest) untuk proses registrasi dan **Single-Device Login** / Logout sistem.
2. `ProfileController` - Digunakan oleh **Role Institusi (Peserta)** untuk mengecek status verifikasi oleh institusi (Admin Pusat) dan melengkapi pengisian *form baseline*.
3. `AssessmentController` - Digunakan oleh **Role Institusi (Peserta)** sebagai jalur utama pengerjaan instrumen metrik (menarik soal, menyimpan jawaban sementara berserta *link drive* bukti klaim, hingga melakukan final *submit*).
4. `ReviewController` - Digunakan oleh **Role Reviewer dan Admin Pusat** dalam melakukan penjurian silang (melihat *submission*, memberikan skor validasi secara *granular*, hingga Admin Pusat mempublikasikan hasil akhir).

Namun, ditemukan beberapa **Controller lama yang sudah tidak terpakai** (tidak direferensikan di *route* manapun):
1. `KategoriController.php` => Tergantikan oleh `AssessmentController`. Pengambilan hierarki soal dan kategori kini kemungkinan besar disatukan dalam satu endpoint _assessment questions_ agar lebih efisien.
2. `PengaturanCmsController.php` => role:admin, tetap diperlukan karena mengatur bagaimana cara kerja cms didepannya.
3. `PengumpulanController.php` => Tergantikan oleh `AssessmentController` (untuk fitur *Final Submit* oleh Institusi) dan `ReviewController` (untuk fitur melihat daftar *submissions* oleh Reviewer).
4. `PengumpulanJawabanController.php` => Tergantikan oleh `AssessmentController` (untuk fitur menyimpan jawaban/klaim oleh Institusi) dan `ReviewController` (untuk fitur *verdict*/penjurian jawaban).
5. `PertanyaanController.php` => Tergantikan oleh `AssessmentController` karena logika penarikan daftar pertanyaan ujian difokuskan pada controller untuk asesmen.

## Best Practice Pemanfaatan Controller

Untuk menjaga kebersihan *codebase* dan mempermudah pemeliharaan (maintenance) aplikasi ke depannya, berikut merupakan *best practice* yang dapat diterapkan terkait controller dalam proyek Laravel ini:

### 1. Hapus Controller yang Tidak Terpakai (Dead Code Elimination)
Controller yang tidak lagi digunakan (seperti 5 controller lama di atas) sangat disarankan untuk **dihapus**. Menyimpan kode usang (*dead code*) dapat menimbulkan kebingungan dan membebani *mental load* saat mencari *bug* atau menambah fitur baru. Jika khawatir kehilangan kodenya, Git (Version Control) sudah mencatat histori lengkapnya sehingga selalu bisa dikembalikan kapan pun dibutuhkan.

### 2. Terapkan Single Responsibility Principle (SRP)
Pastikan setiap controller hanya menangani tugas yang spesifik terkait entitasnya. Controller tidak boleh tahu cara detail memproses data; ia hanya menerima `Request` HTTP, mengopernya ke lapisan *Service*/Action, lalu mengembalikan JSON `Response` atau *View*. ("Thin Controllers, Fat Services").

### 3. Ekstraksi Logika Bisnis Kompleks ke Service Layer
Sebagai kelanjutan SRP, jangan menulis query database yang panjang atau perhitungan yang rumit secara langsung di dalam Controller. Serahkan ke Service (misal `SubmissionService.php`) sehingga logika bisnis bisa diuji (Unit Test) secara mandiri tanpa harus melewati Controller.

### 4. Struktur Direktori dan Namespace yang Rapih
Saat ini ke-4 controller baru dilayani di dalam `Route::prefix('api')`. Sangat direkomendasikan untuk memindahkan controller khusus API ke dalam direktori/namespace `App\Http\Controllers\Api\*`. Hal ini agar ke depan, jika aplikasi bertambah besar dan memiliki respon yang berbeda untuk Web UI (Blade) dan API (JSON), *codebase* tidak tercampur.

### 5. Gunakan Resource & Invokable Controller
- Jika Controller beroperasi utuh selayaknya CRUD, gunakan implementasi **Resource Controller** (`Route::apiResource`).
- Jika sebuah aksi sangat spesifik atau besar (seperti Final Submission), sangat dianjurkan memecahnya menjadi **Invokable Controller** (menggunakan fungsi `__invoke`) agar tidak membuat sebuah controller membengkak dengan puluhan method.

---

**Saran Tindakan Cepat (Actionable Steps):**
1. Hapus 5 file Controller lama yang sudah tidak terpakai.
2. (*Opsional*) Jika proyek diubah menjadi *API-Centric*, pindahkan 4 Controller baru tersebut ke dalam folder `app/Http/Controllers/Api` dan perbarui *namespace*-nya.

=> role:admin, tetap diperlukan karena mengatur bagaimana cara kerja cms didepannya