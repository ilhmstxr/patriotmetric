<?php

namespace App\Repositories;

use App\Models\Institusi;
use App\Models\Pengumpulan;
use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function createUser(array $data)
    {
        return $this->model->create($data);
    }

    public function createInstitusi(array $data)
    {
        return Institusi::create($data);
    }

    public function createPengumpulan(array $data)
    {
        return Pengumpulan::create($data);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    // Tambahkan query spesifik (misal: scope atau complex join) untuk User di sini
}
