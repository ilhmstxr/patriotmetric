<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class BaseService
{
    protected BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDetail(int|string $id, bool $useCache = true): ?Model
    {
        if (!$useCache) return $this->repository->find($id);

        // Contoh otomatisasi cache menggunakan nama class
        $cacheKey = strtolower(class_basename($this)) . "_detail_{$id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return $this->repository->find($id);
        });
    }

    public function store(object $dto): Model
    {
        // $dto di sini adalah objek dari class DTO kamu
        return $this->repository->create((array) $dto);
    }
}