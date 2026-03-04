<?php

namespace App\Repositories;

use App\Models\pengumpulan;

class pengumpulanRepository extends BaseRepository
{
    /**
     * pengumpulanRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(pengumpulan $model)
    {
        parent::__construct($model);
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk pengumpulan di sini
}
