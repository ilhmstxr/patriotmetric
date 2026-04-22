POST /api/baseline/{userId}

contoh = http://127.0.0.1:8000/api/baseline/{userId}

cara panggil = ('api.submitter.baseline')

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



GET /api/assessment/submitter/questions/{assessmentId}

contoh = 
http://127.0.0.1:8000/api/assessment/submitter/questions/{assessmentId}
test api = http://127.0.0.1:8000/api/assessment/submitter/questions/1

cara panggil = ('api.submitter.questions')

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
        },
        {
          "id": 2,
          "pertanyaan_id": 1,
          "opsi_jawaban": "1",
          "value": null,
          "keterangan": "Ada kebijakan tertulis tetapi belum diimplementasikan",
          "created_at": "2026-04-21T22:38:39.000000Z",
          "updated_at": "2026-04-21T22:38:39.000000Z"
        },
        {
          "id": 3,
          "pertanyaan_id": 1,
          "opsi_jawaban": "2",
          "value": null,
          "keterangan": "Ada kebijakan dan diimplementasikan dalam satu kegiatan dari Tridharma",
          "created_at": "2026-04-21T22:38:39.000000Z",
          "updated_at": "2026-04-21T22:38:39.000000Z"
        },
        {
          "id": 4,
          "pertanyaan_id": 1,
          "opsi_jawaban": "3",
          "value": null,
          "keterangan": "Ada kebijakan dan diimplementasikan dalam dua kegiatan dari Tridharma",
          "created_at": "2026-04-21T22:38:39.000000Z",
          "updated_at": "2026-04-21T22:38:39.000000Z"
        },
        {
          "id": 5,
          "pertanyaan_id": 1,
          "opsi_jawaban": "4",
          "value": null,
          "keterangan": "Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma",
          "created_at": "2026-04-21T22:38:39.000000Z",
          "updated_at": "2026-04-21T22:38:39.000000Z"
        },
        {
          "id": 6,
          "pertanyaan_id": 1,
          "opsi_jawaban": "5",
          "value": null,
          "keterangan": "Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma serta kegiatan penunjang",
          "created_at": "2026-04-21T22:38:39.000000Z",
          "updated_at": "2026-04-21T22:38:39.000000Z"
        }
      ]
    }
  ]
}


POST /api/assessment/submitter/finalize/{assessmentID}

contoh = 
http://127.0.0.1:8000/api/assessment/submitter/finalize/{assessmentID}
test api = http:/127.0.0.1:8000/api/assessment/submitter/finalize/1

cara panggil = ('api.submitter.finalize')

request
   int $userId

response
 {
  "success": true,
  "message": "Data baseline berhasil disimpan",
  "data": null
}


GET /api/assessment/submitter/preview-results/{assessmentId}

contoh = 
http://127.0.0.1:8000//api/assessment/submitter/preview-results/{assessmentId}
test api = http:/127.0.0.1:8000/api/assessment/submitter/preview-results/2

cara panggil = ('api.submitter.preview-results')

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


POST /api/assessment/submitter/save-answer/{userId}

contoh = 
http:/127.0.0.1:8000/api/assessment/submitter/save-answer/{userId}
test api = 
http:/127.0.0.1:8000/api/assessment/submitter/save-answer/3


cara panggil = ('api.submitter.save-answer')

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
---

### Auth Routes

POST /api/auth/register
cara panggil = ('api.auth.register')

POST /api/auth/login
cara panggil = ('api.auth.login')

POST /api/auth/logout
cara panggil = ('api.auth.logout')

---

### Profile Routes

GET /api/profile
cara panggil = ('api.profile')

POST /api/profile/baseline
cara panggil = ('api.profile.baseline')

GET /api/profile/status
cara panggil = ('api.profile.status')

---

### Submitter Progress

GET /api/assessment/submitter/current-progress/{assessment_id?}
cara panggil = ('api.submitter.progress')

POST /api/assessment/submitter/auto-save/{assessment_id?}
cara panggil = ('api.submitter.auto-save')

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


