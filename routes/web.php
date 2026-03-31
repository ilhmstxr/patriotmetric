<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReviewController;
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
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Profile Endpoints
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/baseline', [ProfileController::class, 'baseline']);
    Route::get('/profile/status', [ProfileController::class, 'status']);

    // Assessment Endpoints
    Route::get('/assessment/questions', [AssessmentController::class, 'questions']);
    Route::post('/assessment/answers', [AssessmentController::class, 'answers']);
    Route::post('/assessment/submit', [AssessmentController::class, 'submit']);
    Route::get('/assessment/preview', [AssessmentController::class, 'preview']);

    // Review Endpoints
    Route::get('/review/submissions', [ReviewController::class, 'submissions']);
    Route::get('/review/submissions/{id}', [ReviewController::class, 'submissionDetail']);
    Route::patch('/review/answers/{id}', [ReviewController::class, 'updateAnswer']);
    Route::post('/review/publish/{id}', [ReviewController::class, 'publish']);
});
