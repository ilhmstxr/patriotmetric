<?php

namespace App\Repositories;

use App\Models\Cms;

class CmsRepository extends BaseRepository
{
    /**
     * CmsRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(Cms $model)
    {
        parent::__construct($model);
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk Cms di sini
}
