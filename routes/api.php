<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\AssessmentController;

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
/* DONE */
Route::post('baseline/{userId}', [AssessmentController::class, 'storeBaseline'])->name('api.peserta.baseline');
// });

// --- Tahap 3: Assessment (Single Form & Auto-Save) ---
// Route::middleware(['auth:sanctum', 'check.baseline.verified'])->prefix('assessment/peserta')->group(function () {
Route::prefix('assessment/peserta')->group(function () {
    Route::get('/questions/{assessmentId}', [AssessmentController::class, 'getAllQuestions'])->name('api.peserta.questions'); 
    Route::post('/finalize/{assessmentId}', [AssessmentController::class, 'finalize'])->name('api.peserta.finalize');
    Route::get('/preview-results/{assessmentId}', [AssessmentController::class, 'previewResults'])->name('api.peserta.preview-results');    
    Route::post('/save-answer/{userId}', [AssessmentController::class, 'saveJawaban'])->name('api.peserta.save-answer');

    Route::get('/current-progress/{assessment_id?}', [AssessmentController::class, 'getProgress'])->name('api.peserta.progress');
    Route::post('/auto-save/{assessment_id?}', [AssessmentController::class, 'autoSaveProgress'])->name('api.peserta.auto-save');
});




Route::prefix('assessment/reviewer')->group(function () {
    Route::get('/tasks', [ReviewerController::class, 'getAssignedTasks'])->name('api.reviewer.tasks');
    Route::get('/tasks/reviewer/peseta/{pesertaId}', [ReviewerController::class, 'getDetailTasks'])->name('api.reviewer.detail');
    // Route::get('/tasks', [ReviewerController::class, 'getAssignedTasks'])->name('api.reviewer.tasks');
    
    /* BUG */
    // Route::get('/assignments', [ReviewerController::class, 'assignments'])->name('api.reviewer.assignments');
    // Route::get('/questions/{sub_id}/{cat_id}', [ReviewerController::class, 'questions'])->name('api.reviewer.questions');
    // Route::post('/save-verification', [ReviewerController::class, 'saveVerification'])->name('api.reviewer.save-verification');
    // Route::post('/finalize/{sub_id}', [ReviewerController::class, 'finalize'])->name('api.reviewer.finalize');
});
