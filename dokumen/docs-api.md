POST /api/baseline/{userId}

contoh = http://127.0.0.1:8000/api/baseline/{userId}

cara panggil = ('api.peserta.baseline')

request
int $userId;
string $namaInstitusi;
string $jenisInstitusi;
string $namaPic;
string $jabatanPic;
string $noHpPic;

     int $jmlMhs;
     int $jmlDosen;
     int $jmlTendik;
     int $jmlProdi;
     int $jmlUkm;
     int $jmlFakultas;

     string $visi;
     string $misi;
     array $legalDocuments;

response
{
"success": true,
"message": "Data baseline berhasil disimpan",
"data": null
}

GET /api/assessment/peserta/questions/{assessmentId}

contoh =
http://127.0.0.1:8000/api/assessment/peserta/questions/{assessmentId}
test api = http://127.0.0.1:8000/api/assessment/peserta/questions/1

cara panggil = ('api.peserta.questions')

request
int $userId

contoh response
{
"success": true,
"message": "Data seluruh soal dan jawaban tersimpan berhasil diambil",
"data": [
{
"id": 1,
"kode_pertanyaan": "A.1",
"category_id": 1,
"teks_pertanyaan": "A.1. Kebijakan tentang Implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma dan Penunjang",
"deskripsi": "dummy - deskripsi",
"kebutuhan_bukti": "Bukti A.1: SK rektor, ST, laporan kegiatan, dokumentasi",
"tipe": "pilihan_ganda",
"skor_maksimal": 0,
"created_at": "2026-04-21T22:38:39.000000Z",
"updated_at": "2026-04-21T22:38:39.000000Z",
"kategori": {
"id": 1,
"nama_kategori": "A. VARIABEL PATRIOTISME KEBIJAKAN",
"deskripsi": null,
"created_at": null,
"updated_at": null
},
"opsi_jawabans": [
{
"id": 1,
"pertanyaan_id": 1,
"opsi_jawaban": "0",
"value": null,
"keterangan": "Tidak ada",
"created_at": "2026-04-21T22:38:39.000000Z",
"updated_at": "2026-04-21T22:38:39.000000Z"
}
]
}
]
}

POST /api/assessment/peserta/finalize/{assessmentID}

contoh =
http://127.0.0.1:8000/api/assessment/peserta/finalize/{assessmentID}
test api = http:/127.0.0.1:8000/api/assessment/peserta/finalize/1

cara panggil = ('api.peserta.finalize')

request
int $userId

response
{
"success": true,
"message": "Data baseline berhasil disimpan",
"data": null
}

GET /api/assessment/peserta/preview-results/{assessmentId}

contoh =
http://127.0.0.1:8000//api/assessment/peserta/preview-results/{assessmentId}
test api = http:/127.0.0.1:8000/api/assessment/peserta/preview-results/2

cara panggil = ('api.peserta.preview-results')

request

int $userId

{
"success": true,
"message": "Estimasi skor total berhasil dihitung",
"data": {
"estimated_score": 90,
"label": "Estimasi Skor Kasar",
"details": "Skor ini bersifat estimasi sebelum verifikasi Reviewer."
}
}

POST /api/assessment/peserta/save-answer/{userId}

contoh =
http:/127.0.0.1:8000/api/assessment/peserta/save-answer/{userId}
test api =
http:/127.0.0.1:8000/api/assessment/peserta/save-answer/3

cara panggil = ('api.peserta.save-answer')

request
int $userId

response
{
"success": true,
"message": "Jawaban berhasil disimpan.",
"data": {
"id": 1,
"submission_id": 2,
"pertanyaan_id": 1,
"jawaban_id": 1,
"jawaban_teks": "0",
"tautan_bukti_drive": null,
"skor_sistem": 0,
"skor_validasi_reviewer": "0.00",
"note_reviewer": null,
"created_at": "2026-04-22T08:12:27.000000Z",
"updated_at": "2026-04-22T13:21:29.000000Z"
}
}

GET /api/assessment/reviewer/tasks/{userId}

contoh =
http://127.0.0.1:8000/api/assessment/reviewer/tasks/{userId}
test api = http:/127.0.0.1:8000/api/assessment/reviewer/tasks/{userId}/2

cara panggil = ('api.reviewer.tasks')

request

int $userId

response{
"success": true,
"message": "Daftar plottingan tugas berhasil diambil.",
"data": {
"total_tugas": 5,
"menunggu_review": 0,
"selesai_review": 0,
"daftar_asesmen": [
{
"id": 1,
"institution_id": "14c1dd87-54fe-30e0-8750-0b7160de62f9",
"nama_pic": "PIC Default",
"jabatan_pic": null,
"no_hp_pic": "081234567890",
"tahun_periode": "2026",
"status": "ACTIVE",
"user_id": 2,
"reviewer_id": 8,
"total_skor_sistem": "0.00",
"total_skor_akhir": "0.00",
"created_at": "2026-04-22T15:52:03.000000Z",
"updated_at": "2026-04-22T15:52:03.000000Z",
"institusi": null
}
]
}
}

reviewer
method get
menu dashboard
belum dinilai = tabel pengumpulan where status = submitted where total diplotting = true return hasil angka / count
total diplotting =tabel pengumpulan where reviewer_id = user id return hasil angka/ count
selesai dinilai = tabel pengumpulan where status = graded where total diplotting = true return hasil angka/ count
yang belum di review = belum dinilai true return id tabel pengumpulan with tabel institusi(get nama institusi)
daftar plotting = total diplotting true return id tabel pengumpulan with tabel institusi(get nama institusi)

GET /api/assessment/reviewer/tasks/reviewer/peseta/{pesertaId}

contoh =
http://127.0.0.1:8000/api/assessment/reviewer/tasks/reviewer/peseta/{pesertaId}
test api = http://127.0.0.1:8000/api/assessment/reviewer/tasks/reviewer/peseta/1

cara panggil = ('api.reviewer.detail')

request
int $pesertaId

contoh response
{
"success": true,
"message": "Daftar plottingan tugas berhasil diambil.",
"data": {
"pengumpulan": {
"id": 1,
"status": "SUBMITTED",
"total_skor_sistem": "0.00",
"total_skor_akhir": "0.00",
"tahun_periode": "2026"
},
"institusi": {
"id": "14c1dd87-54fe-30e0-8750-0b7160de62f9",
"nama_institusi": "Universitas Pembangunan Nasional Veteran Jawa Timur",
"jenis_institusi": "PTN"
},
"profil_peserta": {
"visi": "Visi institusi...",
"misi": "Misi institusi...",
"jml_fakultas": 7,
"jml_studi": 30,
"jml_dosen": 500,
"jml_tendik": 200,
"jml_mhs": 20000,
"jml_ukm": 45,
"berkas_pendukung": [
"/storage/lampiran-peserta/upn/berkas.pdf"
],
"agama": {
"islam": 19845,
"kristen": 523,
"katolik": 287,
"hindu": 156,
"buddha": 134,
"konghucu": 55
}
}
}
}

form penilaian rubrik
post
skor final
note reviewer

menu riwayat penilaian
get

---

### Auth Routes

POST /api/auth/register

contoh =
http://127.0.0.1:8000/api/auth/register
test api = http://127.0.0.1:8000/api/auth/register

cara panggil = ('api.auth.register')

request
string $nama_pt
string $kategori_pt
string $nama_pic
string $no_hp_pic
string $jabatan_pic
string $email
string $password
string $password_confirmation

response
{
"success": true,
"message": "Registrasi berhasil. Silakan tunggu konfirmasi Admin.",
"data": {
"id": 1,
"email": "user@example.com",
"role": "PESERTA",
"status": "PENDING",
"created_at": "...",
"updated_at": "..."
}
}

---

POST /api/auth/login

contoh =
http://127.0.0.1:8000/api/auth/login
test api = http://127.0.0.1:8000/api/auth/login

cara panggil = ('api.auth.login')

request
string $email
string $password

response
{
"success": true,
"message": "Login berhasil.",
"data": {
"user": {
"id": 1,
"email": "user@example.com",
"role": "PESERTA",
"status": "ACTIVE",
"created_at": "...",
"updated_at": "..."
},
"token": "1|O1x2...TokenSanctumDiSini"
}
}

---

POST /api/auth/logout

contoh =
http://127.0.0.1:8000/api/auth/logout
test api = http://127.0.0.1:8000/api/auth/logout

cara panggil = ('api.auth.logout')

request
Header: Authorization Bearer {token}

response
{
"success": true,
"message": "Logout berhasil.",
"data": null
}

---

### Profile Routes

GET /api/profile
cara panggil = ('api.profile')

POST /api/profile/baseline
cara panggil = ('api.profile.baseline')

GET /api/profile/status
cara panggil = ('api.profile.status')

---

### Peserta Progress

GET /api/assessment/peserta/current-progress/{assessment_id?}
cara panggil = ('api.peserta.progress')

POST /api/assessment/peserta/auto-save/{assessment_id?}
cara panggil = ('api.peserta.auto-save')

---

### Reviewer Routes

GET /api/assessment/reviewer/assignments
cara panggil = ('api.reviewer.assignments')

GET /api/assessment/reviewer/questions/{sub_id}/{cat_id}
cara panggil = ('api.reviewer.questions')

POST /api/assessment/reviewer/save-verification
cara panggil = ('api.reviewer.save-verification')

POST /api/assessment/reviewer/finalize/{sub_id}
cara panggil = ('api.reviewer.finalize')
