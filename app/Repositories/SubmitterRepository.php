<?php

namespace App\Repositories;

use App\Models\Identitas;
use App\Models\Pengumpulan; // Gunakan PascalCase
use App\Models\PengumpulanJawaban;
use App\Models\Kategori;
use App\Models\opsiJawaban;
use App\Models\Pertanyaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SubmitterRepository extends BaseRepository
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
    // Contoh penggunaan di SubmitterRepository.php
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

    public function getAllQuestionsWithExistingAnswers($assessment)
    {
        return $this->model->with([
            'kategori',
            'opsiJawabans',
            'jawaban' => function ($query) use ($assessment) {
                $query->where('submission_id', $assessment->id);
            }
        ])->get();
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
     * STATUS UPDATE: Mengunci form
     */
    public function updateStatusAssessment($id, $status)
    {
        return $this->model->where('id', $id)->update([
            'status' => $status,
            'updated_at' => now() // Gunakan updated_at jika submitted_at tidak ada
        ]);
    }

    /**
     * Mencari data identitas berdasarkan ID Pengumpulan
     */
    public function findIdentitasByPengumpulanId($pengumpulanId)
    {
        // Sesuaikan dengan model Identitas Anda
        return Identitas::where('pengumpulan_id', $pengumpulanId)->first();
    }


    /**
     * Membuat record baru di tabel identitas
     */
    public function createIdentitas(array $data)
    {
        return Identitas::create($data);
    }

    public function isUserActive(int $userId): bool
    {
        $user = User::find($userId);
        return $user && $user->status === 'ACTIVE';
    }
}
