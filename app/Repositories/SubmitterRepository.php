<?php

namespace App\Repositories;

use App\Models\Pengumpulan; // Gunakan PascalCase
use App\Models\PengumpulanJawaban;
use App\Models\Kategori;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\DB;

class SubmitterRepository extends BaseRepository
{
    public function __construct(Pengumpulan $model)
    {
        parent::__construct($model);
    }

    /**
     * MENCARI SESI AKTIF BERDASARKAN USER (Keamanan Utama)
     * Mengembalikan Assessment/Pengumpulan yang valid untuk tahun berjalan
     * dan statusnya BUKAN draft awal/ditolak.
     */
    public function findActiveByUserId($userId)
    {
        // Asumsi: Anda punya kolom user_id atau relasi melalui institusi
        // Sesuaikan query ini dengan struktur spesifik tabel Pengumpulan Anda
        return $this->model
            ->where('user_id', $userId)
            // ->where('tahun_periode', date('Y')) // Opsional: Filter tahun
            ->whereIn('status', ['baseline_verified', 'active', 'SUBMITTED', 'REVIEWING', 'REVIEWED', 'PUBLISHED']) 
            ->first();
    }

    /**
     * SINGLE FORM: Mengambil seluruh pertanyaan + relasi jawaban jika sudah ada
     */
    public function getAllQuestionsWithExistingAnswers($assessmentId)
    {
        return Pertanyaan::with([
            'opsiJawabans', // Pastikan relasi ini ada di Model Pertanyaan
            'jawaban' => function($query) use ($assessmentId) {
                // Filter jawaban spesifik untuk submission ini
                $query->where('submission_id', $assessmentId); 
            }
        ])->get();
    }

    /**
     * AUTO-SAVE (Upsert Batch)
     */
    public function upsertAnswers($assessmentId, array $answers)
    {
        DB::transaction(function () use ($assessmentId, $answers) {
            foreach ($answers as $answer) {
                PengumpulanJawaban::updateOrCreate(
                    [
                        'submission_id' => $assessmentId,
                        'question_id' => $answer['question_id']
                    ],
                    [
                        'claim_value' => $answer['claim_value'] ?? null,
                        'evidence_url' => $answer['evidence_url'] ?? null,
                        'updated_at' => now(), // Memastikan timestamp berubah saat auto-save
                    ]
                );
            }
        });
    }

    /**
     * COUNT: Total soal wajib yang harus dijawab
     */
    public function countTotalMandatoryQuestions()
    {
        // Sesuaikan jika ada pertanyaan yang tidak wajib (is_mandatory = false)
        return Pertanyaan::count(); 
    }

    /**
     * COUNT: Total jawaban valid (sudah diisi claim_value dan evidence_url)
     */
    public function countValidAnswers($assessmentId)
    {
        return PengumpulanJawaban::where('submission_id', $assessmentId)
            ->whereNotNull('claim_value')
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
    public function updateStatus($id, $status)
    {
        return $this->model->where('id', $id)->update([
            'status' => $status,
            'updated_at' => now() // Gunakan updated_at jika submitted_at tidak ada
        ]);
    }
}
