<?php

namespace App\Repositories;

use App\Models\pengumpulan;

class ReviewRepository extends BaseRepository
{
    public function __construct(pengumpulan $model)
    {
        parent::__construct($model);
    }
}
