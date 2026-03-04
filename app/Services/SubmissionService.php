<?php

namespace App\Services;

use App\Repositories\SubmissionRepository;

class SubmissionService extends BaseService
{
    /**
     * SubmissionService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(SubmissionRepository $repository)
    {
        parent::__construct($repository);
    }

    // Tambahkan logika bisnis spesifik untuk Submission di sini
}