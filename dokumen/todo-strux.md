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
Assessments,"periode_tahun, is_published"
respon_assessments,"verified_details (JSON), skor_normalisasi"

untuk rubrik

- TODO: edit Assessment & Assessment -> user => peserta, view reviewer ga muncul
- TODO: tugaskan reviewer masih belum konek
- TODO: fw ketika successful create
- TODO: password di pengaturan cms kosong
- TODO: delete Assessment
- TODO: plottingan peserta & reviewer

Tabel,Kolom Baru / Revisi,Penjelasan
institutions,"id, nama_institusi, jenis_institusi, alamat, status_verifikasi"
identitas_institusi,"institution_id, jml_mahasiswa, jml_dosen, jml_prodi, baseline_json"
pertanyaans,"formula_config (JSON), benchmark_value"
Assessments,"periode_tahun, is_published"
respon_assessments,"verified_details (JSON), skor_normalisasi"

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
   DONE 5. untuk respon_assessments diisi foreign di opsi jawaban + jawaban_teks
5. qa revisi tampilan peserta
   DONE 7. nambahin label isian singkat / pilihan ganda

DONE
contoh
fetch harus
fetch pertanyaan_id
fetch assessment_id

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

dikarenakan tabel Assessment wajib ada
tahun_periode maka otomatis masukkan tahun sekarang

dikarenakan tabel user juga wajib ada
role => PESERTA, jika registrasi otomatis ke role peserta

1. Up pdf
2. Perhitungan rumus rubrik
3. Preview setelah submit (presentase) 
4.

DONE 1 instansi 1 akun aktif email pic per tahun
DONE Api buat nembak opsi jawaban (get per rubrik)
Ngitung biar masuk value yang mana di jawaban_teks / yang mengisi

- CHECK Plotting akun reviewer n peserta
- CHECK Timeline lock submission
- CHECK bikin akun reviewer
- DONE pemetaan fix status pengerjaan

rumus perhitungan masuk ke skor mana ditaruh sekalian di service 


jika isian singkat + rumus = jawaban teks + calculated precentage
jika isian singkat = jawaban teks 



rumus, hardcode disimpan berdasarkan calculated presentage 
contoh output jsonnya
// Contoh format payload yang harus dikirim ke API
const payload = {
    pertanyaanId: 10,
    jawabanTeks: JSON.stringify({
        raw_input: 15000000,
        calculated_percentage: 85.5
    }),
    tautanBukti: "https://drive.google.com/..."
};


1. File Migrasi (Mengubah Kolom ke JSON)
Jika tabel sudah ada, buat file migrasi baru untuk memodifikasi tipe kolom. Jalankan perintah ini di terminal:
php artisan make:migration change_jawaban_teks_to_json_on_jawabans_table

Isi file migrasi tersebut dengan kode berikut:

PHP
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jawabans', function (Blueprint $table) {
            // Ubah tipe data menjadi JSON. 
            // Pastikan Anda menggunakan Laravel versi modern atau memiliki doctrine/dbal terinstal jika versi lama.
            $table->json('jawaban_teks')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jawabans', function (Blueprint $table) {
            $table->text('jawaban_teks')->nullable()->change();
        });
    }
};
2. Model (Konfigurasi Casting)
Pastikan Model Anda secara otomatis melakukan serialisasi/deserialisasi array ke JSON.

PHP
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $fillable = [
        'pertanyaan_id',
        'jawaban_teks',
        'tautan_bukti'
    ];

    // Memicu konversi otomatis: Array (PHP) <-> JSON (Database)
    protected $casts = [
        'jawaban_teks' => 'array',
    ];
}
3. Layer Service (Defensive Parsing & Normalization)
Karena Frontend Anda menggunakan JSON.stringify(), payload yang ditangkap oleh Laravel untuk jawabanTeks akan berupa tipe String. Kita harus mengekstraknya menggunakan json_decode() secara aman, lalu menormalisasinya agar struktur tabel selalu konsisten.

PHP
namespace App\Services;

use App\Models\Jawaban;

class EvaluasiService
{
    public function simpanJawaban(array $payload)
    {
        $rawJawabanTeks = $payload['jawabanTeks'] ?? null;
        $decodedJawaban = null;

        // 1. Defensive Parsing: Coba decode stringified JSON dari FE
        if (is_string($rawJawabanTeks)) {
            $decoded = json_decode($rawJawabanTeks, true);
            
            // Periksa apakah decoding sukses (misal FE kirim JSON.stringify) 
            // atau gagal (misal FE kirim string teks murni untuk isian singkat tunggal)
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $decodedJawaban = $decoded;
            } else {
                $decodedJawaban = $rawJawabanTeks; // Fallback ke string murni
            }
        } elseif (is_array($rawJawabanTeks)) {
            // Antisipasi jika payload sudah berupa array dari HTTP Request (FE tidak memakai stringify)
            $decodedJawaban = $rawJawabanTeks;
        }

        // 2. Normalisasi Struktur: Paksa masuk ke kerangka baku
        $normalizedJawaban = [
            'raw_input' => is_array($decodedJawaban) 
                ? ($decodedJawaban['raw_input'] ?? null) 
                : $decodedJawaban, // Jika isian tunggal (string murni), taruh di sini
                
            'calculated_percentage' => is_array($decodedJawaban) 
                ? ($decodedJawaban['calculated_percentage'] ?? null) 
                : null,
        ];

        // 3. Eksekusi Penyimpanan
        // Menggunakan updateOrCreate untuk mencegah duplikasi data jika 1 pertanyaan = 1 jawaban per sesi
        return Jawaban::updateOrCreate(
            ['pertanyaan_id' => $payload['pertanyaanId']],
            [
                'jawaban_teks' => $normalizedJawaban, // Model cast akan otomatis menjadikannya JSON
                'tautan_bukti' => $payload['tautanBukti'] ?? null
            ]
        );
    }
}
Analisis Trade-off Logika di Atas:
Plus: Sangat tangguh (robust). Logika json_last_error() memastikan bahwa jika suatu saat Frontend lupa melakukan JSON.stringify() atau mengirim isian teks biasa secara langsung (misal: "Ini adalah jawaban teks saja"), sistem tidak akan crash dan tetap akan menyimpannya ke dalam struktur {"raw_input": "Ini adalah jawaban teks saja", "calculated_percentage": null}.

Minus: Terdapat sedikit overhead pada proses komputasi saat evaluasi is_string dan json_decode, namun sangat dapat diabaikan di level aplikasi modern demi tercapainya integritas data 100%.

Pastikan naming convention nama tabel (jawabans atau jawaban) disesuaikan dengan skema database asli Anda saat menjalankan migrasi.



untuk verifikasi masih nyantol kalau pakai seeder
datanya masih kesimpen di local storage


```json
{
  "pertanyaan_id": 42,
  "jawaban_id": null,
  "jawaban_teks": "{
  \"lokal\":2,
  \"regional\":1,
  \"nasional\":0,
  \"internasional\":1,
  
  \"poin_lokal\":2,
  \"poin_regional\":2,
  \"poin_nasional\":0,
  \"poin_internasional\":4,

  \"total_poin\":8}",
  "tautan_bukti": "https://drive.google.com/..."
}
```

bobot, dihapus
lepas hubungan dihapus
hapus dihapus

kelola opsi
kelola pertanyaan
syarat bukti

note tanya bu sri
untuk keagamaan apakah butuh dengan keputusan mk

- fitur cek pdf per peserta
- untuk create user tidak bisa dibuatkan via admin untuk pembuatan & verifikasi akunnya