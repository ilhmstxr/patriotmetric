# Flag & Drawer Indicator Bugfix Design

## Overview

Dua bug pada halaman rubrik peserta (`resources/views/dashboard/rubrik.blade.php`) perlu diperbaiki:

1. **Flag button tidak ter-disable saat status locked** — Fungsi `toggleFlag()` tidak memiliki guard terhadap `is_edit_enabled`, sehingga peserta masih bisa toggle flag meskipun submission sudah SUBMITTED/GRADED/PUBLISHED.

2. **Drawer indicator tidak reaktif untuk key baru** — Objek `answers` diinisialisasi sebagai `{}` dan key ditambahkan secara dinamis saat user berinteraksi. Alpine.js tidak mendeteksi penambahan property baru pada objek yang sudah di-track, sehingga `fillStatus()` tidak memicu re-render pada drawer indicator.

Pendekatan fix bersifat minimal dan targeted: menambahkan guard condition pada `toggleFlag()` dan HTML button, serta menggunakan teknik reassignment (`this.answers = {...this.answers}`) atau pre-inisialisasi key untuk memicu reaktivitas Alpine.js.

## Glossary

- **Bug_Condition (C)**: Kondisi yang memicu bug — flag bisa diklik saat locked, atau drawer tidak update saat answer key baru ditambahkan
- **Property (P)**: Perilaku yang diharapkan — flag disabled saat locked, drawer update real-time saat jawaban berubah
- **Preservation**: Perilaku yang harus tetap sama — flag tetap berfungsi saat DRAFT/IN_PROGRESS, fillStatus tetap benar untuk jawaban yang sudah ada
- **`toggleFlag(questionId)`**: Fungsi di Alpine.js component yang toggle state flag pada pertanyaan tertentu dan persist ke sessionStorage
- **`fillStatus(qId)`**: Fungsi yang mengembalikan 0 (kosong), 1 (sebagian), atau 2 (lengkap) berdasarkan apakah `answers[qId]` dan `links[qId]` terisi
- **`is_edit_enabled`**: Boolean property yang di-set `false` saat status SUBMITTED/GRADED/PUBLISHED
- **`applyData(data)`**: Fungsi yang memproses response API dan mengisi `answers`, `links`, `categories`

## Bug Details

### Bug Condition

#### Bug 1: Flag Interactive on Locked Status

Bug terjadi karena `toggleFlag()` tidak memeriksa `is_edit_enabled` sebelum mengubah state flag. HTML button juga tidak memiliki `:disabled` binding.

**Formal Specification:**
```
FUNCTION isBugCondition_Flag(input)
  INPUT: input of type {status: string, action: string, is_edit_enabled: boolean}
  OUTPUT: boolean
  
  RETURN input.action = 'toggle_flag'
         AND input.is_edit_enabled = false
         AND input.status IN ['SUBMITTED', 'GRADED', 'PUBLISHED']
END FUNCTION
```

#### Bug 2: Drawer Indicator Not Reactive

Bug terjadi karena Alpine.js menggunakan JavaScript Proxy untuk reactivity tracking. Ketika property baru ditambahkan ke objek yang sudah di-proxy (misalnya `this.answers[newKey] = value`), Alpine tidak mendeteksi perubahan tersebut karena proxy hanya track property yang sudah ada saat objek pertama kali di-observe.

**Formal Specification:**
```
FUNCTION isBugCondition_Drawer(input)
  INPUT: input of type {questionId: int, answers: object, action: string}
  OUTPUT: boolean
  
  RETURN input.action IN ['set_answer', 'set_link']
         AND input.questionId NOT IN Object.keys(input.answers)
         // Key belum ada di objek answers/links saat pertama kali di-track Alpine
END FUNCTION
```

### Examples

- **Bug 1 Example 1**: Status = SUBMITTED, peserta klik flag pada soal A.1 → flag berubah merah (seharusnya tidak bisa diklik)
- **Bug 1 Example 2**: Status = GRADED, peserta klik flag pada soal B.5 → flag toggle on/off (seharusnya disabled, cursor not-allowed)
- **Bug 1 Example 3**: Status = PUBLISHED, peserta klik flag → sessionStorage terupdate (seharusnya tidak ada perubahan)
- **Bug 2 Example 1**: Peserta baru pertama kali mengisi jawaban soal A.1 (key belum ada di `answers`) → drawer tetap abu-abu (seharusnya kuning/hijau)
- **Bug 2 Example 2**: Peserta mengisi link bukti pada soal yang belum pernah diisi → drawer tidak berubah warna sampai page refresh atau API response
- **Bug 2 Edge Case**: Peserta yang sudah punya jawaban tersimpan (key sudah ada dari `applyData`) → drawer update normal (ini bukan bug condition)

## Expected Behavior

### Preservation Requirements

**Unchanged Behaviors:**
- Flag button tetap berfungsi normal (toggle on/off, persist ke sessionStorage) saat status DRAFT atau IN_PROGRESS
- Visual flag (bookmark merah/abu-abu) tetap ditampilkan dengan benar berdasarkan state
- `fillStatus()` tetap mengembalikan nilai yang benar (0, 1, 2) untuk pertanyaan yang key-nya sudah ada di `answers`/`links`
- Auto-save mechanism (`scheduleAutoSave`, `autoSave`) tetap berjalan tanpa perubahan
- Drawer navigation (scroll to question, highlight) tetap berfungsi
- Mouse/touch interaction pada pilihan ganda dan input numerik tetap berfungsi
- Cache mechanism (localStorage) tetap berfungsi tanpa perubahan

**Scope:**
Semua input yang TIDAK melibatkan bug condition harus completely unaffected:
- Interaksi flag saat status DRAFT/IN_PROGRESS
- Perubahan jawaban pada pertanyaan yang key-nya sudah ter-inisialisasi di `answers`
- Semua interaksi non-flag (pilihan ganda, input numerik, link bukti) yang sudah ada
- Navigasi drawer (buka/tutup, scroll to question)

## Hypothesized Root Cause

### Bug 1: Flag Tidak Ter-disable

1. **Missing Guard di `toggleFlag()`**: Fungsi langsung mengubah `this.flags[questionId]` tanpa memeriksa `this.is_edit_enabled`. Semua fungsi lain yang mengubah data (pilihan ganda click, input numerik) sudah memiliki `:disabled="!is_edit_enabled"` pada HTML element-nya.

2. **Missing `:disabled` pada HTML button**: Button flag tidak memiliki attribute `:disabled` yang terikat ke `is_edit_enabled`, berbeda dengan button pilihan ganda yang sudah memiliki `:disabled="!is_edit_enabled || status === 'SUBMITTED' || status === 'GRADED'"`.

### Bug 2: Drawer Indicator Tidak Reaktif

1. **Alpine.js Proxy Limitation**: Alpine.js (v3) menggunakan JavaScript Proxy untuk reactivity. Proxy hanya bisa intercept operasi pada property yang sudah ada saat proxy dibuat. Menambahkan property baru (`this.answers[newKey] = value`) tidak memicu reactive update pada computed/bindings yang bergantung pada objek tersebut.

2. **Objek `answers` dimulai sebagai `{}`**: Pada `applyData()`, `this.answers = {}` di-reset, lalu hanya key untuk pertanyaan yang sudah punya jawaban yang ditambahkan. Pertanyaan yang belum dijawab tidak memiliki key di `answers`, sehingga saat user pertama kali mengisi jawaban, Alpine tidak mendeteksi perubahan.

3. **`fillStatus()` bergantung pada `this.answers[qId]`**: Fungsi ini membaca property dari objek `answers`. Jika key belum ada saat Alpine mulai tracking, perubahan pada key tersebut tidak memicu re-evaluation binding `:style` di drawer.

## Correctness Properties

Property 1: Bug Condition - Flag Disabled on Locked Status

_For any_ input where `is_edit_enabled` is false (status SUBMITTED/GRADED/PUBLISHED) and user attempts to toggle a flag, the fixed `toggleFlag()` function SHALL NOT modify the flag state, and the flag button SHALL be visually disabled (disabled attribute, cursor-not-allowed styling).

**Validates: Requirements 2.1**

Property 2: Bug Condition - Drawer Indicator Reactive Update

_For any_ input where a user changes an answer or link on a question whose key did not previously exist in the `answers`/`links` object, the fixed code SHALL immediately trigger Alpine.js reactivity so that `fillStatus()` returns the correct value and the drawer indicator updates its color without waiting for API response.

**Validates: Requirements 2.2, 2.3**

Property 3: Preservation - Flag Works on Editable Status

_For any_ input where `is_edit_enabled` is true (status DRAFT/IN_PROGRESS), the fixed `toggleFlag()` function SHALL produce the same result as the original function, preserving flag toggle behavior and sessionStorage persistence.

**Validates: Requirements 3.1, 3.2**

Property 4: Preservation - Existing Answer Reactivity Unchanged

_For any_ input where the answer key already exists in the `answers` object (pre-initialized from API data), the fixed `fillStatus()` SHALL produce the same result as the original function, preserving correct color indicators for already-answered questions.

**Validates: Requirements 3.3, 3.4, 3.5**

## Fix Implementation

### Changes Required

Assuming our root cause analysis is correct:

**File**: `resources/views/dashboard/rubrik.blade.php`

#### Fix 1: Guard `toggleFlag()` dan Disable Button

**Specific Changes**:

1. **Add guard di `toggleFlag()`**: Tambahkan early return jika `!this.is_edit_enabled`
   ```javascript
   toggleFlag(questionId) {
       if (!this.is_edit_enabled) return;
       this.flags[questionId] = !this.flags[questionId];
       try { sessionStorage.setItem('rubrik_flags', JSON.stringify(this.flags)); } catch(e) {}
   },
   ```

2. **Add `:disabled` pada flag button HTML**: Tambahkan binding disabled dan styling
   ```html
   <button type="button"
       @click.stop="toggleFlag(q.id)"
       :disabled="!is_edit_enabled"
       :title="isFlagged(q.id) ? 'Hapus flag' : 'Tandai pertanyaan ini'"
       class="absolute top-0 right-4 z-10 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
       style="width: 24px;">
   ```

#### Fix 2: Trigger Reaktivitas Alpine.js pada Drawer Indicator

**Specific Changes**:

3. **Pre-inisialisasi semua key di `applyData()`**: Setelah `this.answers = {}` dan `this.links = {}`, iterasi semua pertanyaan dan set default value untuk key yang belum ada
   ```javascript
   applyData(data) {
       // ... existing code ...
       this.categories = this.groupByCategory(data.questions);
       
       // Pre-initialize all question keys for Alpine reactivity
       this.allQuestions.forEach(q => {
           if (!(q.id in this.answers)) {
               this.answers[q.id] = '';
           }
           if (!(q.id in this.links)) {
               this.links[q.id] = '';
           }
       });
       // ... rest of existing code ...
   }
   ```

4. **Alternative/Complementary: Force reactivity pada answer change**: Pada event handler yang mengubah `answers` untuk pertama kali, gunakan spread reassignment
   ```javascript
   // Pada @click handler pilihan ganda, setelah set value:
   @click="answers[q.id] = opt.id; answers = {...answers}; scheduleAutoSave(q.id)"
   ```
   
   Namun pendekatan pre-inisialisasi (point 3) lebih clean karena menghindari spread pada setiap interaksi.

5. **Pastikan `links` juga pre-initialized**: Sama seperti `answers`, objek `links` juga perlu key yang sudah ada agar `fillStatus()` reaktif saat link pertama kali diisi.

## Testing Strategy

### Validation Approach

Testing menggunakan pendekatan dua fase: pertama surface counterexample pada kode yang belum diperbaiki, lalu verifikasi fix bekerja dan tidak merusak behavior yang ada.

### Exploratory Bug Condition Checking

**Goal**: Surface counterexample yang mendemonstrasikan bug SEBELUM implementasi fix. Konfirmasi atau bantah root cause analysis.

**Test Plan**: Buat test scenario yang mensimulasikan kondisi bug pada kode yang belum diperbaiki.

**Test Cases**:
1. **Flag Toggle on SUBMITTED**: Set `is_edit_enabled = false`, panggil `toggleFlag('q1')` → flag state berubah (bug confirmed, will fail after fix)
2. **Flag Toggle on GRADED**: Set status GRADED, klik flag button → button tidak disabled (bug confirmed)
3. **Drawer Color on New Answer**: Set `answers = {}`, lalu `answers['q1'] = 'opt1'` → `fillStatus('q1')` masih return 0 (bug confirmed karena Alpine tidak detect)
4. **Drawer Color on New Link**: Set `links = {}`, lalu `links['q1'] = 'https://drive.google.com/...'` → `fillStatus('q1')` masih return 0

**Expected Counterexamples**:
- Flag state berubah meskipun `is_edit_enabled = false`
- Drawer indicator tetap abu-abu meskipun jawaban sudah diisi
- Possible causes: missing guard, Alpine proxy limitation pada dynamic keys

### Fix Checking

**Goal**: Verifikasi bahwa untuk semua input dimana bug condition terpenuhi, fungsi yang sudah diperbaiki menghasilkan behavior yang benar.

**Pseudocode:**
```
// Bug 1: Flag disabled
FOR ALL input WHERE isBugCondition_Flag(input) DO
  result := toggleFlag_fixed(input.questionId)
  ASSERT flags[input.questionId] unchanged
  ASSERT button.disabled = true
END FOR

// Bug 2: Drawer reactive
FOR ALL input WHERE isBugCondition_Drawer(input) DO
  answers_fixed[input.questionId] := input.value
  result := fillStatus_fixed(input.questionId)
  ASSERT result > 0 (immediately, without API response)
END FOR
```

### Preservation Checking

**Goal**: Verifikasi bahwa untuk semua input dimana bug condition TIDAK terpenuhi, fungsi yang diperbaiki menghasilkan hasil yang sama dengan fungsi original.

**Pseudocode:**
```
// Bug 1: Flag still works on editable status
FOR ALL input WHERE NOT isBugCondition_Flag(input) DO
  ASSERT toggleFlag_original(input) = toggleFlag_fixed(input)
  ASSERT flags state identical
  ASSERT sessionStorage identical
END FOR

// Bug 2: Existing answers still display correctly
FOR ALL input WHERE NOT isBugCondition_Drawer(input) DO
  ASSERT fillStatus_original(input.questionId) = fillStatus_fixed(input.questionId)
END FOR
```

**Testing Approach**: Property-based testing direkomendasikan untuk preservation checking karena:
- Menghasilkan banyak test case otomatis untuk berbagai kombinasi status dan question ID
- Menangkap edge case yang mungkin terlewat oleh unit test manual
- Memberikan jaminan kuat bahwa behavior tidak berubah untuk semua non-buggy input

**Test Plan**: Observasi behavior pada kode UNFIXED terlebih dahulu, lalu tulis property-based test yang capture behavior tersebut.

**Test Cases**:
1. **Flag Preservation on DRAFT**: Verify flag toggle masih berfungsi saat `is_edit_enabled = true`
2. **Flag SessionStorage Preservation**: Verify sessionStorage tetap di-update saat status editable
3. **FillStatus Preservation for Existing Keys**: Verify `fillStatus()` tetap benar untuk key yang sudah ada
4. **Drawer Visual Preservation**: Verify warna drawer tetap benar untuk pertanyaan yang sudah dijawab dari API

### Unit Tests

- Test `toggleFlag()` dengan `is_edit_enabled = false` → flag state tidak berubah
- Test `toggleFlag()` dengan `is_edit_enabled = true` → flag state berubah
- Test flag button memiliki `disabled` attribute saat `is_edit_enabled = false`
- Test `fillStatus()` mengembalikan nilai benar setelah pre-inisialisasi key
- Test `fillStatus()` mengembalikan 0 untuk pertanyaan tanpa jawaban dan tanpa link
- Test `fillStatus()` mengembalikan 1 untuk pertanyaan dengan jawaban ATAU link
- Test `fillStatus()` mengembalikan 2 untuk pertanyaan dengan jawaban DAN link

### Property-Based Tests

- Generate random status (DRAFT, IN_PROGRESS, SUBMITTED, GRADED, PUBLISHED) dan verify flag behavior sesuai `is_edit_enabled`
- Generate random question IDs dan answer values, verify `fillStatus()` selalu mengembalikan nilai yang benar (0, 1, atau 2)
- Generate random sequences of answer/link assignments pada key baru dan existing, verify drawer indicator selalu konsisten

### Integration Tests

- Test full flow: load rubrik dengan status SUBMITTED → verify semua flag button disabled
- Test full flow: load rubrik dengan status DRAFT → isi jawaban baru → verify drawer indicator langsung berubah warna
- Test full flow: load rubrik dengan jawaban existing → verify drawer indicator menampilkan warna yang benar sejak awal
- Test flow: toggle flag saat DRAFT → submit → verify flag tidak bisa di-toggle lagi
