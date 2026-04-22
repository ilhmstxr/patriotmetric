<?php

namespace App\Repositories;

use App\Models\Identitas;
use App\Models\Pengumpulan; // Gunakan PascalCase
use App\Models\PengumpulanJawaban;
use App\Models\Kategori;
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
            // ->whereIn('status', ['ACTIVE', 'IN_PROGRESS','SUBMITTED'])
            ->first();
    }


    /**
     * SINGLE FORM: Mengambil seluruh pertanyaan + relasi jawaban jika sudah ada
     */
    public function getAllQuestionsWithExistingAnswers($assessment)
    {
        return Pertanyaan::with([
            'kategori', // Pastikan relasi ini ada di Model Pertanyaan
            'opsiJawabans', // <--- WAJIB DITAMBAHKAN AGAR OPSI PILIHAN GANDA MUNCUL DI JSON
            'jawaban' => function ($query) use ($assessment) {
                // Filter jawaban spesifik untuk submission ini
                $query->where('submission_id', $assessment->id);
            }
        ])->get();
    }

    public function getPertanyaanWithOpsiJawaban(){
        return Pertanyaan::with(
            'kategori',
            'opsiJawabans'
        )->get();
    }

    /**
     * AUTO-SAVE (Upsert Batch)
     */
    // Contoh penggunaan di SubmitterRepository.php
    public function upsertAnswers($assessmentId, array $answers)
    {
        foreach ($answers as $answer) {
            PengumpulanJawaban::updateOrCreate(
                [
                    'submission_id' => $assessmentId,
                    'pertanyaan_id' => $answer['pertanyaan_id'] // Kunci unik untuk pencarian
                ],
                [
                    'jawaban_id' => $answer['jawaban_id'], // Opsi yang dipilih
                    'jawaban_teks' => $answer['jawaban_teks'] ?? null,
                    'tautan_bukti_drive' => $answer['tautan_bukti_drive'] ?? null,
                ]
            );
        }
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
