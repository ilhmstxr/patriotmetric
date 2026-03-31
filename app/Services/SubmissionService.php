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
    public function calculateTotalScore(array $data): array
    {
        // Bobot berdasarkan header klasifikasi di gambar
        $weights = [
            'kebijakan' => 5,
            'kelembagaan' => 20,
            'patriotisme' => 15,
        ];

        $scores = [
            'kebijakan' => $this->calculateKebijakanScore($data['kebijakan'] ?? []),
            'kelembagaan' => $this->calculateKelembagaanScore($data['kelembagaan'] ?? []),
            'patriotisme' => $this->calculatePatriotismeScore($data['patriotisme'] ?? []),
        ];

        // Rumus Akhir: (Total Skor Per Kategori / Skor Maksimal Kategori) * Bobot Kategori
        // Diasumsikan skor maksimal per indikator adalah 5
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
        // Indikator 1-5 (Linear 0-5)
        return array_sum($items);
    }


    private function calculateKelembagaanScore(array $items): float
    {
        $total = 0;
        foreach ($items as $key => $value) {
            // Indikator 13 & 14: Skor = (Jumlah) x (Skala)
            if (in_array($key, [13, 14])) {
                $score = $value['jumlah'] * $value['skala'];
                $total += min($score, 5); // Biasanya dicap maksimal 5 sesuai kolom
            }
            // Indikator 20: Persentase UKM Keagamaan
            elseif ($key == 20) {
                $total += $this->mapPercentageToScore($value['persentase']);
            } else {
                $total += $value;
            }
        }
        return $total;
    }
    private function calculatePatriotismeScore(array $items): float
    {
        $total = 0;
        foreach ($items as $key => $value) {
            // Indikator 7: Persentase mahasiswa ikut UKM (Range 20%, 40%, dst)
            if ($key == 7) {
                $total += $this->mapRangeScore($value['persentase'], [20, 40, 60, 80]);
            }
            // Indikator 2: Perbandingan dengan jumlah Prodi
            elseif ($key == 2) {
                $total += $this->compareWithProdi($value['jumlah'], $value['total_prodi']);
            } else {
                $total += $value;
            }
        }
        return $total;
    }

    // helper function
    private function mapRangeScore(float $value, array $thresholds): int
    {
        if ($value <= 0) return 0;
        foreach ($thresholds as $index => $threshold) {
            if ($value <= $threshold) return $index + 1;
        }
        return 5;
    }

    private function compareWithProdi(int $jumlah, int $totalProdi): int
    {
        if ($jumlah > $totalProdi) return 5;
        if ($jumlah == $totalProdi) return 4;
        if ($jumlah > ($totalProdi / 2)) return 3;
        return 1;
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
        if ($percentage < 100) return 4; // 76 - 99%
        return 5; // Tepat 100%
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
        return 5; // > 80% hingga 100%
    }

    private function mapToRange(float $value, array $thresholds): int
    {
        if ($value <= 0) return 0;

        foreach ($thresholds as $index => $limit) {
            if ($value <= $limit) {
                return $index + 1;
            }
        }

        return 5; // Default max score
    }
}
