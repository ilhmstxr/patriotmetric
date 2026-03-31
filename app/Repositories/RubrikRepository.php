<?php

namespace App\Repositories;

use App\Models\kategori;

class RubrikRepository extends BaseRepository
{
    public function __construct(kategori $model)
    {
        parent::__construct($model);
    }
}
