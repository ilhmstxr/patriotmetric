<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubmitterController;

// use App\Http\Controllers\ReviewerController;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});

// Profile Routes
Route::prefix('profile')->group(function () {
    /* DONE */
    Route::get('/', [ProfileController::class, 'index'])->name('api.profile');
    /* DONE */
    Route::post('/baseline', [ProfileController::class, 'baseline'])->name('api.profile.baseline');
    /* DONE */
    Route::get('/status', [ProfileController::class, 'status'])->name('api.profile.status');
});

// Assessment Routes
// Route::prefix('assessment')->group(function () {
//     // Core Assessment
// /* DONE */    Route::get('/questions', [AssessmentController::class, 'questions'])->name('api.assessment.questions');
// /* DONE */    Route::post('/answers', [AssessmentController::class, 'answers'])->name('api.assessment.answers');
// /* DONE */    Route::post('/submit', [AssessmentController::class, 'submit'])->name('api.assessment.submit');
// /* DONE */    Route::get('/preview', [AssessmentController::class, 'preview'])->name('api.assessment.preview');

//     // Rubrik (Tambahan / Belum Terdokumentasi)
//     Route::get('/rubrik/structure', [AssessmentController::class, 'getRubrikStructure'])->name('api.assessment.rubrik.structure');
//     Route::get('/rubrik/metadata', [AssessmentController::class, 'getCategoryMetadata'])->name('api.assessment.rubrik.metadata');
//     Route::get('/rubrik/validate', [AssessmentController::class, 'validateRubrikConsistency'])->name('api.assessment.rubrik.validate');

//     // Submitter (Tambahan / Belum Terdokumentasi)
//     Route::get('/details', [AssessmentController::class, 'getTaskDetails'])->name('api.assessment.details');
//     Route::get('/preview-score', [AssessmentController::class, 'getPreviewScore'])->name('api.assessment.preview-score');
//     Route::post('/draft', [AssessmentController::class, 'saveDraft'])->name('api.assessment.draft');
// });

// // Reviewer Routes
// Route::prefix('review')->group(function () {
//     // Sesuai dokumen/api.md
//     Route::get('/submissions', [ReviewController::class, 'index'])->name('api.review.submissions');
//     Route::get('/submissions/{id}', [ReviewController::class, 'show'])->name('api.review.submissions.show');
//     Route::patch('/answers/{id}', [ReviewController::class, 'updateAnswer'])->name('api.review.answers.update');
//     Route::post('/publish/{id}', [ReviewController::class, 'publish'])->name('api.review.publish');

//     // Legacy AssessmentController (Belum Terdokumentasi / Belum dipindah ke Standar)
//     Route::get('/final-score', [AssessmentController::class, 'getFinalScore'])->name('api.review.legacy.final-score');
//     Route::post('/assign', [AssessmentController::class, 'assignReviewersToSubmissions'])->name('api.review.legacy.assign');
//     Route::get('/assigned', [AssessmentController::class, 'getAssignedSubmissions'])->name('api.review.legacy.assigned');
//     Route::post('/calculate', [AssessmentController::class, 'calculateVerifiedFinalScore'])->name('api.review.legacy.calculate');
//     Route::post('/finalize', [AssessmentController::class, 'finalizeReview'])->name('api.review.legacy.finalize');
//     Route::patch('/verify-indicator', [AssessmentController::class, 'verifySingleIndicator'])->name('api.review.legacy.verify-indicator');
// });


// --- Authentication & Account ---
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('api.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('api.logout')->middleware('auth:sanctum');
});

// --- User Profile ---
// Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
Route::get('/', [AuthController::class, 'profile'])->name('api.profile');

// TAHAP 1: Baseline / Daftar Ulang (Pemberkasan)
// Parameter assessment_id dihapus dari URL, ambil dari Auth di Controller

// daftar ulang
/* DONE */Route::post('baseline/{userId}', [SubmitterController::class, 'storeBaseline'])->name('api.submitter.baseline');
// });

// --- Tahap 3: Assessment (Single Form & Auto-Save) ---
// Route::middleware(['auth:sanctum', 'check.baseline.verified'])->prefix('assessment/submitter')->group(function () {
Route::prefix('assessment/submitter')->group(function () {
    // 1. Ambil semua pertanyaan (Single Form)
    // Mendukung naked URL (/questions) atau berparameter (/questions/99)
    Route::get('/questions/{assessmentId}', [SubmitterController::class, 'getAllQuestions'])->name('api.submitter.questions');

    // 2. Auto-save (Dipanggil tiap 5 menit atau saat ganti input)
    Route::post('/auto-save/{assessment_id?}', [SubmitterController::class, 'autoSaveProgress'])->name('api.submitter.auto-save');

    // 3. Final Lock (Submit Akhir)
    Route::post('/finalize/{assessmentId}', [SubmitterController::class, 'finalize'])->name('api.submitter.finalize');

    // 4. Preview Nilai (Setelah Finalize atau Real-time)
    Route::get('/preview-results/{assessmentId}', [SubmitterController::class, 'previewResults'])->name('api.submitter.preview-results');

    // 5. Progress Check (Opsional: Untuk Progress Bar Dashboard)
    Route::get('/current-progress/{assessment_id?}', [SubmitterController::class, 'getProgress'])->name('api.submitter.progress');

    Route::post('/save-answer/{userId}', [SubmitterController::class, 'saveJawaban'])->name('api.submitter.save-answer');    
});


// Authentication
// submitter - only
// Route::prefix('auth')->group(function () {
//     Route::post('register', [UserController::class, 'register'])->name('api.register');
//     Route::post('login', [UserController::class, 'login'])->name('api.login');
//     Route::post('logout', [UserController::class, 'logout'])->name('api.logout');
// });

// // Profile
// Route::prefix('profile')->group(function () {
//     Route::post('profile', [UserController::class, 'profile'])->name('api.profile');
// });



Route::prefix('assessment/reviewer')->group(function () {
    /* BUG */
    Route::get('/assignments', [ReviewController::class, 'assignments'])->name('api.reviewer.assignments');
    Route::get('/questions/{sub_id}/{cat_id}', [ReviewController::class, 'questions'])->name('api.reviewer.questions');
    Route::post('/save-verification', [ReviewController::class, 'saveVerification'])->name('api.reviewer.save-verification');
    Route::post('/finalize/{sub_id}', [ReviewController::class, 'finalize'])->name('api.reviewer.finalize');
});
