buat sop untuk front-end, back-end, database

1. mengapa ini ada eror
   $ git push origin ilham/master
   Total 0 (delta 0), reused 0 (delta 0)
   remote:
   remote: Create a pull request for 'ilham/master' on GitHub by visiting:
   remote: https://github.com/ilhmstxr/patriotmetric/pull/new/ilham/master
   remote:
   To https://github.com/ilhmstxr/patriotmetric.git

- [new branch] ilham/master -> ilham/master
  error: update_ref failed for ref 'refs/remotes/origin/ilham/master': cannot lock ref 'refs/remotes/origin/ilham/master': 'refs/remotes/origin/ilham' exists; cannot create 'refs/remotes/origin/ilham/master'

2. bagaimana cara membuat stubs, baserepo, baseservice, basecontroller, apakah ada commandna atau harus buat folder / file sendiri
3. jika tidak ada folder app/traits/apiresponse.php maka bagaimana

stubs ini ditaruh di folder apa

[x] Automation Layer (make:arch Command): Alat untuk men-generate file DTO, Service, dan Repository secara instan agar struktur folder tetap rapi.

[x] Logic Layer (Base Service & Repository): Kerangka utama yang berisi fungsi CRUD dasar (Create, Read, Update, Delete) sehingga tim tidak perlu menulis query manual terus-menerus.

[x] Consistency Layer (ApiResponse Trait): Standarisasi format data JSON agar sisi Front-end selalu mendapatkan struktur data yang sama.

[x] Global Safety Layer (Exception Handler): Penanganan error otomatis (seperti error validasi atau 404) agar aplikasi tidak pernah mengirimkan halaman HTML error yang berantakan ke API.

[x] Standardization Layer (Force JSON Middleware): Memastikan semua komunikasi antar sistem dilakukan dalam format JSON secara otomatis.

1. Persiapan Folder & File Base (Manual)

[x] Folder Creation: Buat folder app/DTOs, app/Services, app/Repositories, dan app/Traits.
[x] app/Traits/ApiResponse.php: Standardisasi format JSON untuk API.

[x] app/Repositories/BaseRepository.php: Wrapper untuk query database (CRUD dasar).

[x] app/Services/BaseService.php: Tempat logika bisnis dan manajemen caching.

2. Konfigurasi Sistem Global

[x] app/Http/Middleware/ForceJsonResponse.php: Memaksa setiap request API merespons JSON.
[x] bootstrap/app.php:
[x] Registrasi Middleware ForceJsonResponse.
[x] Setup Exception Handler global (Tangkap error 404, 422, dan 500).

[x] Stubs Custom:
[x] Jalankan php artisan stub:publish.
[x] Buat stubs/dto.stub.
[x] Buat stubs/service.stub.
[x] Buat stubs/repository.stub.

3. Otomatisasi (Custom Command)

[x] app/Console/Commands/MakeArchitectureCommand.php: Command php artisan make:arch untuk generate DTO, Service, dan Repo sekaligus.

[x] Uji Coba: Jalankan php artisan make:arch Test dan verifikasi file yang dihasilkan.

🚀 FASE 2: Fitur Utama (Berdasarkan Task Board)

👨‍💻 PROGRAMMER A (Branch: feature/kuesioner-dashboard)

[ ] Setup Awal: Install Laravel 12 & Filament v3.
[ ] Database: Migrasi tabel users, categories, questions, submissions, submission_answers.
[ ] Dashboard Widget: Buat StatsOverviewWidget.
[ ] Master Kuesioner: - [ ] CategoryResource (Nama, Deskripsi, Bobot).
[ ] QuestionResource (Relasi Kategori, Teks Soal, Tipe).
[ ] Input Repeater untuk pilihan ganda & bobot nilai.

👨‍💻 PROGRAMMER 2 (Branch: feature/users-submissions-cms)

[x] CMS Compro: Buat CmsResource dengan RichEditor.
[x] Manajemen User: Buat UserResource (Role: Admin, Reviewer, Peserta).
[x] Manajemen Submisi: - [x] SubmissionResource (Status, Total Skor, Reviewer).
[x] Custom Action "Tugaskan Reviewer" (Integrasi dengan SubmissionService).
[x] Infolist: Halaman view jawaban kuesioner & link Google Drive.

Tabel,Kolom Baru / Revisi,Penjelasan
institutions,"id, nama_institusi, jenis_institusi, alamat, status_verifikasi"
identitas_institusi,"institution_id, jml_mahasiswa, jml_dosen, jml_prodi, baseline_json"
pertanyaans,"formula_config (JSON), benchmark_value"
pengumpulans,"periode_tahun, is_published"
pengumpulan_jawabans,"verified_details (JSON), skor_normalisasi"

untuk rubrik

- TODO: edit pengumpulan & pengumpulan -> user => peserta, view reviewer ga muncul
- TODO: tugaskan reviewer masih belum konek
- TODO: fw ketika successful create
- TODO: password di pengaturan cms kosong
- TODO: delete pengumpulan
- TODO: plottingan peserta & reviewer

Tabel,Kolom Baru / Revisi,Penjelasan
institutions,"id, nama_institusi, jenis_institusi, alamat, status_verifikasi"
identitas_institusi,"institution_id, jml_mahasiswa, jml_dosen, jml_prodi, baseline_json"
pertanyaans,"formula_config (JSON), benchmark_value"
pengumpulans,"periode_tahun, is_published"
pengumpulan_jawabans,"verified_details (JSON), skor_normalisasi"

untuk rubrik

1. regist email
2. verifikasi
3. regist ulang untuk data penting
4. pengerjaan kuesioner
5. lock by deadline
6. validasi reviewer
7. hasil

TODO: pagination admin rubrik by kategor

note:
CHECK

1. jika ada data jawaban_teks kosong bisa di null kan
   CHECK
2. untuk jawabannya bisa diisi opsi & teksfield singkat
   DONE
3. diinject
4. untuk rumus perhitungan dibuat di frontend, jadi tinggal terima hasil nya lalu di lempar ke api
   DONE 5. untuk pengumpulan_jawabans diisi foreign di opsi jawaban + jawaban_teks
5. qa revisi tampilan peserta
   DONE 7. nambahin label isian singkat / pilihan ganda

DONE
contoh
fetch harus
fetch pertanyaan_id
fetch submission_id

kondisi 1
ketika mengklik tipe pilihan_ganda, maka akan fetch jawaban_id
ketika mengklik tipe isian, maka akan fetch jawaban_teks

kondisi 2
ketika mengisi url, maka akan fetch tautan_bukti_drive

kondisi 3
ketika mengisi catatan dari reviewer, maka akan fetch note_reviewer

    kurang yang bagian autosave, konsepnya masih belum paham & validasi form request juga belum di rapihkan ke dalam file
    pemisahan soc di sisi repository

peserta di rename menjadi assessment

reviewer
plottingan reviewer dengan peserta yang telah di plotting

DONE
profil

nama pt
jenis pt
visi
misi

jml fakultas
jml prodi

jml dosen
jml tendik

jml mhs
jml organisasi
jml ukm

nama pic
jabatan pic
nomor hp
email

islam
kristen
katolik
hindu
buddha
konghucu

TODO
setelah registrasi terdapat proses verifikasi via email
pakai laravel sanctum untuk authnya
alur
registrasi, data tersimpan & trigger ke email yang telah didaftar
verifikasi email , membuka emailnya, dan klik button verifikasi akun ini, lalu otomatis terverif & status user dirubah menjadi active
lanjut mengisi verifikasi(daftar ulang)

---

## 🕵️‍♂️ Hasil Technical Interrogation (Arsitektur Frontend)

Berdasarkan analisis kode (khususnya `routes/web.php`, `routes/api.php`, struktur `resources/views`, dan Controller), berikut adalah jawaban pasti untuk 3 skenario yang Anda tanyakan:

**1. Apakah menggunakan "Blade Murni + SSR"? ❌ BUKAN UTAMA**

- **Fakta:** Di `routes/web.php`, halaman dirender **kosong** tanpa memparsing variabel. Contoh: `Route::get('/', function () { return view('dashboard.index'); });`
- **Fakta:** Controller untuk Web (yang seharusnya memanggil `compact()` atau `with()`) nyaris tidak mem-passing data dinamis apapun ke View.
- _Catatan:_ Memang ada penggunaan `@foreach` (contoh di `demografi.blade.php`), tapi itu hanya _hardcode array_ untuk dummy data UI, bukan dari Database.

**2. Apakah menggunakan "Blade + AJAX/Vanilla Fetch"? ✅ YA, INI MAZHAB ANDA**

- **Fakta:** Anda mereturn view kosong di `web.php`, namun menyediakan Endpoint API lengkap di `routes/api.php` (`/assessment/peserta/questions/{id}`, `/assessment/peserta/save-answer/{id}`, dll).
- **Fakta:** Semua interaksi data dikelola oleh Controller API (seperti `AssessmentController`) yang mengembalikan format JSON.
- **Fakta:** Di dokumen `todo-strux.md` ini sendiri sudah terkonfirmasi pola kerjanya: _"fetch harus fetch pertanyaan_id... ketika mengklik tipe pilihan_ganda, maka akan fetch jawaban_id"_.
- **Fakta Ekstra:** Interaksi dan UI State dikelola juga dengan **Alpine.js** (terlihat penggunaan atribut reaktif seperti `x-data`, `x-show`, `x-model` pada `reviewer/index.blade.php`).

**3. Apakah tanpa sadar mencampur dengan Inertia.js / Livewire? ❌ TIDAK**

- **Fakta:** Tidak ditemukan file `.jsx` / `.tsx` sama sekali di dalam folder `resources/js`. Folder `resources/js` hanya berisi `app.js` dan `bootstrap.js` bawaan.
- **Fakta:** Tidak ada satupun pemanggilan `Inertia::render()` di Controller.
- **Fakta:** Tidak ada directive `@livewire` ataupun `wire:model` di seluruh file `.blade.php`.

### 💡 Kesimpulan "Mazhab" Anda:

Mazhab arsitektur Frontend Anda secara konsisten adalah: **API-Driven Blade (Blade sebagai UI Container Statis + Alpine.js + Vanilla Fetch AJAX)**.

**Alurnya:**

1. User mengakses web → Laravel (`web.php`) mereturn layout/shell Blade yang secara fungsional "kosongan" dari sisi data.
2. Browser merender UI HTML/CSS awal beserta inisialisasi script.
3. JavaScript (Vanilla Fetch API dipanggil via Alpine.js/Script tag) menembak endpoint di `routes/api.php` untuk mengambil/mengirim data JSON.
4. Data JSON dari API tersebut langsung disuntikkan/dirender ke DOM secara interaktif di sisi Client (browser).

**Saran Konsistensi:** Lanjutkan pola ini! Jangan tiba-tiba mengirim data dari Controller `web.php` menggunakan `compact()` ke View, karena itu akan merusak arsitektur API-Driven Anda yang sudah cukup rapih dan terpisah ini (Separation of Concerns). Semua pengolahan data harus selalu masuk melalui Fetch API + Trait ApiResponse JSON.

pastikan register post data dengan parameter ini
nama pt
jenis pt

nama pic
jabatan pic
no hp pic
email pic
password

dikarenakan tabel pengumpulan wajib ada
tahun_periode maka otomatis masukkan tahun sekarang

dikarenakan tabel user juga wajib ada
role => PESERTA, jika registrasi otomatis ke role peserta
