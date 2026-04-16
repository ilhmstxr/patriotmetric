<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubmitterController;
use App\Http\Controllers\ReviewerController;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});

// Profile Routes
Route::prefix('profile')->group(function () {
/* DONE */    Route::get('/', [ProfileController::class, 'index'])->name('api.profile');
/* DONE */    Route::post('/baseline', [ProfileController::class, 'baseline'])->name('api.profile.baseline');
/* DONE */    Route::get('/status', [ProfileController::class, 'status'])->name('api.profile.status');
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

Route::prefix('api/assessment/submitter')->group(function () {
    Route::get('/steps', [SubmitterController::class, 'steps'])->name('api.submitter.steps');
    Route::get('/questions/{cat_id}', [SubmitterController::class, 'questions'])->name('api.submitter.questions');
    Route::post('/save-progress', [SubmitterController::class, 'saveProgress'])->name('api.submitter.save-progress');
    Route::get('/preview-category/{cat_id}', [SubmitterController::class, 'previewCategory'])->name('api.submitter.preview-category');
    Route::post('/finalize', [SubmitterController::class, 'finalize'])->name('api.submitter.finalize');
});

Route::prefix('api/assessment/reviewer')->group(function () {
    Route::get('/assignments', [ReviewerController::class, 'assignments'])->name('api.reviewer.assignments');
    Route::get('/questions/{sub_id}/{cat_id}', [ReviewerController::class, 'questions'])->name('api.reviewer.questions');
    Route::post('/save-verification', [ReviewerController::class, 'saveVerification'])->name('api.reviewer.save-verification');
    Route::post('/finalize/{sub_id}', [ReviewerController::class, 'finalize'])->name('api.reviewer.finalize');
});
