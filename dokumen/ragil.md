# 👨‍💻 Task Board Khusus: Programmer A

## Informasi Umum

| Item              | Detail                                            |
| ----------------- | ------------------------------------------------- |
| **Fokus Modul**   | Dashboard Statistik & Master Kuesioner Dinamis    |
| **Branch Kerja**  | `feature/kuesioner-dashboard`                     |
| **Prasyarat**     | Setup Laravel, Filament, dan Database Migration sudah selesai (berada di branch `main`). |

---

## 📊 Langkah 2: Membuat Widget Dashboard Utama

**Tujuan:** Menampilkan ringkasan data di halaman pertama saat Super Admin login.

### 2.1 Generate Widget

Jalankan perintah berikut di terminal:

```bash
php artisan make:filament-widget StatsOverviewWidget --stats-overview
```

### 2.2 Edit File Widget

Buka file `app/Filament/Widgets/StatsOverviewWidget.php`, lalu lakukan hal berikut:

- [ ] Tarik data **Total Institusi** — Query `User` dengan role `submiter`.
- [ ] Tarik data **Submisi Menunggu Review** — Query `Submission` dengan status `submitted` atau `reviewing`.
- [ ] Tarik data **Selesai Divalidasi** — Query `Submission` dengan status `validated`.
- [ ] Tampilkan setiap data menggunakan `Stat::make('Label', $value)`.

**Contoh implementasi:**

```php
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Submission;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Institusi', User::role('submiter')->count()),
            Stat::make('Menunggu Review', Submission::whereIn('status', ['submitted', 'reviewing'])->count()),
            Stat::make('Selesai Divalidasi', Submission::where('status', 'validated')->count()),
        ];
    }
}
```

---

## 📁 Langkah 3: Modul Kategori Kriteria (`CategoryResource`)

**Tujuan:** Membuat Bab/Kriteria penilaian yang dapat dikelola oleh Super Admin.

### 3.1 Generate Resource

```bash
php artisan make:filament-resource Category
```

### 3.2 Setup Form Schema

Buka file `CategoryResource.php`, lalu buat form dengan field berikut:

- [ ] `TextInput::make('nama_kategori')->required()`
- [ ] `Textarea::make('deskripsi')->nullable()`
- [ ] `TextInput::make('bobot_persentase')->numeric()->default(0)`

### 3.3 Setup Table Columns

Tampilkan kolom-kolom berikut di tabel:

- [ ] **Nama Kategori** — `TextColumn::make('nama_kategori')`
- [ ] **Deskripsi** — `TextColumn::make('deskripsi')->limit(50)`
- [ ] **Bobot Persentase** — `TextColumn::make('bobot_persentase')->suffix('%')`

---

## ❓ Langkah 4: Modul Pertanyaan Dinamis (`QuestionResource`)

**Tujuan:** Membuat bank soal yang dinamis, termasuk pertanyaan pilihan ganda dengan opsi jawaban yang bisa ditambah/dikurangi secara fleksibel.

> [!IMPORTANT]
> Ini adalah bagian paling menantang. Pastikan bagian **Repeater untuk Pilihan Ganda** (Langkah 4.4) diimplementasikan dengan benar.

### 4.1 Persiapan Model

Buka file `Question.php`, lalu pastikan kolom `opsi_jawaban` di-cast sebagai array/JSON:

```php
// app/Models/Question.php

protected $casts = [
    'opsi_jawaban' => 'array',
];
```

### 4.2 Generate Resource

```bash
php artisan make:filament-resource Question
```

### 4.3 Setup Form Schema

Buka file `QuestionResource.php`, lalu buat form dengan field berikut:

- [ ] `Select::make('category_id')->relationship('category', 'nama_kategori')->required()`
- [ ] `Textarea::make('teks_pertanyaan')->required()`
- [ ] `Select::make('tipe')->options(['pilihan_ganda' => 'Pilihan Ganda', 'teks_singkat' => 'Teks Singkat'])->live()->required()`

### 4.4 Setup Repeater untuk Pilihan Ganda

> [!CAUTION]
> Bagian ini **CRITICAL**. Repeater hanya boleh muncul jika tipe pertanyaan yang dipilih adalah `pilihan_ganda`. Gunakan method `->visible()` dengan callback untuk mengontrol visibilitasnya.

Tambahkan blok kode **Repeater** berikut ke dalam form schema:

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

Repeater::make('opsi_jawaban')
    ->schema([
        TextInput::make('teks')
            ->label('Teks Opsi (Cth: Sangat Baik)')
            ->required(),
        TextInput::make('nilai')
            ->label('Bobot Nilai (Cth: 5)')
            ->numeric()
            ->required(),
    ])
    // Hanya muncul jika tipe yang dipilih adalah 'pilihan_ganda'
    ->visible(fn (\Filament\Forms\Get $get): bool => $get('tipe') === 'pilihan_ganda')
    ->columnSpanFull()
```

### 4.5 Setup Table Columns

Tampilkan kolom-kolom berikut di tabel:

- [ ] **Kategori** — `TextColumn::make('category.nama_kategori')`
- [ ] **Teks Pertanyaan** — `TextColumn::make('teks_pertanyaan')->limit(60)`
- [ ] **Tipe** — `TextColumn::make('tipe')->badge()`

---

## 🔗 Langkah 5: Relation Manager (Opsional, tapi Direkomendasikan)

**Tujuan:** Agar lebih *user-friendly*, hubungkan `Question` ke dalam halaman edit `Category` — sehingga admin bisa langsung mengelola pertanyaan dari dalam halaman kategori.

### 5.1 Generate Relation Manager

```bash
php artisan make:filament-relation-manager CategoryResource questions teks_pertanyaan
```

### 5.2 Sinkronkan Schema

- [ ] Buka file Relation Manager yang baru terbuat.
- [ ] Salin/samakan schema **Form** dan **Table**-nya dengan yang ada di `QuestionResource`.

### 5.3 Daftarkan Relation Manager

- [ ] Buka file `CategoryResource.php`.
- [ ] Daftarkan class Relation Manager ke dalam method `getRelations()`:

```php
public static function getRelations(): array
{
    return [
        RelationManagers\QuestionsRelationManager::class,
    ];
}
```

---

## ✅ Checklist Penyelesaian

| #   | Task                                        | Status |
| --- | ------------------------------------------- | ------ |
| 1   | Widget Dashboard (`StatsOverviewWidget`)    | ⬜     |
| 2   | `CategoryResource` — Form & Table           | ⬜     |
| 3   | `QuestionResource` — Form & Table           | ⬜     |
| 4   | Repeater Pilihan Ganda berfungsi dinamis    | ⬜     |
| 5   | Relation Manager terdaftar & berfungsi      | ⬜     |