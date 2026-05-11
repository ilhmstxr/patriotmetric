<?php

namespace App\Http\Controllers;

use App\Services\AssessmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerController extends Controller
{
    use ApiResponse;

    protected $assessmentService;

    public function __construct(AssessmentService $assessmentService)
    {
        $this->assessmentService = $assessmentService;
    }



    private function getErrorCode(\Throwable $e)
    {
        $code = $e->getCode();
        return (is_numeric($code) && $code >= 400 && $code < 600) ? $code : 500;
    }

    /**
     * Endpoint: GET /api/reviewer/tasks
     * Mengambil daftar tugas (plottingan) institusi yang harus dinilai.
     */
    public function getAssignedTasks(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user || strtolower($user->role) !== 'reviewer') {
                throw new \Exception("Unauthorized: Akses khusus untuk Reviewer.", 403);
            }
            
            $reviewer = \App\Models\Reviewer::where('user_id', $user->id)->first();
            if (!$reviewer) {
                throw new \Exception("Profil Reviewer tidak ditemukan.", 404);
            }
            $reviewerId = $reviewer->id;

            // Eksekusi Service
            $result = $this->assessmentService->getAssignedReviews($reviewerId);

            return $this->successResponse($result, 'Daftar plottingan tugas berhasil diambil.', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }

    public function getDetailTasks(Request $request, $pesertaId)
    {
        try {
            $user = $request->user();
            if (!$user || strtolower($user->role) !== 'reviewer') {
                throw new \Exception("Unauthorized: Akses khusus untuk Reviewer.", 403);
            }
            
            $reviewer = \App\Models\Reviewer::where('user_id', $user->id)->first();
            if (!$reviewer) {
                throw new \Exception("Profil Reviewer tidak ditemukan.", 404);
            }
            $reviewerId = $reviewer->id;

            // Eksekusi Service
            $result = $this->assessmentService->getDetailReviewTasks($reviewerId, $pesertaId);

            return $this->successResponse($result, 'Daftar plottingan tugas berhasil diambil.', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }

    /**
     * Endpoint: POST /api/assessment/reviewer/tasks/{pesertaId}/save-scores
     * Menyimpan skor validasi reviewer per indikator dan mengupdate rekap skor JSON.
     * Body: { scores: { pertanyaan_id: skor, ... }, notes: { pertanyaan_id: catatan, ... } }
     */
    public function saveScores(Request $request, $pesertaId)
    {
        try {
            $user = $request->user();
            if (!$user || strtolower($user->role) !== 'reviewer') {
                throw new \Exception("Unauthorized: Akses khusus untuk Reviewer.", 403);
            }

            $assessment = \App\Models\Assessment::where('id', $pesertaId)
                ->whereIn('status', ['SUBMITTED', 'IN_PROGRESS', 'GRADED'])
                ->latest()
                ->firstOrFail();

            $scores = $request->input('scores', []);
            $notes  = $request->input('notes', []);

            \Illuminate\Support\Facades\DB::transaction(function () use ($assessment, $scores, $notes) {
                foreach ($scores as $pertanyaanId => $skor) {
                    if ($skor === null || $skor === '') continue;

                    $skor = min(5, max(0, (int) $skor));

                    \App\Models\ResponAssessment::updateOrCreate(
                        [
                            'assessment_id' => $assessment->id,
                            'pertanyaan_id' => (int) $pertanyaanId,
                        ],
                        [
                            'skor_validasi_reviewer' => $skor,
                            'note_reviewer'          => $notes[$pertanyaanId] ?? null,
                        ]
                    );
                }
            });

            // Perbarui rekap skor JSON setelah skor reviewer disimpan
            $this->assessmentService->recapAndSaveSkor($assessment);

            return $this->successResponse([], 'Skor berhasil disimpan dan rekap diperbarui.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }

    /**
     * Endpoint: POST /api/assessment/reviewer/tasks/{pesertaId}/finalize
     * Memfinalisasi penilaian: set status GRADED dan lock rekap skor akhir.
     */
    public function finalizeReview(Request $request, $pesertaId)
    {
        try {
            $user = $request->user();
            if (!$user || strtolower($user->role) !== 'reviewer') {
                throw new \Exception("Unauthorized: Akses khusus untuk Reviewer.", 403);
            }

            $assessment = \App\Models\Assessment::where('id', $pesertaId)
                ->whereIn('status', ['SUBMITTED', 'IN_PROGRESS'])
                ->latest()
                ->firstOrFail();

            // Cek semua pertanyaan sudah ada skor dari reviewer
            $totalPertanyaan = \App\Models\Pertanyaan::count();
            $totalDinilai = \App\Models\ResponAssessment::where('assessment_id', $assessment->id)
                ->whereNotNull('skor_validasi_reviewer')
                ->count();

            if ($totalDinilai < $totalPertanyaan) {
                $belum = $totalPertanyaan - $totalDinilai;
                throw new \Exception("Finalisasi gagal: Masih ada {$belum} indikator yang belum diberi skor.", 422);
            }

            // Update status ke GRADED
            $assessment->update(['status' => 'GRADED']);

            // Update rekap skor final
            $this->assessmentService->recapAndSaveSkor($assessment);

            return $this->successResponse([], 'Penilaian berhasil difinalisasi. Status peserta berubah menjadi GRADED.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }
}
