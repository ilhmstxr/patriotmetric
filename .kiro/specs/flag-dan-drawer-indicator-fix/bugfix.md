# Bugfix Requirements Document

## Introduction

Terdapat dua bug pada halaman rubrik peserta (`resources/views/dashboard/rubrik.blade.php`):

1. **Flag masih bisa diinteraksi saat status SUBMITTED** — Tombol flag (bookmark) pada setiap pertanyaan tetap bisa diklik dan di-toggle meskipun status submission sudah SUBMITTED/GRADED/PUBLISHED. Padahal semua indikator rubrik lainnya (jawaban, link bukti) sudah di-disable melalui `is_edit_enabled = false`.

2. **Warna indikator di Floating Quiz Drawer tidak langsung terupdate** — Indikator warna pada drawer navigasi soal tidak berubah secara real-time saat peserta mengisi jawaban. Warna baru terupdate setelah API auto-save berhasil diproses, bukan saat user mengubah input. Ini disebabkan oleh masalah reaktivitas Alpine.js pada objek `answers` yang key-nya belum ter-inisialisasi sebelumnya.

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN status submission adalah SUBMITTED, GRADED, atau PUBLISHED THEN the system masih mengizinkan peserta mengklik tombol flag dan toggle state flag pada pertanyaan

1.2 WHEN peserta mengubah jawaban pilihan ganda atau mengisi link bukti pada pertanyaan yang belum pernah dijawab sebelumnya THEN the system tidak langsung memperbarui warna indikator di Floating Quiz Drawer sampai API auto-save berhasil merespons

1.3 WHEN peserta mengubah jawaban pada pertanyaan yang key-nya belum ada di objek `answers` saat inisialisasi THEN the system tidak mendeteksi perubahan reaktif pada `fillStatus()` sehingga drawer indicator tetap berwarna abu-abu (kosong)

### Expected Behavior (Correct)

2.1 WHEN status submission adalah SUBMITTED, GRADED, atau PUBLISHED THEN the system SHALL mencegah interaksi dengan tombol flag (disable klik, tampilkan visual disabled) sehingga flag tidak bisa di-toggle

2.2 WHEN peserta mengubah jawaban atau mengisi link bukti THEN the system SHALL langsung memperbarui warna indikator di Floating Quiz Drawer secara real-time tanpa menunggu respons API auto-save

2.3 WHEN peserta mengubah jawaban pada pertanyaan yang key-nya belum ada di objek `answers` THEN the system SHALL tetap memicu reaktivitas Alpine.js sehingga `fillStatus()` mengembalikan nilai yang benar dan drawer indicator berubah warna sesuai status pengisian

### Unchanged Behavior (Regression Prevention)

3.1 WHEN status submission adalah DRAFT atau IN_PROGRESS THEN the system SHALL CONTINUE TO mengizinkan peserta mengklik tombol flag untuk menandai pertanyaan

3.2 WHEN peserta mengklik tombol flag pada status DRAFT/IN_PROGRESS THEN the system SHALL CONTINUE TO menyimpan state flag ke sessionStorage dan menampilkan visual flag (bookmark merah) pada pertanyaan

3.3 WHEN API auto-save berhasil THEN the system SHALL CONTINUE TO memperbarui `saveStatus` dan menampilkan indikator tersimpan pada pertanyaan yang bersangkutan

3.4 WHEN peserta mengisi jawaban dan link bukti secara lengkap THEN the system SHALL CONTINUE TO menampilkan indikator hijau (lengkap) di drawer, dan kuning (sebagian) jika hanya salah satu yang terisi

3.5 WHEN peserta membuka halaman rubrik THEN the system SHALL CONTINUE TO me-restore flag state dari sessionStorage dan menampilkan flag yang sudah ditandai sebelumnya

---

## Bug Condition (Formal)

### Bug 1: Flag Interactive on Locked Status

```pascal
FUNCTION isBugCondition_Flag(X)
  INPUT: X of type {status: string, action: 'toggle_flag'}
  OUTPUT: boolean
  
  RETURN X.status IN ['SUBMITTED', 'GRADED', 'PUBLISHED'] AND X.action = 'toggle_flag'
END FUNCTION
```

```pascal
// Property: Fix Checking - Flag disabled on locked status
FOR ALL X WHERE isBugCondition_Flag(X) DO
  result ← toggleFlag'(X)
  ASSERT flag_state_unchanged(result) AND button_is_disabled(result)
END FOR
```

```pascal
// Property: Preservation Checking - Flag still works on editable status
FOR ALL X WHERE NOT isBugCondition_Flag(X) DO
  ASSERT toggleFlag(X) = toggleFlag'(X)
END FOR
```

### Bug 2: Drawer Indicator Not Reactive

```pascal
FUNCTION isBugCondition_Drawer(X)
  INPUT: X of type {questionId: int, answers: object, previousKeyExists: boolean}
  OUTPUT: boolean
  
  RETURN X.previousKeyExists = false
  // Bug triggers when answer key didn't exist in answers object before user interaction
END FUNCTION
```

```pascal
// Property: Fix Checking - Drawer updates immediately on answer change
FOR ALL X WHERE isBugCondition_Drawer(X) DO
  result ← setAnswer'(X.questionId, X.value)
  ASSERT fillStatus'(X.questionId) reflects new value immediately without API response
END FOR
```

```pascal
// Property: Preservation Checking - Existing answers still display correctly
FOR ALL X WHERE NOT isBugCondition_Drawer(X) DO
  ASSERT fillStatus(X.questionId) = fillStatus'(X.questionId)
END FOR
```
