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

    public function existsByName(string $name): bool
    {
        return $this->model->whereRaw('LOWER(nama_institusi) = ?', [strtolower($name)])->exists();
    }

    public function existsByDomain(string $domain): bool
    {
        return $this->model->where('domain_email', $domain)->exists();
    }
}
