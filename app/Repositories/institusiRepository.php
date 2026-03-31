<?php

namespace App\Repositories;

use App\Models\institusi;

class institusiRepository extends BaseRepository
{
    /**
     * institusiRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(institusi $model)
    {
        parent::__construct($model);
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk institusi di sini
}
