<?php

namespace App\Services;

use App\Repositories\institusiRepository;

class institusiService extends BaseService
{
    /**
     * institusiService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(institusiRepository $repository)
    {
        parent::__construct($repository);
    }

    // Tambahkan logika bisnis spesifik untuk institusi di sini
}