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

    public function getByKey(string $key)
    {
        return $this->model->where('key', $key)->first();
    }
}
