1. Trait: CalculatesRubrikScore
resolveIndicatorScore(): Menentukan skor (0-5) berdasarkan tipe input (statis, kuantitas x skala, atau persentase).

mapPercentageToScore(): Mengonversi angka persentase mentah ke dalam skala skor 0-5 berdasarkan ambang batas (threshold) kategori terkait.

calculateCategorySubtotal(): Menghitung total skor mentah dalam satu kategori tertentu (misal: total skor Kebijakan).

applyCategoryWeight(): Mengalikan subtotal skor kategori dengan bobot dinamis yang diambil dari database.

compareWithInstitutionalBenchmarks(): Logika perbandingan jumlah mahasiswa terhadap jumlah Prodi dan Fakultas untuk indikator spesifik.

2. RubrikService
getRubrikStructure(): Mengambil seluruh kategori beserta indikator dan opsi jawabannya secara nested untuk kebutuhan render accordion di React.

getCategoryMetadata(): Mengambil informasi bobot dan jumlah indikator aktif per kategori langsung dari database.

validateRubrikConsistency(): Memastikan total bobot seluruh kategori mencapai 100% sebelum sistem dibuka untuk pengumpulan.

3. SubmissionService (Pengumpulan & Jawaban)
getTaskDetails(): Mengambil data pertanyaan sekaligus jawaban yang sudah diisi oleh peserta untuk ditampilkan kembali pada form.

saveDraft(): Menyimpan jawaban secara masal (bulk) ke dalam tabel jawaban setiap kali terjadi autosave di frontend.

calculateLivePreview(): Menggunakan CalculatesRubrikScore untuk memberikan estimasi skor real-time kepada peserta sebelum data dikunci.

checkCompletionStatus(): Memvalidasi apakah semua indikator wajib dan tautan bukti (link drive) sudah terisi.

lockSubmission(): Melakukan finalisasi, menghitung skor self-assessment akhir, dan mengubah status menjadi LOCKED (mengunci akses edit).

4. ReviewService (Validasi & Plotting)
assignReviewersToSubmissions(): Melakukan plotting atau pembagian jatah peserta kepada reviewer tertentu.

getAssignedSubmissions(): Menampilkan daftar peserta yang harus dikoreksi oleh reviewer yang sedang login.

verifySingleIndicator(): Menyimpan hasil verifikasi skor dan catatan perbaikan untuk satu indikator tertentu.

calculateVerifiedFinalScore(): Menggunakan CalculatesRubrikScore untuk menghitung skor total berdasarkan angka yang telah divalidasi oleh reviewer.

finalizeReview(): Memastikan semua indikator telah diperiksa, menyimpan skor akhir hasil verifikasi, dan mengubah status menjadi REVIEWED.