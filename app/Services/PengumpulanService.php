<?php

namespace App\Services;

use App\Repositories\PengumpulanRepository;

class PengumpulanService extends BaseService
{
    /**
     * PengumpulanService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(PengumpulanRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(object $dto): \Illuminate\Database\Eloquent\Model
    {
        return $this->repository->create($dto->data);
    }

    public function update(int|string $id, object $dto): bool
    {
        return $this->repository->update($id, $dto->data);
    }

    public function assignReviewer(int|string $id, int|string $reviewerId): bool
    {
        return $this->repository->update($id, [
            'reviewer_id' => $reviewerId,
            'status' => 'assigned' // Or any relevant status
        ]);
    }
}