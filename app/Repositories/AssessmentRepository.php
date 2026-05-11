<?php

namespace App\Repositories;

use App\Models\Agama;
use App\Models\Identitas;
use App\Models\Institusi;
use App\Models\Assessment; // Gunakan PascalCase
use App\Models\ResponAssessment;
use App\Models\Kategori;
use App\Models\OpsiJawaban;
use App\Models\Pertanyaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssessmentRepository extends BaseRepository
{
    public function __construct(Assessment $model)
    {
        parent::__construct($model);
    }

    public function findActiveAssessmentByUserId($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    public function findActiveAssessmentByUserIdAndYear($userId, $year)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('tahun_periode', $year)
            ->with(['institusi', 'identitas.agamas'])
            ->first();
    }

    public function findLatestAssessmentByUserId($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('tahun_periode', 'desc')
            ->first();
    }

    public function getAgamasByIdentitasId($identitasId)
    {
        return Agama::where('identitas_id', $identitasId)->get();
    }

    /**
     * AUTO-SAVE (Upsert Batch)
     */
    // Contoh penggunaan di AssessmentRepository.php
    public function upsertJawaban(array $payload)
    {
        // Set default 0 hanya jika skor_sistem tidak dikirim dari service
        $payload['skor_sistem'] = $payload['skor_sistem'] ?? 0;

        return ResponAssessment::updateOrCreate(
            [
                'assessment_id' => $payload['assessment_id'],
                'pertanyaan_id' => $payload['pertanyaan_id']
            ],
            $payload
        );
    }
    /**
     * Logic Pencocokan (Kondisi 2): Mencari opsi_jawaban berdasarkan value
     * Alur: Cari opsi yang nilainya <= input, ambil yang paling mendekati (terbesar)
     */
    public function findMatchingOpsiByValue($pertanyaanId, $inputValue)
    {
        return OpsiJawaban::where('pertanyaan_id', $pertanyaanId)
            ->where('value', '<=', (int) $inputValue)
            ->orderBy('value', 'desc')
            ->first();
    }

    /**
     * COUNT: Total jawaban valid (sudah diisi claim_value dan evidence_url)
     */
    public function countValidAnswers($assessment)
    {
        return ResponAssessment::where('assessment_id', $assessment->id)
            ->whereNotNull('jawaban_id')
            // ->whereNotNull('evidence_url') // Aktifkan jika URL wajib
            ->count();
    }

    /**
     * PREVIEW: Mengambil seluruh jawaban dari satu Assessment
     */
    public function getAllAnswersByAssessment($assessmentId)
    {
        return ResponAssessment::where('assessment_id', $assessmentId)->get();
    }

    /**
     * Mencari record identitas berdasarkan Assessment_id.
     */
    public function findIdentitasByAssessmentId(int $AssessmentId)
    {
        return Identitas::where('Assessment_id', $AssessmentId)->first();
    }

    public function findInstitusiById(string $id)
    {
        return Institusi::find($id);
    }

    public function updateInstitusi(string $id, array $data)
    {
        return Institusi::where('id', $id)->update($data);
    }
    /**
     * Logic Create or Update (Upsert) untuk tabel Identitas.
     */
    public function upsertIdentitas(int $AssessmentId, array $data)
    {
        return Identitas::updateOrCreate(
            ['Assessment_id' => $AssessmentId],
            $data
        );
    }

    /**
     * Logic Upsert untuk tabel Agama berdasarkan identitas_id dan nama agama.
     */
    public function upsertAgama(int $identitasId, string $agama, int $jumlah)
    {
        return Agama::updateOrCreate(
            [
                'identitas_id' => $identitasId,
                'agama' => $agama
            ],
            ['jumlah' => $jumlah]
        );
    }

    public function getAssignedAssessmentsByReviewer(int $reviewerId)
    {
        return $this->model
            ->where('reviewer_id', $reviewerId)
            // ->whereIn('status', ['ACTIVE', 'IN_PROGRESS', 'SUBMITTED', 'GRADED']) // Reviewer tidak boleh melihat yang masih ACTIVE/IN_PROGRESS
            ->with(['institusi' => function ($query) {
                $query->select('id', 'nama_institusi', 'jenis_institusi');
            }])
            ->orderBy('updated_at', 'desc') // Yang terbaru diubah ada di atas
            ->get();
    }

    public function isUserActive(int $userId): bool
    {
        $user = User::find($userId);
        return $user && in_array($user->status, ['ACTIVE', 'IN_PROGRESS', 'SUBMITTED', 'GRADED']);
    }

    public function getProfilePeserta(int $pesertaId)
    {
        return $this->model
            ->where('user_id', $pesertaId)
            ->with(['institusi', 'identitas.agamas', 'identitas'])
            ->first();  
    }

    public function getDetailAssessmentByReviewer(int $reviewerId, int $pesertaId)
    {
        return $this->model
            ->where('reviewer_id', $reviewerId)
            ->where('id', $pesertaId)
            ->with([
                'user',
                'institusi',
                'identitas.agamas',
                'jawabans.pertanyaan.kategori',
                'jawabans.pertanyaan.OpsiJawaban',
                'jawabans.jawabanOpsi',
            ])
            ->first();
    }

    /**
     * Get all answers for an assessment grouped by category, including pertanyaan & opsi data
     */
    public function getAnswersGroupedByCategory(int $assessmentId)
    {
        return ResponAssessment::where('assessment_id', $assessmentId)
            ->with([
                'pertanyaan.kategori',
                'pertanyaan.OpsiJawaban',
                'jawabanOpsi',
            ])
            ->get();
    }

    /**
     * Update status of an assessment
     */
    public function updateStatusAssessment(int $assessmentId, string $status)
    {
        $assessment = $this->model->find($assessmentId);
        if ($assessment) {
            $assessment->update(['status' => $status]);
            // Sync user status
            User::where('id', $assessment->user_id)->update(['status' => $status]);
        }
        return true;
    }
    public function getIdentitasWithAgama(int $assessmentId)
    {
        return Identitas::with('agamas')
            ->where('Assessment_id', $assessmentId)
            ->first();
    }

    public function getLatestJawabanUpdate(int $assessmentId)
    {
        return ResponAssessment::where('assessment_id', $assessmentId)->max('updated_at');
    }

    public function updateRekapSkor(int $assessmentId, array $rekap)
    {
        return $this->model->where('id', $assessmentId)->update([
            'skor_rekap_json' => json_encode($rekap),
        ]);
    }

    public function batchUpdateStatusByYear(string $tahun, array $fromStatuses, string $toStatus)
    {
        return $this->model->where('tahun_periode', $tahun)
            ->whereIn('status', $fromStatuses)
            ->update(['status' => $toStatus]);
    }

    public function countValidReviewerScores(int $assessmentId)
    {
        return \App\Models\ResponAssessment::where('assessment_id', $assessmentId)
            ->whereNotNull('skor_validasi_reviewer')
            ->count();
    }
}
