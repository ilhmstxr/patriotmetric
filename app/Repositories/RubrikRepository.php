<?php

namespace App\Repositories;

use App\Models\kategori;

class RubrikRepository extends BaseRepository
{
    public function __construct(kategori $model)
    {
        parent::__construct($model);
    }

    public function getRubrikWithQuestions()
    {
        return kategori::with('pertanyaans.opsi_jawaban')->get();
    }
    
    public function getCategoryCountQuestion()
    {
        return kategori::withCount('pertanyaans')->get();
    }

    public function getCategoryWeight()
    {
        return kategori::sum('bobot');
    }
}
