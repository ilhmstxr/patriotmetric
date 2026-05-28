# Accordion Rubrik (Peserta & Reviewer) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan accordion per kategori pada halaman rubrik peserta dan halaman penilaian reviewer, sehingga user bisa collapse/expand grup pertanyaan per kategori.

**Architecture:** Menggunakan Alpine.js `x-data` state untuk track kategori mana yang terbuka. Setiap category header menjadi clickable toggle. Default: semua kategori terbuka (expanded). Klik header akan collapse/expand pertanyaan di bawahnya dengan animasi smooth.

**Tech Stack:** Alpine.js (sudah ada), Tailwind CSS (sudah ada), Lucide icons (sudah ada)

---

## File Structure

| File | Action | Responsibility |
|------|--------|----------------|
| `resources/views/dashboard/rubrik.blade.php` | Modify | Tambah accordion state & toggle di sisi peserta |
| `resources/views/reviewer/detail.blade.php` | Modify | Tambah accordion state & toggle di sisi reviewer (tab penilaian) |

---

### Task 1: Accordion pada Halaman Rubrik Peserta

**Files:**
- Modify: `resources/views/dashboard/rubrik.blade.php:4` (x-data block — tambah `openCategories` state)
- Modify: `resources/views/dashboard/rubrik.blade.php:592-600` (category loop — wrap questions dengan accordion)

- [ ] **Step 1: Tambah state `openCategories` di x-data root**

Di dalam blok `x-data` (line 4), tambahkan property dan helper:

```javascript
openCategories: {},

initOpenCategories() {
    this.categories.forEach((_, idx) => { this.openCategories[idx] = true; });
},

toggleCategory(idx) {
    this.openCategories[idx] = !this.openCategories[idx];
},

isCategoryOpen(idx) {
    return this.openCategories[idx] !== false;
},
```

Dan di dalam `applyData()` (setelah `this.categories = this.groupByCategory(data.questions);`), panggil:
```javascript
this.initOpenCategories();
```

- [ ] **Step 2: Ubah Category Header menjadi clickable accordion toggle**

Ganti bagian Category Header (sekitar line 595-598) dari:

```html
<div class="flex items-center justify-between border-b border-[#e0e0e0] pb-2">
    <h2 class="text-[15px] font-bold text-[#1d293d] uppercase tracking-wide" x-text="categoryData.category"></h2>
    <span class="text-[12px] font-semibold text-[#62748e]" x-text="'Bobot: ' + categoryData.weight"></span>
</div>
```

Menjadi:

```html
<button type="button"
    @click="toggleCategory(cIdx)"
    class="w-full flex items-center justify-between border-b border-[#e0e0e0] pb-2 cursor-pointer group">
    <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4 text-[#62748e] transition-transform duration-300"
             :class="isCategoryOpen(cIdx) ? 'rotate-90' : 'rotate-0'"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
        <h2 class="text-[15px] font-bold text-[#1d293d] uppercase tracking-wide group-hover:text-[#1b5e20] transition-colors" x-text="categoryData.category"></h2>
    </div>
    <span class="text-[12px] font-semibold text-[#62748e]" x-text="'Bobot: ' + categoryData.weight"></span>
</button>
```

- [ ] **Step 3: Wrap questions template dengan x-show dan transition**

Wrap bagian `<template x-for="q in categoryData.questions" ...>` beserta kontennya di dalam sebuah div yang di-toggle:

```html
<div x-show="isCategoryOpen(cIdx)"
     x-collapse>
    <div class="space-y-4 mt-4">
        <template x-for="q in categoryData.questions" :key="q.id">
            {{-- ... existing question card content ... --}}
        </template>
    </div>
</div>
```

Catatan: `x-collapse` adalah plugin Alpine.js. Jika belum tersedia, gunakan alternatif:

```html
<div x-show="isCategoryOpen(cIdx)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 max-h-0 overflow-hidden"
     x-transition:enter-end="opacity-100 max-h-[5000px]"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 max-h-[5000px]"
     x-transition:leave-end="opacity-0 max-h-0 overflow-hidden">
```

- [ ] **Step 4: Verifikasi Alpine Collapse plugin tersedia atau gunakan CSS transition**

Cek apakah `@alpinejs/collapse` sudah di-include di project. Jika tidak, gunakan pendekatan `x-show` + `x-transition` yang sudah ada di codebase (pattern yang sama dipakai di drawer).

Run: `grep -r "collapse" resources/js/ package.json`

Jika tidak ada, gunakan approach sederhana `x-show` + `x-transition.opacity.duration.200ms` saja (tanpa height animation) yang sudah proven di codebase ini.

- [ ] **Step 5: Commit perubahan peserta**

```bash
git add resources/views/dashboard/rubrik.blade.php
git commit -m "feat: add accordion per category on peserta rubrik page"
```

---

### Task 2: Accordion pada Halaman Penilaian Reviewer

**Files:**
- Modify: `resources/views/reviewer/detail.blade.php:6` (x-data block — tambah `openCategories` state)
- Modify: `resources/views/reviewer/detail.blade.php:624-865` (tab penilaian — wrap questions dengan accordion)

- [ ] **Step 1: Tambah state `openCategories` di x-data root reviewer**

Di dalam blok `x-data` (line 6), tambahkan:

```javascript
openCategories: {},

initOpenCategories() {
    this.rubrikData.forEach((_, idx) => { this.openCategories[idx] = true; });
},

toggleCategory(idx) {
    this.openCategories[idx] = !this.openCategories[idx];
},

isCategoryOpen(idx) {
    return this.openCategories[idx] !== false;
},
```

Dan di dalam `applyData()` (setelah `this.rubrikData = data.rubrik || [];`), panggil:
```javascript
this.initOpenCategories();
```

- [ ] **Step 2: Ubah Category Header reviewer menjadi clickable accordion toggle**

Ganti bagian Category Header di tab penilaian (sekitar line 627-629) dari:

```html
<div class="flex items-center justify-between border-b-[2px] border-[#e2e8f0] pb-[8px] mb-[16px]">
    <h2 class="text-[18px] font-bold text-[#1b5e20] uppercase" x-text="categoryData.kategori"></h2>
    <span class="text-[14px] font-bold text-[#62748e] bg-white border border-[#e2e8f0] px-[12px] py-[4px] rounded-full shadow-sm" x-text="'Max: ' + categoryData.bobot_maksimal + ' poin'"></span>
</div>
```

Menjadi:

```html
<button type="button"
    @click="toggleCategory(cIdx)"
    class="w-full flex items-center justify-between border-b-[2px] border-[#e2e8f0] pb-[8px] mb-[16px] cursor-pointer group">
    <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5 text-[#1b5e20] transition-transform duration-300"
             :class="isCategoryOpen(cIdx) ? 'rotate-90' : 'rotate-0'"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
        <h2 class="text-[18px] font-bold text-[#1b5e20] uppercase" x-text="categoryData.kategori"></h2>
    </div>
    <span class="text-[14px] font-bold text-[#62748e] bg-white border border-[#e2e8f0] px-[12px] py-[4px] rounded-full shadow-sm" x-text="'Max: ' + categoryData.bobot_maksimal + ' poin'"></span>
</button>
```

- [ ] **Step 3: Wrap questions template reviewer dengan x-show dan transition**

Wrap bagian `<template x-for="q in categoryData.pertanyaan" ...>` di dalam:

```html
<div x-show="isCategoryOpen(cIdx)"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="space-y-[16px]">
        <template x-for="q in categoryData.pertanyaan" :key="q.id">
            {{-- ... existing question card content ... --}}
        </template>
    </div>
</div>
```

- [ ] **Step 4: Commit perubahan reviewer**

```bash
git add resources/views/reviewer/detail.blade.php
git commit -m "feat: add accordion per category on reviewer penilaian page"
```

---

### Task 3: Verifikasi Visual

- [ ] **Step 1: Jalankan dev server dan test di browser**

Run: `php artisan serve` dan buka halaman rubrik peserta + reviewer detail.

Verifikasi:
- Semua kategori terbuka by default
- Klik header kategori → collapse pertanyaan di bawahnya
- Klik lagi → expand kembali
- Chevron icon berputar sesuai state (pointing right = collapsed, pointing down = expanded)
- Auto-save tetap berfungsi normal
- Navigator drawer masih bisa scroll ke pertanyaan yang ter-collapse (pertanyaan harus auto-expand saat di-scroll-to)

- [ ] **Step 2: Fix scroll-to-question agar auto-expand kategori**

Di peserta `scrollToQuestion(qId)`, tambahkan logic untuk membuka kategori yang mengandung pertanyaan tersebut:

```javascript
scrollToQuestion(qId) {
    // Auto-expand category containing this question
    const catIdx = this.categories.findIndex(c => c.questions.some(q => q.id == qId));
    if (catIdx !== -1) this.openCategories[catIdx] = true;

    this.$nextTick(() => {
        const el = document.getElementById('q-' + qId);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.classList.add('ring-2', 'ring-[#1b5e20]', 'ring-offset-2');
            setTimeout(() => el.classList.remove('ring-2', 'ring-[#1b5e20]', 'ring-offset-2'), 1500);
        }
    });
    this.drawerOpen = false;
},
```

Di reviewer `scrollToQuestion(qId)`, tambahkan logic serupa:

```javascript
scrollToQuestion(qId) {
    const catIdx = this.rubrikData.findIndex(c => c.pertanyaan.some(q => q.id == qId));
    if (catIdx !== -1) this.openCategories[catIdx] = true;

    this.$nextTick(() => {
        const el = document.getElementById('q-' + qId);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.classList.add('ring-2', 'ring-[#1b5e20]', 'ring-offset-2');
            setTimeout(() => el.classList.remove('ring-2', 'ring-[#1b5e20]', 'ring-offset-2'), 1500);
        }
    });
    this.drawerOpen = false;
},
```

- [ ] **Step 3: Final commit**

```bash
git add resources/views/dashboard/rubrik.blade.php resources/views/reviewer/detail.blade.php
git commit -m "fix: auto-expand accordion category when navigating via drawer"
```
