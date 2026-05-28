<?php

namespace App\Services;

use App\Repositories\InstitusiRepository;

class InstitusiService extends BaseService
{
    /**
     * InstitusiService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(InstitusiRepository $repository)
    {
        parent::__construct($repository);
    }

    // Tambahkan logika bisnis spesifik untuk institusi di sini
}