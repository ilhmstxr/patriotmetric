<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CmsAssetController;
use App\Http\Controllers\ComproPreviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReviewController;
use App\Services\RubrikService;
use App\Services\SubmissionService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Temporary debug route - DELETE after debugging
require __DIR__ . '/debug-admin.php';

// CMS assets (public, no auth)
Route::get('/cms-assets/{path}', [CmsAssetController::class, 'show'])
    ->where('path', '.*')
    ->name('cms.asset');

// public routes / compro
Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/tim', function () {
    return view('tim');
});

Route::get('/penghargaan', function () {
    return view('penghargaan');
});

Route::get('/pengumuman', function () {
    return view('pengumuman');
});

Route::get('/panduan', function () {
    return view('panduan');
});

// Admin preview route for compro pages (authenticated only)
Route::get('/admin/compro-preview/{page}', [ComproPreviewController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('compro.preview');

// Auth pages (public - no middleware needed, auth is handled via API token)
Route::get('/masuk', function () {
    return view('auth.masuk');
})->name('login');

Route::get('/daftar', function () {
    return view('auth.daftar');
});

Route::get('/lupa-sandi', fn() => view('auth.lupa-sandi'));

Route::get('/reset-password/{token}', fn($token) => view('auth.reset-password', compact('token')));

Route::get('/verifikasi', function () {
    return view('auth.verifikasi');
})->name('verifikasi');

Route::get('/cek-email', function () {
    return view('auth.cek-email');
})->name('cek-email');

Route::get('/verifikasi-gagal', function () {
    return view('auth.verifikasi-gagal');
})->name('verifikasi-gagal');

// Dashboard pages (peserta)
Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard.index');

    Route::get('/rubrik', function () {
        return view('dashboard.rubrik');
    })->name('dashboard.rubrik');

    Route::get('/hasil', function () {
        return view('dashboard.hasil');
    })->name('dashboard.hasil');

    Route::get('/panduan', function () {
        return view('dashboard.panduan');
    })->name('dashboard.panduan');
});

// Reviewer pages
Route::prefix('reviewer')->group(function () {
    Route::get('/', function () {
        return view('reviewer.panduan');
    })->name('reviewer.index');

    Route::get('/dashboard', function () {
        return view('reviewer.index');
    })->name('reviewer.dashboard');

    Route::get('/peserta/{id}', function ($id) {
        return view('reviewer.detail', compact('id'));
    })->name('reviewer.peserta_detail');
});

// Admin assessment detail (readonly reviewer view)
Route::get('/admin/assessment-detail/{id}', function ($id) {
    return view('reviewer.detail', ['id' => $id, 'adminReadonly' => true]);
})->name('admin.assessment.detail');

Route::get('/admin/api/assessment/{id}', function ($id) {
    $assessment = \App\Models\Assessment::with(['institusi', 'identitas.agamas', 'jawabans.pertanyaan', 'jawabans.jawabanOpsi', 'user'])->findOrFail($id);
    $service = app(\App\Services\AssessmentService::class);

    if ($assessment->reviewer_id) {
        try {
            $result = $service->getDetailReviewTasks($assessment->reviewer_id, $id);
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Throwable $e) {}
    }

    $allPertanyaan = app(\App\Repositories\PertanyaanRepository::class)->getPertanyaanWithOpsiJawaban();
    $identitas = $assessment->identitas;

    $jawabanMap = [];
    foreach ($assessment->jawabans as $jawaban) {
        $jawabanMap[$jawaban->pertanyaan_id] = [
            'jawaban_id' => $jawaban->jawaban_id,
            'jawaban_teks' => $jawaban->jawaban_teks,
            'tautan_bukti_drive' => $jawaban->tautan_bukti_drive,
            'skor_sistem' => $jawaban->skor_sistem,
            'skor_validasi_reviewer' => $jawaban->skor_validasi_reviewer,
            'opsi_dipilih' => $jawaban->jawabanOpsi ? [
                'id' => $jawaban->jawabanOpsi->id,
                'opsi_jawaban' => $jawaban->jawabanOpsi->opsi_jawaban,
                'keterangan' => $jawaban->jawabanOpsi->keterangan,
                'value' => $jawaban->jawabanOpsi->value,
            ] : null,
        ];
    }

    $rubrikData = [];
    foreach ($allPertanyaan as $pertanyaan) {
        $kategoriName = $pertanyaan->kategori->nama_kategori ?? 'Tanpa Kategori';
        if (!isset($rubrikData[$kategoriName])) {
            $rubrikData[$kategoriName] = [
                'kategori' => $kategoriName,
                'pertanyaan_count' => 0,
                'bobot_maksimal' => 0,
                'pertanyaan' => [],
            ];
        }
        $rubrikData[$kategoriName]['pertanyaan_count']++;
        $rubrikData[$kategoriName]['bobot_maksimal'] += 5;
        $rubrikData[$kategoriName]['pertanyaan'][] = [
            'id' => $pertanyaan->id,
            'kode_pertanyaan' => $pertanyaan->kode_pertanyaan,
            'teks_pertanyaan' => $pertanyaan->teks_pertanyaan,
            'kebutuhan_bukti' => $pertanyaan->kebutuhan_bukti,
            'tipe' => $pertanyaan->tipe,
            'opsi_jawaban' => $pertanyaan->OpsiJawaban->map(fn ($opsi) => [
                'id' => $opsi->id,
                'opsi_jawaban' => $opsi->opsi_jawaban,
                'keterangan' => $opsi->keterangan,
                'value' => $opsi->value,
            ])->toArray(),
            'jawaban_peserta' => $jawabanMap[$pertanyaan->id] ?? null,
        ];
    }

    return response()->json(['success' => true, 'data' => [
        'Assessment' => [
            'id' => $assessment->id,
            'status' => $assessment->status,
            'total_skor_sistem' => $assessment->total_skor_sistem,
            'total_skor_akhir' => $assessment->total_skor_akhir,
            'tahun_periode' => $assessment->tahun_periode,
        ],
        'institusi' => $assessment->institusi,
        'profil_peserta' => $identitas ? [
            'visi' => $identitas->visi,
            'misi' => $identitas->misi,
            'jml_fakultas' => $identitas->jml_fakultas,
            'jml_prodi' => $identitas->jml_prodi,
            'jml_dosen' => $identitas->jml_dosen,
            'jml_tendik' => $identitas->jml_tendik,
            'jml_mhs' => $identitas->jml_mahasiswa,
            'jml_ukm' => $identitas->jml_ukm,
            'jml_ormawa' => $identitas->jml_ormawa ?? 0,
            'berkas_pendukung' => $identitas->legal_documents,
            'agama' => $identitas->agamas->mapWithKeys(fn ($item) => [strtolower($item->agama) => $item->jumlah]),
        ] : null,
        'rubrik' => array_values($rubrikData),
        'nama_pic' => $assessment->nama_pic,
        'jabatan_pic' => $assessment->jabatan_pic,
        'no_hp_pic' => $assessment->no_hp_pic,
        'email_pic' => $assessment->user->email ?? null,
    ]]);
})->name('admin.assessment.api');
