# Perubahan Warna ke Soft Colors

**Tanggal:** 25 Mei 2026

## Ringkasan Perubahan

Semua warna yang terlalu menonjol (vibrant) telah diubah menjadi warna yang lebih soft dan tidak terlalu mencolok.

---

## Pemetaan Warna Baru

### Status Colors (Lebih Soft)
| Status | Warna Lama | Warna Baru | Perubahan |
|--------|-----------|-----------|-----------|
| SUBMITTED | `bg-violet-100 text-violet-700` | `bg-purple-50 text-purple-600` | Violet → Purple (lebih soft) |
| IN_PROGRESS | `bg-indigo-100 text-indigo-700` | `bg-blue-50 text-blue-600` | Indigo → Blue (lebih soft) |
| GRADED | `bg-teal-100 text-teal-700` | `bg-emerald-50 text-emerald-600` | Teal → Emerald (lebih soft) |
| PUBLISHED | `bg-sky-100 text-sky-700` | `bg-cyan-50 text-cyan-600` | Sky → Cyan (lebih soft) |
| REJECTED | `bg-rose-100 text-rose-700` | `bg-rose-50 text-rose-600` | Rose (lebih soft) |

### Warning/Alert Colors (Lebih Soft)
| Elemen | Warna Lama | Warna Baru | Perubahan |
|--------|-----------|-----------|-----------|
| Background | `bg-amber-50/100` | `bg-orange-50` | Amber → Orange (lebih soft) |
| Text | `text-amber-700/800/900` | `text-orange-600/700` | Lebih soft & konsisten |
| Border | `border-amber-200/300` | `border-orange-100/200` | Lebih subtle |
| Icons | `text-amber-500/600` | `text-orange-400/500` | Lebih soft |

### Icon Backgrounds (Lebih Soft)
| Elemen | Warna Lama | Warna Baru |
|--------|-----------|-----------|
| Belum Dinilai | `bg-violet-50 text-violet-600` | `bg-purple-50 text-purple-500` |
| Total Plotting | `bg-indigo-50 text-indigo-600` | `bg-blue-50 text-blue-500` |
| Selesai Dinilai | `bg-teal-50 text-teal-600` | `bg-emerald-50 text-emerald-500` |

---

## File yang Dimodifikasi

### 1. resources/views/reviewer/detail.blade.php
**Perubahan:**
- ✅ Status colors di `submissionStatus()` function (line ~176)
- ✅ Evidence requirements section background & text (line ~639)
- ✅ Score badges di panduan (line ~656)
- ✅ Confirmation modal warning icon (line ~866)
- ✅ Saving status indicator (line ~768)
- ✅ Character count warning (line ~811, 818)
- ✅ Floating drawer "Dinilai" indicator (line ~963)

**Total:** 7 area perubahan

### 2. resources/views/reviewer/index.blade.php
**Perubahan:**
- ✅ Summary card icon backgrounds (3 cards) (line ~125, 134, 143)
- ✅ Header badge "Peserta Belum Direview" (line ~160)
- ✅ Avatar backgrounds di tabel pending (line ~178)
- ✅ Success icon "Tidak ada tugas" (line ~194)
- ✅ Avatar backgrounds di tabel utama (line ~238)
- ✅ Status badges: IN_PROGRESS, SUBMITTED, GRADED, PUBLISHED (line ~253-265)

**Total:** 6 area perubahan

### 3. resources/views/dashboard/rubrik.blade.php
**Perubahan:**
- ✅ Analysis result color untuk C.2 (line ~456, 459)
- ✅ Saving status indicator (line ~644)
- ✅ Warning text (line ~917)
- ✅ Toast notification border & background (line ~1002, 1010)

**Total:** 4 area perubahan

---

## Prinsip Perubahan

### 1. Background Colors
- **Lama:** `-100` (lebih gelap, lebih saturated)
- **Baru:** `-50` (lebih terang, lebih subtle)
- **Efek:** Background lebih soft, tidak terlalu mencolok

### 2. Text Colors
- **Lama:** `-700`, `-800`, `-900` (sangat gelap & saturated)
- **Baru:** `-500`, `-600`, `-700` (lebih soft, tetap readable)
- **Efek:** Text lebih lembut di mata, tidak terlalu kontras

### 3. Border Colors
- **Lama:** `-200`, `-300` (cukup prominent)
- **Baru:** `-100`, `-200` (lebih subtle)
- **Efek:** Border lebih halus, tidak terlalu menonjol

### 4. Icon Colors
- **Lama:** `-500`, `-600` (cukup vibrant)
- **Baru:** `-400`, `-500` (lebih soft)
- **Efek:** Icon lebih subtle, tidak terlalu eye-catching

---

## Perbandingan Visual

### Status Badges (Sebelum vs Sesudah)

**SUBMITTED (Menunggu Review):**
- ❌ Lama: Violet yang cukup vibrant
- ✅ Baru: Purple yang lebih soft & pastel

**IN_PROGRESS (Sedang Mengisi):**
- ❌ Lama: Indigo yang cukup bold
- ✅ Baru: Blue yang lebih soft & calming

**GRADED (Sudah Dinilai):**
- ❌ Lama: Teal yang cukup bright
- ✅ Baru: Emerald yang lebih soft & natural

**Warning/Alert:**
- ❌ Lama: Amber yang sangat eye-catching
- ✅ Baru: Orange yang lebih soft & warm

---

## Karakteristik Warna Baru

### ✅ Soft & Subtle
- Tidak terlalu mencolok
- Lebih nyaman di mata
- Tetap distinguishable (bisa dibedakan)

### ✅ Konsisten
- Semua menggunakan shade -50 untuk background
- Semua menggunakan shade -500/-600 untuk text
- Pola yang konsisten di seluruh aplikasi

### ✅ Professional
- Tidak menggunakan traffic light colors (merah-kuning-hijau)
- Warna lebih sophisticated
- Lebih cocok untuk aplikasi profesional

### ✅ Accessible
- Kontras masih cukup untuk readability
- Tetap memenuhi standar accessibility
- Tidak terlalu terang atau terlalu gelap

---

## Testing Checklist

### Halaman Reviewer
- [ ] Dashboard reviewer - summary cards
- [ ] Dashboard reviewer - tabel pending tasks
- [ ] Dashboard reviewer - tabel plotting
- [ ] Detail penilaian - status badge
- [ ] Detail penilaian - evidence requirements section
- [ ] Detail penilaian - confirmation modal
- [ ] Detail penilaian - floating drawer

### Halaman Dashboard Peserta
- [ ] Form rubrik - saving indicators
- [ ] Form rubrik - analysis results
- [ ] Form rubrik - toast notifications

---

## Catatan

1. **Tidak menggunakan Red-Yellow-Green** - Sesuai requirement, tidak menggunakan warna traffic light yang terlalu obvious
2. **Soft tapi tetap functional** - Warna masih bisa dibedakan untuk setiap status
3. **Konsisten di seluruh aplikasi** - Semua status menggunakan pola warna yang sama
4. **Mudah di-maintain** - Jika perlu adjust lagi, tinggal ubah shade number (50→100, 600→700, dll)

---

## Rekomendasi Selanjutnya

Jika ingin lebih soft lagi, bisa:
1. Ubah text dari `-600` ke `-500` (lebih terang)
2. Ubah background dari `-50` ke lebih terang lagi dengan opacity
3. Gunakan gray-based colors dengan slight tint

Jika ingin lebih prominent, bisa:
1. Ubah text dari `-600` ke `-700` (lebih gelap)
2. Ubah background dari `-50` ke `-100` (lebih saturated)
