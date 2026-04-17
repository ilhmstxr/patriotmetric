<?php

namespace App\Services;

use App\Repositories\ReviewerRepository;

class ReviewerService extends BaseService
{
    public function __construct(ReviewerRepository $repository)
    {
        parent::__construct($repository);
    }
}
