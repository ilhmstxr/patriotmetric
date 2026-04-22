<?php


namespace App\Repositories;

use App\Models\opsiJawaban;
use App\Models\pertanyaan;

class PertanyaanRepository extends BaseRepository
{
    public function __construct(pertanyaan $model)
    {
        parent::__construct($model);
    }
    /**
     * SINGLE FORM: Mengambil seluruh pertanyaan + relasi jawaban jika sudah ada
     */
    public function getAllQuestionsWithExistingAnswers($assessment)
    {
        return $this->model->with([
            'kategori', // Pastikan relasi ini ada di Model Pertanyaan
            'opsiJawabans', // <--- WAJIB DITAMBAHKAN AGAR OPSI PILIHAN GANDA MUNCUL DI JSON
            'jawaban' => function ($query) use ($assessment) {
                // Filter jawaban spesifik untuk submission ini
                $query->where('submission_id', $assessment->id);
            }
        ])->get();
    }


    /**
     * Mengambil data opsi jawaban tunggal berdasarkan ID
     */
    public function findOpsiById($id)
    {
        return OpsiJawaban::find($id);
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

    public function getPertanyaanWithOpsiJawaban()
    {
        return $this->model->with(
            'kategori',
            'opsiJawabans'
        )->get();
    }

    public function findPertanyaanById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * COUNT: Total soal wajib yang harus dijawab
     */
    public function countTotalMandatoryQuestions()
    {
        // Sesuaikan jika ada pertanyaan yang tidak wajib (is_mandatory = false)
        return $this->model->count();
    }
}
