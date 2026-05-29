# 🧪 Prompt Alchemy: Graphify + Claude Code Skills

Dokumen ini adalah manuskrip *forge* (peleburan) yang merangkai kapabilitas visibilitas spasial **Graphify** dengan spesialisasi eksekusi dari ekosistem **Claude Code Skills** (`ai-toolkit`, `fullstack-dev-skills`, `superpowers`, `laravel`).

Setiap racikan (*recipe*) di bawah ini dirancang untuk memaksa AI (LLM) membaca fakta sebelum menggunakan keterampilannya.

---

## 🛠️ Racikan Eksekusi (Recipes)

### 1. 💾 The Database Surgeon (Refactor Basis Data Tanpa Downtime)

Skenario ini digunakan saat Anda ingin mengubah tipe kolom relasional dari Integer (ID) menjadi UUID pada tabel inti, tapi takut merusak relasi ORM dan Service yang ada.

> **ℹ️ Metadata Resep:**
> *   **Gaya Prompt:** Hybrid (Graph-Driven) + Comparative
> *   **Kombinasi Skill:** `database-optimizer` (atau `table-creator`) + `laravel-specialist`
> *   **Parameter Graphify:** Feature Impact (`--dfs --budget 2500`)

#### 📝 Template Prompt:
```text
[DATABASE SURGEON PROTOCOL]
Anda adalah Senior Data Architect dengan spesialisasi laravel-specialist and database-optimizer. Kita akan mengubah skema tabel [NamaTabel] dari ID ke UUID.

Fase 1: Radius Analisis
Eksekusi /graphify query "Model [NamaModel]" --budget 2500 (tanpa --dfs agar merambat ke samping). Temukan setiap Repository, Service, dan Controller yang mengandalkan model ini.

Fase 2: Trade-Off & Plan
Berdasarkan graf tersebut, buat tabel perbandingan dampak jika kita membiarkan relasi lama rusak vs menggunakan Migration bertahap. Hasilkan Implementation Plan yang mencakup:
- File Migration (dengan foreign key cascade yang aman).
- Penyesuaian [NamaModel].php (Traits UUID).
- Penyesuaian di Repository layer.

Tampilkan plan ini. Jika saya balas "EXECUTE", jalankan modifikasi kode di semua file tersebut.
```

---

### 2. 🏹 The Autonomous Bug Hunter (Pemadaman Api Kritis)

Skenario ini digunakan saat aplikasi hancur dengan error 500 di production. Log menunjukkan *Null Pointer Exception* di sebuah Service, tapi Anda tidak tahu titik awal datanya (asal usul payload).

> **ℹ️ Metadata Resep:**
> *   **Gaya Prompt:** Context-Grounded + Micro-Tasking
> *   **Kombinasi Skill:** `systematic-debugging` + `service-debugging` + `secure-code-guardian`
> *   **Parameter Graphify:** Surgical Strike (`--dfs --budget 1000`)

#### 📝 Template Prompt:
```text
[GROUND-ZERO DEBUGGER]
Mode krisis aktif. Aktifkan insting systematic-debugging dan secure-code-guardian Anda.
Ini stack trace absolut dari server:
[PASTE LOG ERROR DI SINI]

Error meledak di [NamaClass/Method]. Jangan menebak alurnya.

Eksekusi /graphify query "[NamaClass]" --dfs --budget 1000 untuk melacak rute ke atas (siapa Controller/Route yang memanggilnya dan DTO apa yang dilempar).

Lakukan lokalisasi bug menggunakan pendekatan binary search logs (baca log terminal jika perlu).

Perbaiki bug ini murni pada titik kegagalannya. Pastikan tidak melanggar keamanan OWASP.
```

---

### 3. 🏗️ The Architecture Modernizer (Merombak Legacy Code)

Skenario ini digunakan ketika Anda mewarisi kode kuno (Monolith Fat Controller) dan ingin mencicilnya menjadi pola Service-Repository atau memecahnya ke microservices.

> **ℹ️ Metadata Resep:**
> *   **Gaya Prompt:** Milestone (WBS) + Reverse-Engineered
> *   **Kombinasi Skill:** `legacy-modernizer` + `architecture-designer` + `microservices-architect`
> *   **Parameter Graphify:** God Mode (`--budget 6000`)

#### 📝 Template Prompt:
```text
[LEGACY MODERNIZATION INITIATIVE]
Anda bertindak sebagai Principal architecture-designer dan legacy-modernizer. Kita akan merombak modul [NamaModulKuno] menjadi struktur Clean Architecture.

Fase 1: Deteksi God Nodes
Eksekusi /graphify query "[NamaModulKuno] Controller" --budget 6000 untuk membongkar kopling ketat (tight coupling) di file tersebut.

Fase 2: WBS (Work Breakdown Structure)
Tarik mundur (Reverse-Engineer) langkah pembongkarannya. Buat daftar tugas sekuensial (Milestone) mulai dari pemisahan Interface, pembuatan Repository, hingga pembersihan Controller.

JANGAN tulis logika kodenya dulu. Tampilkan WBS tersebut untuk saya setujui. Kita akan mengeksekusinya satu per satu per milestone.
```

---

### 4. 🛡️ The Paranoid Guardian (Zero-Bug Feature Forge)

Skenario ini digunakan saat menulis fitur inti sistem pembayaran atau otentikasi di mana cacat logika (bug) berarti kerugian finansial.

> **ℹ️ Metadata Resep:**
> *   **Gaya Prompt:** Constraint (Berbatas Mutlak)
> *   **Kombinasi Skill:** `fullstack-guardian` (atau `impeccable`) + `test-master` + `test-driven-development`
> *   **Parameter Graphify:** Feature Impact (`--dfs --budget 2000`)

#### 📝 Template Prompt:
```text
[PARANOID FEATURE FORGE]
Aktifkan mode fullstack-guardian dan impeccable (Zero-Bug Tolerance).
Kita akan membuat fitur [NamaFiturKritis].

Validasi batas arsitektur dengan /graphify query "[EndpointTerkait / Database]" --dfs --budget 2000.

Gunakan Test-Driven Development (test-master). Tulis pengujian (Unit/Integration Test) yang gagal terlebih dahulu.

Implementasikan logika backend-nya menggunakan standar Clean Architecture. Dilarang ada metode yang melebihi 20 baris. Dilarang melakukan query Eloquent di Controller.

Pastikan tes berjalan hijau (Pass). Jika ada peringatan linting atau error, otomatis berbaikilah diri Anda sebelum melaporkan "Selesai".
```

---

### 5. 🌉 The UI/UX Architect (Frontend-Backend Bridge)

Skenario ini digunakan ketika Backend (API) sudah jadi dan tergambar di Graphify, lalu Anda meminta agen menyusun UI React/Vue yang secara sempurna menembak endpoint tersebut tanpa *prop drilling*.

> **ℹ️ Metadata Resep:**
> *   **Gaya Prompt:** Few-Shot (Pattern Matching) + Expert Persona
> *   **Kombinasi Skill:** `react-expert` / `vue-expert` + `design-workflow` + `api-designer`
> *   **Parameter Graphify:** Cross-Domain (`--budget 3000`)

#### 📝 Template Prompt:
```text
[UI/UX BRIDGE PROTOCOL]
Bertindaklah sebagai Lead Frontend Architect dengan keahlian react-expert (atau vue-expert) dan design-workflow.

Fase 1: Backend Alignment
Eksekusi /graphify query "API [NamaFiturBackend]" --dfs --budget 1500 untuk memahami skema Response/Request DTO yang sudah ada di backend.

Fase 2: Pattern Matching
Lihat pola komponen UI kita saat ini dengan membaca direktori [Path/Ke/Komponen/Shared].

Fase 3: Eksekusi UI
Buat komponen antarmuka (Single-File Component) untuk fitur ini. Terapkan state management yang efisien (tanpa prop drilling berlebih). Sesuaikan type/interface TypeScript persis dengan DTO backend dari output graf tadi.
```

---

### 6. 🐳 The DevOps Orchestrator (Deployment Tanpa Kejutan)

Skenario ini digunakan ketika Anda butuh script Dockerfile atau GitHub Actions, tapi sistem memiliki dependensi biner tersembunyi (seperti library PDF, ekstensi Redis, dll).

> **ℹ️ Metadata Resep:**
> *   **Gaya Prompt:** Reverse-Engineered
> *   **Kombinasi Skill:** `ci-cd-patterns` + `devops-engineer` + `terraform-best-practices` (Opsional)
> *   **Parameter Graphify:** Cross-Domain (`--budget 4000`)

#### 📝 Template Prompt:
```text
[DEVOPS ORCHESTRATION PIPELINE]
Anda adalah Senior devops-engineer dan ahli ci-cd-patterns. End-Goal kita: Deploy aplikasi ini via Docker ke VPS.

Fase 1: Analisis Dependensi Tersembunyi
Eksekusi /graphify query "Composer/Package/Dependencies" --budget 4000. Cari tahu ekstensi PHP, driver database, atau dependensi eksternal apa saja yang terikat di graf ini.

Fase 2: Reverse-Engineering (IaC/CI)
Tarik mundur kebutuhan environment server dari dependensi tersebut. Buatkan:
- Dockerfile yang dioptimasi (Multi-stage build).
- Konfigurasi docker-compose.yml (Termasuk Redis/Postgres jika tercatat di graf).

Berikan perintah shell untuk eksekusinya.
```