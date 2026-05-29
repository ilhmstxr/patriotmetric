# 📑 Graphify Prompting Protocols

Dokumen ini adalah *Standard Operating Procedure* (SOP) untuk berinteraksi dengan AI Assistant (Claude Code) menggunakan **Graphify**. Tujuannya adalah memitigasi **Contextual Drift** (kondisi di mana AI berhalusinasi dan memberikan solusi yang hanya 80% akurat akibat kehilangan konteks arsitektur).

---

## 📊 Matriks Keputusan Prompting

| Metrik | 1. Micro-Tasking | 2. Macro (One-Shot) | 3. Hybrid (Graph-Driven) ⭐ |
| :--- | :--- | :--- | :--- |
| **Use Case Utama** | Logika krusial (Kalkulasi, Auth, Payment). | Endpoint CRUD standar, boilerplate. | Modifikasi fitur end-to-end. |
| **Akurasi Output** | **99%** (Manual Gatekeeping) | **70-80%** (Risiko halusinasi tinggi) | **95%+** (Graf menuntun AI) |
| **Beban Kognitif** | Tinggi (Baca-Setuju berulang) | Rendah (Otomatis jalan) | Rendah (Review di awal saja) |

---

## 🛠️ Tiga Pendekatan Prompting Utama

### 1. 🔍 Micro-Tasking (Isolasi Tugas)

Digunakan saat Anda menyentuh lapisan sistem yang sangat kritikal dan AI tidak diizinkan melangkah ke file berikutnya sebelum file saat ini disetujui.

> **⚖️ Trade-off:**
> *   **Keuntungan (+):** Nol halusinasi. Eksekusi sangat presisi karena dikawal per lapis (*Layer-by-Layer*).
> *   **Kerugian (-):** Membosankan dan memperlambat *development speed*.

#### 📝 Template Prompt:
```text
[ISOLATED TASK: {NAMA_LAYER_ATAU_FILE}]
Tujuan: Modifikasi logika di {NamaClass/File}.

Gunakan /graphify query "{NamaClass}" --dfs --budget 1000 untuk melihat relasi inbound dan outbound saat ini.

Berdasarkan graf tersebut, terapkan perubahan ini: {Deskripsi Perubahan Teknis}.

PENTING: Berhenti dan tunggu persetujuan saya sebelum menyentuh file atau layer lain. Tampilkan hasil modifikasi {NamaClass} ini saja.
```

---

### ⚡ 2. Macro / One-Shot (God Mode)

Tembak dan lupakan. Digunakan khusus untuk tugas terisolasi yang tidak akan menyenggol fitur atau logika inti sistem.

> **⚖️ Trade-off:**
> *   **Keuntungan (+):** Eksekusi instan, sangat cepat untuk *scaffolding*.
> *   **Kerugian (-):** Berbahaya jika batasan arsitektur (seperti *Clean Architecture*) tidak dikunci dengan ketat.

#### 📝 Template Prompt:
```text
[ONE-SHOT EXECUTION]
Tujuan: Buat fitur {NamaFitur}.
Batasan: Patuhi Clean Architecture Laravel (rujuk pada standar plugin fullstack-dev-skills). Logika bisnis di Service, abstraksi DB di Repository. Dilarang menggunakan Fat Controllers.
Eksekusi: Gunakan /graphify path "{ControllerLama}" "{Database}" untuk melihat konvensi jalur saat ini, lalu langsung tulis semua kodenya (DTO, Service, Controller, Repository) secara lengkap dalam satu kali eksekusi.
```

---

### 🧠 3. Hybrid (Graph-Driven Implementation Plan) `[DIREKOMENDASIKAN]`

Pendekatan *Zero-Gap*. AI dipaksa mengekstrak data dari graf sebelum membuat rencana. Rencana divalidasi oleh Anda, lalu dieksekusi secara otonom.

> **⚖️ Trade-off:**
> *   **Keuntungan (+):** Menutup celah 20% spesifikasi yang sering hilang di plan biasa. Aman, terukur, dan efisien.
> *   **Kerugian (-):** Membutuhkan konsumsi token LLM yang sedikit lebih besar di awal untuk validasi.

#### 📝 Master Template Prompt:
```text
[ZERO-GAP IMPLEMENTATION PROTOCOL]
Anda adalah Backend Architect. Kita akan memodifikasi fitur {NAMA FITUR}.

Fase 1: Context Grounding (Wajib)
1. Eksekusi /graphify query "{KATA_KUNCI_FITUR}" --dfs --budget 2000.
2. Analisis output graf tersebut secara diam-diam.

Fase 2: Implementation Plan (Berdasarkan Fakta Graf)
Buat rencana eksekusi teknis yang strict. Rencana HARUS mencakup:
- Penyesuaian skema DTO (Sebutkan property).
- Modifikasi Service Layer (Logika bisnis).
- Operasi Repository (Query/ORM).
- Penyesuaian Controller.

Tampilkan Implementation Plan ini kepada saya. TANYAKAN: "Apakah plan ini sudah menutupi semua edge cases arsitektur Anda?"

Fase 3: Eksekusi Otonom
Jika saya menjawab "Ya", langsung eksekusi PENUH seluruh rencana tanpa berhenti per file menggunakan ekosistem ai-toolkit dan standar laravel.
```

---

## 🚀 4. Taksonomi Prompting Tingkat Lanjut (Graphify + Plugin Ecosystem Integration)

Berikut adalah taksonomi interaksi spesifik yang menggabungkan kapabilitas spasial Graphify dengan batas fungsional dari ekosistem plugin Anda (`superpowers`, `laravel`, `ai-toolkit`, `fullstack-dev-skills`).

### A. Eksekusi Kaku & Standardisasi

#### 1. ⚙️ Constraint (Berbatas Mutlak)
*   **Tujuan:** Memaksa AI bekerja dalam perimeter sempit (eksekusi murni tanpa asumsi/penjelasan).
*   **Relevansi:** Saat butuh potongan kode instan, script kecil, atau modifikasi langsung pada satu file yang sudah jelas letaknya.
*   **Trade-off:**
    *   **Plus (+):** Eksekusi kilat & efisien.
    *   **Minus (-):** AI patuh membabi buta, salah parameter = salah kode.
*   **Template Terintegrasi:**
    ```text
    [STRICT CONSTRAINT] Buat [Tugas]. Patuhi standar arsitektur dari plugin [laravel / fullstack-dev-skills]. Pastikan parameter sesuai dengan relasi yang ada di /graphify explain "[NamaClassTerkait]". Batasi output HANYA pada blok kode, tanpa narasi penjelasan.
    ```

#### 2. 📋 Few-Shot (Pattern Matching)
*   **Tujuan:** Menyuruh AI meniru coding standard atau arsitektur internal dari modul yang sudah ada di repositori Anda.
*   **Relevansi:** Menjaga konsistensi DTO, Error Handling, atau struktur Service Layer antar fitur.
*   **Trade-off:**
    *   **Plus (+):** Konsistensi kode 100% identik dengan gaya proyek Anda.
    *   **Minus (-):** Membutuhkan token awal untuk membaca contoh.
*   **Template Terintegrasi:**
    ```text
    [PATTERN MATCHING] Kita akan membuat [Modul Baru]. Pertama, eksekusi /graphify explain "[NamaClassContoh_YangSudahAda]" untuk memahami pola relasinya. Menggunakan style dan standar arsitektur yang identik (sesuai plugin ai-toolkit), buatkan implementasi untuk [Modul Baru].
    ```

---

### B. Brainstorming & Arsitektur

#### 3. 👑 Expert Persona (Role-Play Arsitek)
*   **Tujuan:** Meminjam kerangka berpikir tingkat tinggi (*expert*) untuk merancang struktur arsitektur sistem atau basis data sebelum menyentuh kode.
*   **Relevansi:** Fase desain arsitektur baru, perancangan skema relasional, atau System Design.
*   **Trade-off:**
    *   **Plus (+):** Solusi sangat matang (*enterprise-grade*).
    *   **Minus (-):** Bisa menghasilkan desain *overkill* untuk aplikasi berskala kecil.
*   **Template Terintegrasi:**
    ```text
    [EXPERT PERSONA] Bertindaklah sebagai Lead Systems Architect yang menggunakan kemampuan superpowers dan ekosistem laravel. Eksekusi /graphify query "[Modul_Inti_Saat_Ini]" --budget 4000 untuk menganalisis topologi kita sekarang. Rancang arsitektur baru untuk [Kebutuhan_Baru] yang terintegrasi secara elegan dengan topologi tersebut.
    ```

#### 4. ⚖️ Comparative (Trade-Off Analysis)
*   **Tujuan:** Meminta AI membedah secara teknis (plus-minus) dua atau lebih opsi arsitektur sebelum Anda mengambil keputusan final.
*   **Relevansi:** Persimpangan keputusan tech stack, desain algoritma, atau memilih design pattern.
*   **Trade-off:**
    *   **Plus (+):** Menghilangkan *blind spot* secara transparan.
    *   **Minus (-):** Keputusan akhir dan pertanggungjawaban tetap berada di tangan Anda.
*   **Template Terintegrasi:**
    ```text
    [TRADE-OFF ANALYSIS] Saya bingung antara menggunakan [Opsi A] or [Opsi B] untuk [Kasus]. Gunakan /graphify path "[Titik_Awal]" "[Database]" untuk melihat alur saat ini. Bandingkan dampak kedua opsi tersebut terhadap graf kita saat ini. Tampilkan matriks tabel perbandingan fokus pada [Faktor Penentu, misal: Keamanan/Performa/Skalabilitas].
    ```

---

### C. Troubleshooting & Investigasi Bug

#### 5. 🔍 Context-Grounded (Injeksi Bukti Absolut)
*   **Tujuan:** Menutup total ruang halusinasi AI saat error dengan menyuapkan bukti absolut (stack trace, log, env).
*   **Relevansi:** Saat bug spesifik muncul dengan stack trace yang jelas. Pemadaman api kritis (*Hotfix*).
*   **Trade-off:**
    *   **Plus (+):** Resolusi bug sangat presisi dan mematikan.
    *   **Minus (-):** Jika *root cause* ada di file yang tidak dijangkau graf, AI mungkin tetap buntu.
*   **Template Terintegrasi:**
    ```text
    [GROUND-ZERO DEBUGGING] Ini log error absolut: [Log Error]. Terjadi di sekitar [NamaClass/Method]. Eksekusi /graphify query "[NamaClass]" --dfs --budget 1500 untuk melacak siapa pemanggil class ini. Berdasarkan fakta graf dan standar laravel, temukan exact line yang menyebabkan disfungsi ini dan berikan solusinya.
    ```

#### 6. 🩺 Diagnostic (Root Cause Logic)
*   **Tujuan:** Meminta penjelasan tahap demi tahap (*Socratic method*) mengenai mengapa sebuah logika gagal, tanpa meminta AI langsung menuliskan kode perbaikannya.
*   **Relevansi:** Mentoring, memahami *edge cases* aneh, atau saat Anda ingin melatih *Logical Thinking* Anda sendiri.
*   **Trade-off:**
    *   **Plus (+):** Pemahaman fundamental / *domain knowledge* Anda meningkat tajam.
    *   **Minus (-):** Proses penyelesaian bug menjadi sedikit lebih lambat.
*   **Template Terintegrasi:**
    ```text
    [ROOT CAUSE DIAGNOSTIC] Kode di [NamaClass] menghasilkan anomali pada [Deskripsi_Masalah]. JANGAN berikan kode perbaikannya. Gunakan /graphify query "[NamaClass]" untuk melihat dependensinya, lalu jelaskan tahap demi tahap secara logis mengapa alur data ini menabrak prinsip Clean Architecture dari fullstack-dev-skills.
    ```

---

### D. Planning & Eksekusi Skala Besar

#### 7. 🗓️ Sequential / Milestone (WBS)
*   **Tujuan:** Memecah fitur / perombakan masif menjadi *Work Breakdown Structure* (WBS) mikro yang linier dan terurut.
*   **Relevansi:** Persiapan Sprint mingguan, inisiasi refactoring basis kode legacy, menyusun backlog.
*   **Trade-off:**
    *   **Plus (+):** Eksekusi sangat rapi, bisa di-copy ke Jira/Trello.
    *   **Minus (-):** Kaku. Jika requirement berubah di langkah ke-3, urutan ke-4 dan seterusnya berantakan.
*   **Template Terintegrasi:**
    ```text
    [MILESTONE PLANNING] Rencana eksekusi: Implementasi [Fitur_Besar]. Pertama, ekstrak God Nodes terkait menggunakan /graphify query "[Modul_Terkait]" --budget 5000. Pecah langkah implementasinya secara kronologis dari Database Migration hingga API Endpoint menggunakan standar fullstack-dev-skills. Hasilkan Checklist linier.
    ```

#### 8. 🔄 Reverse-Engineered (Goal-Oriented)
*   **Tujuan:** Memetakan dependency dengan menarik mundur (*Reverse*) dari *End Goal* absolut menuju langkah pertama (prasyarat).
*   **Relevansi:** Deployment, DevOps CI/CD, integrasi API pihak ke-3 yang ketat.
*   **Trade-off:**
    *   **Plus (+):** Sangat aman secara sistem, tidak ada kejutan dependensi (*missing libraries*) di tengah jalan.
    *   **Minus (-):** Membutuhkan definisi *End Goal* yang sangat spesifik dan tak bisa diganggu gugat.
*   **Template Terintegrasi:**
    ```text
    [REVERSE-ENGINEERED PIPELINE] End-Goal absolut kita adalah: [Target_Spesifik, misal: CI/CD Pipeline sukses ke VPS]. Eksekusi /graphify query "[File/Modul_Terkait_Infrastruktur]" untuk melihat batasan konfigurasi saat ini. Tarik mundur (reverse) syarat dan langkah teknisnya dari tahap akhir (Goal) sampai ke prasyarat paling awal (misal: SSH Config/Env Vars) menggunakan kapabilitas superpowers Anda.
    ```