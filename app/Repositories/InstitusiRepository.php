<?php

namespace App\Repositories;

use App\Models\Institusi;

class InstitusiRepository extends BaseRepository
{
    /**
     * InstitusiRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(Institusi $model)
    {
        parent::__construct($model);
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk institusi di sini
}
