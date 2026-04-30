<?php

namespace App\Repositories;

use App\Models\Pengumpulan;

class PengumpulanRepository extends BaseRepository
{
    /**
     * PengumpulanRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(Pengumpulan $model)
    {
        parent::__construct($model);
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk pengumpulan di sini
}
