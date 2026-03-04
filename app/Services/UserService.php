<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends BaseService
{
    /**
     * UserService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(UserRepository $repository)
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
}