# 🪙 Token Budgeting & Capacity Planning

Dokumen ini menjabarkan aturan matematis dalam membatasi aliran data dari **Graphify** menuju LLM. Mengontrol parameter `--budget` adalah kunci dari penerapan *Token Economy*.

---

## 💡 Prinsip Dasar (First Principles)

*   **1 Token** ≈ **4 Karakter Teks**
*   Rata-rata **1 Node** beserta relasinya (*edges*) memakan sekitar **50 - 80 token**.
*   Membiarkan Graphify memuntahkan seluruh node tanpa batas `--budget` akan menyebabkan **Context Dilution** (LLM menjadi buta informasi akibat *noise* yang terlalu banyak dan melupakan instruksi awal).

---

## 📊 Matriks Alokasi Token

| Skala Operasi | Tujuan Arsitektural | Rentang `--budget` | Gejala Jika Angka Meleset |
| :--- | :--- | :--- | :--- |
| **Surgical Strike** | Inspeksi 1 Class / Fungsi spesifik tanpa efek samping. | `500 - 800` | AI mengarang nama properti yang sebenarnya ada (*Under-budget*). |
| **Feature Impact** | Melacak 1 alur vertikal fitur (`Controller` -> `Service` -> `Repo`). | `1500 - 2500` | Graphify mencetak output `...(truncated)` dan memotong rantai dependensi krusial di akhir. |
| **Cross-Domain** | Menganalisis irisan antara 2 fitur berbeda (*Integration impact*). | `3000 - 4000` | AI kelebihan beban dan mulai membahas modul ke-3 yang tidak relevan (*Over-budget/Dilution*). |
| **God Mode** | Global refactoring / Core middleware / Base Class analysis. | `5000 - 7000+` | System timeout, limitasi karakter terminal jebol, atau LLM lupa instruksi awal. |

---

## 📋 Panduan Eksekusi Prompt & Command per Task

Berikut adalah rincian taktis eksekusi command di terminal Claude Code Anda untuk masing-masing skala operasi.

### 1. 🎯 Surgical Strike (Micro-Tasking)

*   **Langkah:** Membedah struktur internal dari satu class atau fungsi secara spesifik (relasi inbound/outbound) tanpa memuat noise dari modul lain. Ideal untuk bug fixing atau isolasi tugas kecil.
*   **Relevansi Target Pencarian:**
    *   **Class / Interface:** Melacak kontrak dan dependensi layer (contoh target: `UserRepository`).
    *   **Function / Method:** Mengisolasi logika spesifik dan melihat siapa saja pemanggilnya (contoh target: `calculateScore`).
    *   **API Endpoint / Route:** Menelusuri titik awal masuknya request (contoh target: `POST /api/v1/auth/login`).
    *   **DTO / Request / Payload:** Melacak di file mana saja struktur data tersebut dibongkar atau divalidasi (contoh target: `RegisterUserRequest`).
    *   **Database Model:** Menginvestigasi relasi ORM, Mutators, atau Observers yang terikat (contoh target: `UserModel`).
*   **Template:**
    ```bash
    /graphify query "[NamaClass_Atau_Fungsi]" --dfs --budget [500-800]
    ```
*   **Contoh Command:**
    ```bash
    /graphify query "PenilaianService" --dfs --budget 800
    ```

---

### ⚡ 2. Feature Impact (Contexting Radius Ledakan)

*   **Langkah:** Memetakan alur satu fitur vertikal secara penuh (contoh: dari Endpoint -> Controller -> Service -> Repository). Sangat krusial digunakan di Fase 1 (Context Grounding) pada pendekatan Hybrid Implementation Plan sebelum memodifikasi fitur yang sudah ada.
*   **Relevansi Target Pencarian:**
    *   **Entry Point (Route/Controller):** Mengunci titik awal eksekusi fitur untuk melacak ke bawah (contoh target: `RegistrasiController`).
    *   **Orchestrator (Service Layer):** Melihat bagaimana aturan bisnis diurai menjadi banyak pemanggilan Repository (contoh target: `RegistrasiService`).
    *   **Data Access (Repository):** Memastikan batas kueri database untuk fitur tersebut (contoh target: `PesertaRepository`).
*   **Template:**
    ```bash
    /graphify query "Alur [Nama Fitur] dari Controller ke Repository" --dfs --budget [1500-2500]
    ```
*   **Contoh Command:**
    ```bash
    /graphify query "Alur Registrasi User dari Controller ke Repository" --dfs --budget 2000
    ```

---

### 🌐 3. Cross-Domain (Investigasi Integrasi)

*   **Langkah:** Menganalisis titik temu (*intersection*) dan potensi konflik logika antara dua fitur atau modul sistem yang berbeda.
*   **Relevansi Target Pencarian:**
    *   **Shared Models:** Entitas database yang diperebutkan oleh dua atau lebih fitur secara bersamaan (contoh target: `UserModel` yang diakses oleh modul Auth dan modul Penilaian).
    *   **Event / Listener:** Jembatan komunikasi asinkron antar modul (contoh target: `UserRegisteredEvent` memicu `SendWelcomeEmailListener`).
    *   **Cross-Service Calls:** Deteksi pelanggaran batas domain ketika Service A memanggil Service B secara langsung (contoh target: `AssessmentService` memanggil `NotificationService`).
*   **Template:**
    ```bash
    /graphify query "[Fitur A] dan [Fitur B]" --budget [3000-4000]
    ```
    > [!NOTE]
    > Hindari flag `--dfs` di sini karena kita butuh sebaran ke samping (Breadth-First Search).
*   **Contoh Command:**
    ```bash
    /graphify query "Modul Assessment dan Modul Auth" --budget 3500
    ```

---

### 👑 4. God Mode (Global Refactoring / Macro One-Shot)

*   **Langkah:** Mengidentifikasi dan membedah *God Nodes* (komponen inti yang dipanggil oleh hampir seluruh sistem, seperti `BaseRepository` atau Middleware) untuk persiapan perombakan arsitektur skala besar atau pembuatan boilerplate komprehensif.
*   **Relevansi Target Pencarian:**
    *   **Base Classes:** Fondasi arsitektur hierarki/pewarisan yang memengaruhi seluruh sistem (contoh target: `BaseRepository` atau `BaseController`).
    *   **Global Middleware:** Penjaga gerbang yang dieksekusi di setiap request yang masuk (contoh target: `JwtMiddleware` atau `RoleMiddleware`).
    *   **Global Traits / Helpers:** Potongan logika atau fungsionalitas yang diinjeksi ke dalam banyak kelas (contoh target: `ResponseFormatter` atau `AuditableTrait`).
*   **Template:**
    ```bash
    /graphify query "[Komponen Inti]" --budget [5000-7000]
    ```
*   **Contoh Command:**
    ```bash
    /graphify query "UserAuthMiddleware" --budget 6000
    ```

---

## 🔄 Parameter Traversal (Algoritma Penelusuran)

Graphify memiliki dua cara merayapi database graph. Pilih algoritma penelusuran yang tepat untuk menghemat token secara natural:

### 1. Depth-First Search (`--dfs`)
*   **Karakteristik:** Menyelam lurus ke bawah.
*   **Kapan dipakai:** Saat menelusuri alur eksekusi (contoh: *"Dari Route, lari ke Controller mana, lalu ke Service mana, lalu ke Repository apa?"*).
*   **Efisiensi:** Sangat hemat token karena membuang rantai yang menyamping. **Wajib digunakan untuk penelusuran arsitektur API.**

### 2. Breadth-First Search (Tanpa Flag / Default)
*   **Karakteristik:** Menyebar luas ke samping.
*   **Kapan dipakai:** Saat mencari tahu radius ledakan / *Reverse Dependency* (contoh: *"Jika saya mengubah BaseModel ini, file apa saja yang akan hancur?"*).
*   **Efisiensi:** Rawan membengkak gila-gilaan, terutama jika node tujuan adalah *God Node* (kelas utilitas). **Wajib dijaga dengan `--budget` yang ketat.**

---

## 📈 Modifier & Utilitas Pengendali Token (Cost-Control Commands)

Selain memanipulasi budget saat melakukan query, Anda dapat mengendalikan perilaku komputasi Graphify di tingkat build/extract untuk menghemat biaya (*Token Economy*) atau memaksa ekstraksi yang lebih dalam.

### A. Modifikator Ekstraksi (Token Burners & Savers)

*   `--update` : **Token Saver (Tertinggi).** Melakukan incremental sync. Hanya mengekstrak ulang file yang diubah (new/modified). Memangkas biaya API LLM hingga 90% pada repositori yang besar.
*   `--cluster-only` : **Zero Token.** Melakukan kalkulasi ulang algoritma komunitas tanpa memanggil API LLM sama sekali. Gunakan ini untuk me-render ulang `GRAPH_REPORT.md` atau `graph.json` jika konfigurasi berubah.
*   `--mode deep` : **Token Burner.** Secara agresif memaksa LLM mencari relasi terselubung (*INFERRED edges*). Sangat mahal secara token, namun mematikan untuk membongkar kopling tersembunyi (*latent couplings*) di legacy code.
*   `--dedup-llm` : **Tiebreaker LLM.** Membakar sedikit token ekstra menggunakan LLM untuk mengatasi ambigu saat nama entitas bentrok/duplikat (contoh: class `User` di backend vs interface `User` di frontend).

### B. Kueri Spesifik (Micro-Querying)

Jika `/graphify query` terasa terlalu melebar dan memakan terlalu banyak budget, gunakan kueri absolut berikut yang sangat hemat token:

*   `/graphify path "NodeA" "NodeB"` : Mencari jalur terpendek (*shortest path*) antara dua titik. Sangat efisien karena hanya me-render relasi linier yang menghubungkan keduanya, membuang semua node di sekitarnya.
*   `/graphify explain "NodeX"` : Penjelasan bahasa natural spesifik untuk 1 node. Menarik semua informasi yang terhubung dengannya tanpa melakukan penelusuran berantai.

---

## 🛠️ Troubleshooting: Analisis Kegagalan

> [!WARNING]
> ### Skenario A: Anda mendapat pesan `...(truncated at ~X token budget)` di terminal
> *   **Analisis Diagnostik:** Batas pelindung budget menyala, Graphify memotong output agar LLM tidak keracunan memori.
> *   **Tindakan Korektif:** Jika informasi yang esensial (seperti Repository layer) belum muncul, tingkatkan `--budget` sebesar `+1000` dan eksekusi ulang.

> [!IMPORTANT]
> ### Skenario B: AI mengeluh "Saya tidak menemukan class X di dalam graf."
> *   **Analisis Diagnostik:** Kata kunci pencarian Anda meleset, atau token budget Anda sudah habis sebelum algoritma Graphify sempat merayap dan menjangkau node X tersebut.
> *   **Tindakan Korektif:** Buat query pencarian menjadi absolut. Ubah parameter agar langsung menunjuk ke kelas yang dicari.
>     *   **Salah ❌:** `/graphify query "Cari fungsi login"`
>     *   **Benar ✅:** `/graphify query "AuthController" --dfs --budget 1500`