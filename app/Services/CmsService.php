<?php

namespace App\Services;

use App\Repositories\CmsRepository;

class CmsService extends BaseService
{
    /**
     * CmsService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(CmsRepository $repository)
    {
        parent::__construct($repository);
    }

    // Tambahkan logika bisnis spesifik untuk Cms di sini
}