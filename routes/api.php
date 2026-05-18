<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\VerificationController;

// --- Public Routes ---
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::get('/check-institusi', [AuthController::class, 'checkInstitusi'])->name('api.auth.check-institusi');
    Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verify'])->name('api.auth.verify-email');
});

// --- Protected Routes ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth Utils
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        Route::get('/me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::post('/verification', [VerificationController::class, 'submit'])->name('api.auth.verification');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('api.auth.profile.update');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('api.auth.change-password');
        Route::post('/resend-verification', [EmailVerificationController::class, 'resend'])
            ->middleware('throttle:3,5')
            ->name('api.auth.resend-verification');
    });

    // User Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('api.profile');
        Route::post('/baseline', [ProfileController::class, 'baseline'])->name('api.profile.baseline');
        Route::get('/status', [ProfileController::class, 'status'])->name('api.profile.status');
    });

    // Tahap 1: Baseline / Daftar Ulang (Pemberkasan)
    Route::post('baseline', [AssessmentController::class, 'storeBaseline'])->name('api.peserta.baseline');

    // Tahap 3: Assessment (Single Form & Auto-Save)
    Route::prefix('assessment/peserta')->group(function () {
        Route::get('/questions', [AssessmentController::class, 'getAllQuestions'])->name('api.peserta.questions');
        Route::get('/questions/version', [AssessmentController::class, 'getQuestionsVersion'])->name('api.peserta.questions.version');
        Route::post('/finalize', [AssessmentController::class, 'finalize'])->name('api.peserta.finalize');
        Route::get('/preview-results', [AssessmentController::class, 'previewResults'])->name('api.peserta.preview-results');
        Route::post('/save-answer', [AssessmentController::class, 'saveJawaban'])->name('api.peserta.save-answer');
        Route::post('/save-draft', [AssessmentController::class, 'saveDraft'])->name('api.peserta.save-draft');
        Route::get('/current-progress', [AssessmentController::class, 'getProgress'])->name('api.peserta.progress');
        Route::get('/hasil', [AssessmentController::class, 'getHasil'])->name('api.peserta.hasil');
    });

    // Reviewer
    Route::prefix('assessment/reviewer')->group(function () {
        Route::get('/tasks', [ReviewerController::class, 'getAssignedTasks'])->name('api.reviewer.tasks');
        Route::get('/tasks/detail/{pesertaId}', [ReviewerController::class, 'getDetailTasks'])->name('api.reviewer.detail');
        Route::post('/tasks/{pesertaId}/save-scores', [ReviewerController::class, 'saveScores'])->name('api.reviewer.save-scores');
        Route::post('/tasks/{pesertaId}/finalize', [ReviewerController::class, 'finalizeReview'])->name('api.reviewer.finalize');
    });
});
