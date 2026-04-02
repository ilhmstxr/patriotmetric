<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;

Route::prefix('assessment')->group(function () {
    // Core Assessment
    Route::get('/questions', [AssessmentController::class, 'questions']);
    Route::post('/answers', [AssessmentController::class, 'answers']);
    Route::post('/submit', [AssessmentController::class, 'submit']);
    Route::get('/preview', [AssessmentController::class, 'preview']);

    // Rubrik
    Route::get('/rubrik/structure', [AssessmentController::class, 'getRubrikStructure']);
    Route::get('/rubrik/metadata', [AssessmentController::class, 'getCategoryMetadata']);
    Route::get('/rubrik/validate', [AssessmentController::class, 'validateRubrikConsistency']);

    // Submitter
    Route::get('/details', [AssessmentController::class, 'getTaskDetails']);
    Route::get('/preview-score', [AssessmentController::class, 'getPreviewScore']);
    Route::post('/draft', [AssessmentController::class, 'saveDraft']);

    // Reviewer
    Route::get('/review/final-score', [AssessmentController::class, 'getFinalScore']);
    Route::post('/review/assign', [AssessmentController::class, 'assignReviewersToSubmissions']);
    Route::get('/review/assigned', [AssessmentController::class, 'getAssignedSubmissions']);
    Route::post('/review/calculate', [AssessmentController::class, 'calculateVerifiedFinalScore']);
    Route::post('/review/finalize', [AssessmentController::class, 'finalizeReview']);
    Route::patch('/review/verify-indicator', [AssessmentController::class, 'verifySingleIndicator']);

    // profil

});


