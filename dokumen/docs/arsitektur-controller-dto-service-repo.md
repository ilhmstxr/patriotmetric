# Dokumentasi Arsitektur: Controller → DTO → Service → Repository

## Gambaran Umum

Patriot Metric menggunakan arsitektur berlapis (layered architecture) untuk memisahkan tanggung jawab setiap komponen:

```
┌─────────────────────────────────────────────────────────────┐
│                        REQUEST                               │
└─────────────────────┬───────────────────────────────────────┘
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                    CONTROLLER                                 │
│  - Menerima request dari client                              │
│  - Validasi input dasar (role, parameter)                    │
│  - Membentuk DTO dari request data                           │
│  - Memanggil Service                                         │
│  - Mengembalikan response (successResponse / errorResponse)  │
└─────────────────────┬───────────────────────────────────────┘
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                       DTO                                     │
│  - Data Transfer Object                                      │
│  - Wadah data yang berpindah antar layer                     │
│  - Tidak ada logic bisnis                                    │
│  - Method toArray() untuk konversi ke format Eloquent        │
└─────────────────────┬───────────────────────────────────────┘
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                     SERVICE                                   │
│  - Business logic utama                                      │
│  - Menerima DTO sebagai parameter                            │
│  - Orchestrate multiple repository calls                     │
│  - Validasi bisnis (status check, permission, dll)           │
│  - Kalkulasi dan transformasi data                           │
└─────────────────────┬───────────────────────────────────────┘
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                   REPOSITORY                                  │
│  - Data access layer (query database)                        │
│  - CRUD operations                                           │
│  - Query kompleks (join, aggregate, dll)                     │
│  - Tidak ada business logic                                  │
└─────────────────────┬───────────────────────────────────────┘
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                   DATABASE (Eloquent Model)                   │
└─────────────────────────────────────────────────────────────┘
```

---

## 1. Controller

**Lokasi:** `app/Http/Controllers/`

**Tanggung Jawab:**
- Menerima HTTP request
- Validasi dasar (autentikasi, otorisasi role)
- Membentuk DTO dari data request
- Memanggil method Service yang sesuai
- Mengembalikan response menggunakan trait `ApiResponse`

**Contoh: `ReviewerController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Services\AssessmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReviewerController extends Controller
{
    use ApiResponse;

    protected $assessmentService;
    protected $reviewerRepository;
    protected $assessmentRepository;

    public function __construct(
        AssessmentService $assessmentService,
        \App\Repositories\ReviewerRepository $reviewerRepository,
        \App\Repositories\AssessmentRepository $assessmentRepository
    ) {
        $this->assessmentService = $assessmentService;
        $this->reviewerRepository = $reviewerRepository;
        $this->assessmentRepository = $assessmentRepository;
    }

    public function saveScores(Request $request, $pesertaId)
    {
        try {
            // 1. Validasi role
            $user = $request->user();
            if (!$user || strtolower($user->role) !== 'reviewer') {
                throw new \Exception("Unauthorized: Akses khusus untuk Reviewer.", 403);
            }

            // 2. Validasi data exists
            $assessment = $this->assessmentRepository->find($pesertaId);
            if (!$assessment || !in_array($assessment->status, ['SUBMITTED', 'IN_PROGRESS', 'GRADED'])) {
                throw new \Exception("Asesmen tidak ditemukan atau tidak dapat dinilai.", 404);
            }

            // 3. Ambil input
            $scores = $request->input('scores', []);
            $notes  = $request->input('notes', []);

            // 4. Panggil Service
            $this->assessmentService->saveReviewerScores($assessment, $scores, $notes);

            // 5. Return response
            return $this->successResponse([], 'Skor berhasil disimpan dan rekap diperbarui.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }
}
```

**Prinsip:**
- Controller harus "skinny" — tidak boleh ada business logic di sini
- Semua logic bisnis didelegasikan ke Service
- Error handling menggunakan try-catch dan trait `ApiResponse`

---

## 2. DTO (Data Transfer Object)

**Lokasi:** `app/DTO/`

**Tanggung Jawab:**
- Wadah/container untuk data yang berpindah antar layer
- Tidak ada logic bisnis, hanya properti data
- Method `toArray()` untuk konversi ke format yang bisa dipakai Eloquent

**Base DTO:**

```php
<?php

namespace App\DTO;

abstract class BaseDTO
{
    public function toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $data = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $value = $this->{$name};

            // Konversi camelCase ke snake_case otomatis
            $snakeName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
            $data[$snakeName] = $value;
        }

        return $data;
    }
}
```

**Contoh: `ReviewDTO.php`**

```php
<?php

namespace App\DTO;

class ReviewDTO
{
    public $submissionId;
    public $categoryId;
    public $answers = [];
}
```

**Cara Penggunaan di Controller/Service:**

```php
// Di Controller: membentuk DTO dari request
$dto = new \App\DTO\ReviewDTO();
$dto->submissionId = $pesertaId;
$dto->categoryId = $request->input('category_id');
$dto->answers = $request->input('answers', []);

// Kirim ke Service
$result = $this->reviewService->persistVerification($dto);
```

**Prinsip:**
- DTO hanya berisi public properties
- Tidak ada validasi atau logic di dalam DTO
- Penamaan properti menggunakan camelCase, otomatis dikonversi ke snake_case saat `toArray()`

---

## 3. Service

**Lokasi:** `app/Services/`

**Tanggung Jawab:**
- Tempat semua business logic
- Menerima DTO sebagai parameter
- Memanggil Repository untuk akses database
- Validasi bisnis (cek status, permission, kelengkapan data)
- Kalkulasi dan transformasi data
- Orchestrate multiple repository calls dalam satu flow

**Base Service:**

```php
<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class BaseService
{
    protected BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDetail(int|string $id, bool $useCache = true): ?Model
    {
        if (!$useCache) return $this->repository->find($id);

        $cacheKey = strtolower(class_basename($this)) . "_detail_{$id}";
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return $this->repository->find($id);
        });
    }

    public function store(object $dto): Model
    {
        return $this->repository->create($dto->toArray());
    }

    public function update(int|string $id, object $dto): bool
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
```

**Contoh: `ReviewService.php` (method `persistVerification`)**

```php
public function persistVerification(\App\DTO\ReviewDTO $dto)
{
    // 1. Validasi bisnis: cek status asesmen
    $submission = $this->repository->find($dto->submissionId);
    if ($submission && $submission->status === 'REVIEWED') {
        throw new \Exception("Akses ditolak: Review sudah final.", 403);
    }

    // 2. Sanitasi dan validasi data
    $sanitizedVerifications = [];
    foreach ($dto->answers as $ver) {
        $sanitizedVerifications[] = [
            'id' => $ver['id'],
            'scale_choice' => intval($ver['scale_choice'] ?? null),
            'manual_score' => floatval($ver['manual_score'] ?? null),
        ];
    }

    // 3. Panggil Repository untuk simpan data
    $this->repository->updateReviewData($dto->submissionId, $sanitizedVerifications);

    // 4. Update status
    if ($submission && $submission->status !== 'REVIEWING') {
        $submission->update(['status' => 'REVIEWING']);
    }

    return true;
}
```

**Contoh: `ReviewService.php` (method `lockReview`)**

```php
public function lockReview(\App\DTO\ReviewDTO $dto)
{
    // 1. Validasi: semua indikator sudah dinilai?
    $belumLengkap = $this->repository->hasUnverifiedAnswers($dto->submissionId);
    if ($belumLengkap) {
        throw new \Exception("Ada indikator yang belum diberikan manual_score.", 422);
    }

    // 2. Kalkulasi skor total
    $calculatedTotal = $this->repository->sumVerifiedScore($dto->submissionId);

    // 3. Update status via Repository
    return $this->repository->updateStatus($dto->submissionId, 'REVIEWED', $calculatedTotal);
}
```

**Prinsip:**
- Service TIDAK boleh akses database langsung — selalu lewat Repository
- Service menerima DTO, bukan raw request
- Semua validasi bisnis ada di sini
- Jika perlu multiple repository, inject di constructor

---

## 4. Repository

**Lokasi:** `app/Repositories/`

**Tanggung Jawab:**
- Satu-satunya layer yang berinteraksi langsung dengan database
- CRUD operations
- Query kompleks (join, aggregate, where conditions)
- Tidak ada business logic — hanya data access

**Base Repository:**

```php
<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $record = $this->find($id);
        return $record ? $record->update($data) : false;
    }

    public function delete(int|string $id): bool
    {
        return $this->model->destroy($id) > 0;
    }
}
```

**Contoh: `ReviewRepository.php`**

```php
<?php

namespace App\Repositories;

use App\Models\Assessment;
use App\Models\ResponAssessment;
use App\Models\Pertanyaan;
use App\Models\Kategori;

class ReviewRepository extends BaseRepository
{
    public function __construct(Assessment $model)
    {
        parent::__construct($model);
    }

    public function getAssignedSubmissionsWithUser($reviewerId)
    {
        return $this->model->with('user')
            ->where('reviewer_id', $reviewerId)
            ->get();
    }

    public function hasUnverifiedAnswers($submissionId)
    {
        $totalQuestions = Pertanyaan::count();
        $verifiedAnswers = ResponAssessment::where('assessment_id', $submissionId)
            ->whereNotNull('manual_score')
            ->count();

        return $verifiedAnswers < $totalQuestions;
    }

    public function sumVerifiedScore($submissionId)
    {
        return ResponAssessment::where('assessment_id', $submissionId)
            ->sum('manual_score');
    }

    public function updateReviewData($submissionId, array $verifications)
    {
        \DB::transaction(function () use ($submissionId, $verifications) {
            foreach ($verifications as $ver) {
                ResponAssessment::where('id', $ver['id'])
                    ->where('assessment_id', $submissionId)
                    ->update([
                        'reviewer_scale' => $ver['scale_choice'],
                        'manual_score' => $ver['manual_score'],
                        'skor_validasi_reviewer' => $ver['manual_score'],
                        'verified_at' => now(),
                    ]);
            }
        });
    }

    public function updateStatus($id, $status, $calculatedTotal)
    {
        $assessment = $this->model->findOrFail($id);
        $assessment->update([
            'status' => $status,
            'final_score' => $calculatedTotal,
            'reviewed_at' => now(),
        ]);
        return $assessment;
    }
}
```

**Prinsip:**
- Repository menerima parameter primitif (id, array), bukan DTO
- Konversi DTO → array dilakukan di Service sebelum dikirim ke Repository
- Satu Repository biasanya terikat ke satu Model utama
- Query kompleks yang melibatkan model lain tetap boleh di Repository

---

## Alur Lengkap (Contoh: Reviewer Menyimpan Verifikasi Skor)

```
1. CLIENT mengirim POST /api/assessment/reviewer/tasks/{id}/save-scores
   Body: { scores: {...}, notes: {...} }

2. CONTROLLER (ReviewerController@saveScores)
   - Validasi: user harus role REVIEWER
   - Validasi: assessment harus exist dan status valid
   - Ambil input scores & notes dari request
   - Panggil: $this->assessmentService->saveReviewerScores($assessment, $scores, $notes)

3. SERVICE (AssessmentService@saveReviewerScores)
   - Terima parameter dari Controller
   - Lakukan business logic (validasi note min 20 karakter, dll)
   - Bentuk data yang siap disimpan
   - Panggil Repository untuk persist ke database

4. REPOSITORY (ReviewRepository / AssessmentRepository)
   - Eksekusi query INSERT/UPDATE ke database
   - Return hasil operasi

5. CONTROLLER menerima hasil dari Service
   - Return: $this->successResponse([], 'Skor berhasil disimpan.')
```

---

## Alur Lengkap (Contoh: Lock Review dengan DTO)

```
1. CLIENT mengirim POST /api/review/lock
   Body: { submission_id: 5 }

2. CONTROLLER
   - Validasi role
   - Bentuk DTO:
     $dto = new ReviewDTO();
     $dto->submissionId = $request->input('submission_id');
   - Panggil: $this->reviewService->lockReview($dto)

3. SERVICE (ReviewService@lockReview)
   - Terima DTO
   - Validasi bisnis: $this->repository->hasUnverifiedAnswers($dto->submissionId)
   - Kalkulasi: $this->repository->sumVerifiedScore($dto->submissionId)
   - Update: $this->repository->updateStatus($dto->submissionId, 'REVIEWED', $total)

4. REPOSITORY
   - hasUnverifiedAnswers(): COUNT query
   - sumVerifiedScore(): SUM query
   - updateStatus(): UPDATE query

5. CONTROLLER
   - Return response sukses/gagal
```

---

## Ringkasan Aturan

| Layer | Boleh | Tidak Boleh |
|-------|-------|-------------|
| **Controller** | Validasi input, bentuk DTO, panggil Service, return response | Business logic, query DB langsung |
| **DTO** | Menyimpan data, konversi toArray() | Logic apapun, validasi |
| **Service** | Business logic, validasi bisnis, panggil Repository, kalkulasi | Query DB langsung, return HTTP response |
| **Repository** | Query DB, CRUD, join, aggregate | Business logic, HTTP concerns |

---

## Dependency Injection

Semua dependency di-inject melalui constructor dan di-resolve otomatis oleh Laravel Service Container:

```php
// Controller menerima Service & Repository via constructor
public function __construct(
    AssessmentService $assessmentService,
    ReviewerRepository $reviewerRepository
) { ... }

// Service menerima Repository via constructor
public function __construct(ReviewRepository $repository)
{
    parent::__construct($repository);
}

// Repository menerima Model via constructor
public function __construct(Assessment $model)
{
    parent::__construct($model);
}
```

Tidak perlu manual instantiation — Laravel otomatis resolve dependency chain ini.
