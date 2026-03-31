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