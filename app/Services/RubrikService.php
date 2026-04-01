<?php

namespace App\Services;

use App\Repositories\RubrikRepository;
use App\Models\kategori;
use Exception;

/**
 * @property RubrikRepository $repository
 */
class RubrikService extends BaseService
{
    /**
     * RubrikService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(RubrikRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Mengambil seluruh kategori beserta indikator dan opsi jawabannya secara nested untuk kebutuhan render accordion di React.
     */
    public function getRubrikStructure()
    {
        return $this->repository->getRubrikWithQuestions();
    }

    /**
     * Mengambil informasi bobot dan jumlah indikator aktif per kategori langsung dari database.
     */
    public function getCategoryMetadata()
    {
        $categories = $this->repository->getCategoryCountQuestion();

        $metadata = [];
        foreach ($categories as $category) {
            $metadata[$category->nama_kategori] = [
                'bobot' => $category->bobot,
                'jumlah_indikator' => $category->pertanyaans_count,
            ];
        }

        return $metadata;
    }

    /**
     * Memastikan total bobot seluruh kategori mencapai 100% sebelum sistem dibuka untuk pengumpulan.
     */
    public function validateRubrikConsistency()
    {
        $totalBobot = $this->repository->getCategoryWeight();

        if ($totalBobot != 100) {
            throw new Exception("Total bobot kategori rubrik tidak konsisten. Total saat ini: {$totalBobot}%. Dibutuhkan tepat 100%.");
        }

        return true;
    }
}
