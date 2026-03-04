<?php

namespace App\Repositories;

use App\Models\PengaturanCms;

class PengaturanCmsRepository extends BaseRepository
{
    /**
     * PengaturanCmsRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(PengaturanCms $model)
    {
        parent::__construct($model);
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk PengaturanCms di sini
}
