<?php

namespace App\Repositories;

use App\Models\Agama;
use App\Models\Identitas;
use App\Models\Institusi;
use App\Models\Pengumpulan; // Gunakan PascalCase
use App\Models\PengumpulanJawaban;
use App\Models\Kategori;
use App\Models\opsiJawaban;
use App\Models\Pertanyaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AssessmentRepository extends BaseRepository
{
    public function __construct(Pengumpulan $model)
    {
        parent::__construct($model);
    }

    public function findActiveAssessmentByUserId($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('tahun_periode', date('Y')) // Opsional: Filter tahun
            ->first();
    }

    /**
     * AUTO-SAVE (Upsert Batch)
     */
    // Contoh penggunaan di AssessmentRepository.php
    public function upsertJawaban(array $payload)
    {
        // Set default 0 hanya jika skor_sistem tidak dikirim dari service
        $payload['skor_sistem'] = $payload['skor_sistem'] ?? 0;

        return PengumpulanJawaban::updateOrCreate(
            [
                'submission_id' => $payload['submission_id'],
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
        return opsiJawaban::where('pertanyaan_id', $pertanyaanId)
            ->where('value', '<=', (int) $inputValue)
            ->orderBy('value', 'desc')
            ->first();
    }

    /**
     * COUNT: Total jawaban valid (sudah diisi claim_value dan evidence_url)
     */
    public function countValidAnswers($assessment)
    {
        return PengumpulanJawaban::where('submission_id', $assessment->id)
            ->whereNotNull('jawaban_id')
            // ->whereNotNull('evidence_url') // Aktifkan jika URL wajib
            ->count();
    }

    /**
     * PREVIEW: Mengambil seluruh jawaban dari satu Assessment
     */
    public function getAllAnswersByAssessment($assessmentId)
    {
        return PengumpulanJawaban::where('submission_id', $assessmentId)->get();
    }

    /**
     * Mencari record identitas berdasarkan pengumpulan_id.
     */
    public function findIdentitasByPengumpulanId(int $pengumpulanId)
    {
        return Identitas::where('pengumpulan_id', $pengumpulanId)->first();
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
    public function upsertIdentitas(int $pengumpulanId, array $data)
    {
        return Identitas::updateOrCreate(
            ['pengumpulan_id' => $pengumpulanId],
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
        return $user && $user->status === 'ACTIVE';
    }
}
