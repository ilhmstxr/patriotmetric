<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;

Route::prefix('assessment')->group(function () {
    // Core Assessment
    Route::get('/questions', [AssessmentController::class, 'questions'])->name('assessment.questions');
    Route::post('/answers', [AssessmentController::class, 'answers'])->name('assessment.answers');
    Route::post('/submit', [AssessmentController::class, 'submit'])->name('assessment.submit');
    Route::get('/preview', [AssessmentController::class, 'preview'])->name('assessment.preview');

    // Rubrik
    Route::get('/rubrik/structure', [AssessmentController::class, 'getRubrikStructure'])->name('assessment.rubrik.structure');
    Route::get('/rubrik/metadata', [AssessmentController::class, 'getCategoryMetadata'])->name('assessment.rubrik.metadata');
    Route::get('/rubrik/validate', [AssessmentController::class, 'validateRubrikConsistency'])->name('assessment.rubrik.validate');

    // Submitter
    Route::get('/details', [AssessmentController::class, 'getTaskDetails'])->name('assessment.details');
    Route::get('/preview-score', [AssessmentController::class, 'getPreviewScore'])->name('assessment.preview-score');
    Route::post('/draft', [AssessmentController::class, 'saveDraft'])->name('assessment.draft');

    // Reviewer
    Route::get('/review/final-score', [AssessmentController::class, 'getFinalScore'])->name('assessment.review.final-score');
    Route::post('/review/assign', [AssessmentController::class, 'assignReviewersToSubmissions'])->name('assessment.review.assign');
    Route::get('/review/assigned', [AssessmentController::class, 'getAssignedSubmissions'])->name('assessment.review.assigned');
    Route::post('/review/calculate', [AssessmentController::class, 'calculateVerifiedFinalScore'])->name('assessment.review.calculate');
    Route::post('/review/finalize', [AssessmentController::class, 'finalizeReview'])->name('assessment.review.finalize');
    Route::patch('/review/verify-indicator', [AssessmentController::class, 'verifySingleIndicator'])->name('assessment.review.verify-indicator');

    // profil

});


