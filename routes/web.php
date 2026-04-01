<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReviewController;
use App\Services\RubrikService;
use App\Services\SubmissionService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/visi-misi', function () {
    return view('visi-misi');
});

Route::get('/tim', function () {
    return view('tim');
});

Route::get('/pemenang', function () {
    return view('pemenang');
});

Route::get('/panduan', function () {
    return view('panduan');
});

Route::get('/masuk', function () {
    return view('auth.masuk');
});

Route::get('/daftar', function () {
    return view('auth.daftar');
});


Route::prefix('api')->group(function () {
    // Auth Endpoints
    // Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
    // Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    // Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Profile Endpoints
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/baseline', [ProfileController::class, 'baseline'])->name('profile.baseline');
    Route::get('/profile/status', [ProfileController::class, 'status'])->name('profile.status');

    // Assessment Endpoints
    Route::get('/assessment/questions', [AssessmentController::class, 'questions'])->name('assessment.questions');
    Route::post('/assessment/answers', [AssessmentController::class, 'answers'])->name('assessment.answers');
    Route::post('/assessment/submit', [AssessmentController::class, 'submit'])->name('assessment.submit');
    Route::get('/assessment/preview', [AssessmentController::class, 'preview'])->name('assessment.preview');

    // Review Endpoints
    Route::get('/review/submissions', [ReviewController::class, 'submissions'])->name('review.submissions');
    Route::get('/review/submissions/{id}', [ReviewController::class, 'submissionDetail'])->name('review.submissionDetail');
    Route::patch('/review/answers/{id}', [ReviewController::class, 'updateAnswer'])->name('review.updateAnswer');
    Route::post('/review/publish/{id}', [ReviewController::class, 'publish'])->name('review.publish');
});

route::get('/sandbox-test', function () {

    $rubrikService = app(RubrikService::class);
    $submissionService = app(SubmissionService::class);
    // --- TEST 1: Pengecekan View Pertanyaan & Opsi ---
    // Skenario: React butuh data untuk merender Accordion
    $viewData = [
        'title' => 'Struktur Rubrik PatriotMetric',
        'data' => $rubrikService->getRubrikStructure(), // Asumsi method ini ada untuk fetch repo
    ];

    return response()->json($viewData);
});
