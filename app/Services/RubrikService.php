<?php

namespace App\Services;

use App\Repositories\RubrikRepository;
use App\DTOs\RubrikDTO;

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

    public function calculateTotalScore(RubrikDTO $dto): array
    {
        // Bobot berdasarkan header klasifikasi
        $weights = [
            'kebijakan' => 5,
            'kelembagaan' => 20,
            'patriotisme' => 15,
        ];

        $scores = [
            'kebijakan' => $this->calculateKebijakanScore($dto->kebijakan),
            'kelembagaan' => $this->calculateKelembagaanScore($dto->kelembagaan),
            'patriotisme' => $this->calculatePatriotismeScore($dto->patriotisme),
        ];

        // Rumus Akhir: (Total Skor Per Kategori / Skor Maksimal Kategori) * Bobot Kategori
        // Skor maksimal per indikator adalah 5. Jumlah indikator: Kebijakan=5, Kelembagaan=20, Patriotisme=15.
        $finalScore = ($scores['kebijakan'] / (5 * 5) * $weights['kebijakan']) +
            ($scores['kelembagaan'] / (20 * 5) * $weights['kelembagaan']) +
            ($scores['patriotisme'] / (15 * 5) * $weights['patriotisme']);

        return [
            'breakdown' => $scores,
            'final_score' => round($finalScore, 2)
        ];
    }

    private function calculateKebijakanScore(array $items): float
    {
        $total = 0;
        foreach ($items as $key => $value) {
            $val = is_array($value) ? ($value['skor'] ?? 0) : $value;
            $total += $val;
        }
        return $total;
    }

    private function calculateKelembagaanScore(array $items): float
    {
        $total = 0;
        foreach ($items as $key => $value) {
            // Indikator 13 & 14: Skor = (Jumlah) x (Skala)
            if (in_array($key, [13, 14])) {
                $jumlah = is_array($value) ? ($value['jumlah'] ?? 0) : 0;
                $skala = is_array($value) ? ($value['skala'] ?? 0) : 0;
                $score = $jumlah * $skala;
                $total += min($score, 5); // maksimal 5
            }
            // Indikator 20: Persentase UKM Keagamaan
            elseif ($key == 20) {
                $persentase = is_array($value) ? ($value['persentase'] ?? 0) : $value;
                $total += $this->mapPercentageToScoreKelembagaan((float)$persentase);
            } else {
                $val = is_array($value) ? ($value['skor'] ?? 0) : $value;
                $total += $val;
            }
        }
        return $total;
    }

    private function calculatePatriotismeScore(array $items): float
    {
        $total = 0;
        foreach ($items as $key => $value) {
            // Indikator 7: Persentase mahasiswa ikut UKM
            if ($key == 7) {
                $persentase = is_array($value) ? ($value['persentase'] ?? 0) : $value;
                $total += $this->mapPercentageToScorePatriotisme((float)$persentase);
            }
            // Indikator 2: Perbandingan dengan jumlah Prodi
            elseif ($key == 2) {
                $jumlah = is_array($value) ? ($value['jumlah'] ?? 0) : (is_numeric($value) ? $value : 0);
                $jumlahFakultas = is_array($value) ? ($value['jumlah_fakultas'] ?? 0) : 0;
                $jumlahProdi = is_array($value) ? ($value['jumlah_prodi'] ?? 0) : 0;
                $total += $this->compareWithProdi((int)$jumlah, (int)$jumlahFakultas, (int)$jumlahProdi);
            } else {
                $val = is_array($value) ? ($value['skor'] ?? 0) : $value;
                $total += $val;
            }
        }
        return $total;
    }

    private function compareWithProdi(int $jumlah, int $jumlahFakultas, int $jumlahProdi): int
    {
        if ($jumlah <= 0) return 0;
        if ($jumlah > $jumlahProdi && $jumlahProdi > 0) return 5;
        if ($jumlah == $jumlahProdi && $jumlahProdi > 0) return 4;
        if ($jumlah > $jumlahFakultas && $jumlah < $jumlahProdi) return 3;
        if ($jumlah == $jumlahFakultas && $jumlahFakultas > 0) return 2;
        if ($jumlah < $jumlahFakultas) return 1;
        return 0;
    }

    /**
     * Logic untuk Indikator 20: Persentase UKM Keagamaan
     * Range: 0, 1-25, 26-50, 51-75, 76-99, 100
     */
    private function mapPercentageToScoreKelembagaan(float $percentage): int
    {
        if ($percentage <= 0) return 0;
        if ($percentage <= 25) return 1;
        if ($percentage <= 50) return 2;
        if ($percentage <= 75) return 3;
        if ($percentage < 100) return 4;
        return 5;
    }

    /**
     * Logic untuk Indikator 7: Persentase Mahasiswa ikut UKM
     * Range: 1-20, 21-40, 41-60, 61-80, 81-100
     */
    private function mapPercentageToScorePatriotisme(float $percentage): int
    {
        if ($percentage <= 0) return 0;
        if ($percentage <= 20) return 1;
        if ($percentage <= 40) return 2;
        if ($percentage <= 60) return 3;
        if ($percentage <= 80) return 4;
        return 5;
    }
}
