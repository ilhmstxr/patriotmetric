<?php

namespace App\Services;

use App\Repositories\SubmitterRepository;

class SubmitterService extends BaseService
{
    public function __construct(SubmitterRepository $repository)
    {
        parent::__construct($repository);
    }
}
