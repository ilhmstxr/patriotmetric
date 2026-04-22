GET /api/baseline/{userId}

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


GET /api/assessment/submitter/finalize/{assessmentID}

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

cara panggil = ('api.submitter.previewResults')

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


GET http:/127.0.0.1:8000/api/assessment/submitter/save-answer/{userId}

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

