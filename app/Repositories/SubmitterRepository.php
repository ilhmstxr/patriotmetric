<?php

namespace App\Repositories;

use App\Models\Submitter;

class SubmitterRepository extends BaseRepository
{
    public function __construct(Submitter $model)
    {
        parent::__construct($model);
    }
}
