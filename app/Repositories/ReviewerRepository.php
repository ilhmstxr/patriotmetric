<?php

namespace App\Repositories;

use App\Models\Reviewer;

class ReviewerRepository extends BaseRepository
{
    public function __construct(Reviewer $model)
    {
        parent::__construct($model);
    }

    public function findByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }
}
