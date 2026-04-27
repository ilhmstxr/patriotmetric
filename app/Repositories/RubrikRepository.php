<?php

namespace App\Repositories;

use App\Models\Kategori;

class RubrikRepository extends BaseRepository
{
    public function __construct(Kategori $model)
    {
        parent::__construct($model);
    }

    public function getRubrikWithQuestions()
    {
        return Kategori::with('pertanyaans.opsi_jawaban')->get();
    }
    
    public function getCategoryCountQuestion()
    {
        return Kategori::withCount('pertanyaans')->get();
    }

    public function getCategoryWeight()
    {
        return Kategori::sum('bobot');
    }
}
