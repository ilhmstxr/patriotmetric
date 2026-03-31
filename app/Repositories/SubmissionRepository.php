<?php

namespace App\Repositories;

use App\Models\pengumpulan;

class SubmissionRepository extends BaseRepository
{
    /**
     * SubmissionRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(pengumpulan $model)
    {
        parent::__construct($model);
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk Submission di sini
}
