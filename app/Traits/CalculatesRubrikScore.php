<?php

namespace App\Traits;

trait CalculatesRubrikScore
{
    /**
     * Menentukan skor (0-5) berdasarkan tipe input (statis, kuantitas x skala, atau persentase).
     */
    public function resolveIndicatorScore($indicatorId, $value, $type = 'statis'): float
    {
        if ($type === 'statis') {
            return is_array($value) ? ($value['skor'] ?? 0) : (float) $value;
        }

        if ($type === 'kuantitas_skala') {
            $jumlah = is_array($value) ? ($value['jumlah'] ?? 0) : 0;
            $skala = is_array($value) ? ($value['skala'] ?? 0) : 0;
            $score = $jumlah * $skala;
            return min($score, 5); // Maksimal 5
        }

        if ($type === 'persentase_kelembagaan') {
            $persentase = is_array($value) ? ($value['persentase'] ?? 0) : (float) $value;
            return $this->mapPercentageToScore((float) $persentase, 'kelembagaan');
        }

        if ($type === 'persentase_patriotisme') {
            $persentase = is_array($value) ? ($value['persentase'] ?? 0) : (float) $value;
            return $this->mapPercentageToScore((float) $persentase, 'patriotisme');
        }

        if ($type === 'benchmark') {
            $jumlah = is_array($value) ? ($value['jumlah'] ?? 0) : (is_numeric($value) ? $value : 0);
            $jumlahFakultas = is_array($value) ? ($value['jumlah_fakultas'] ?? 0) : 0;
            $jumlahProdi = is_array($value) ? ($value['jumlah_prodi'] ?? 0) : 0;
            return $this->compareWithInstitutionalBenchmarks((int) $jumlah, (int) $jumlahFakultas, (int) $jumlahProdi);
        }

        return 0;
    }

    /**
     * Mengonversi angka persentase mentah ke dalam skala skor 0-5 berdasarkan ambang batas (threshold) kategori terkait.
     */
    public function mapPercentageToScore(float $percentage, string $type): int
    {
        if ($percentage <= 0) {
            return 0;
        }

        if ($type === 'kelembagaan') {
            if ($percentage <= 25)
                return 1;
            if ($percentage <= 50)
                return 2;
            if ($percentage <= 75)
                return 3;
            if ($percentage < 100)
                return 4;
            return 5;
        }

        if ($type === 'patriotisme') {
            if ($percentage <= 20)
                return 1;
            if ($percentage <= 40)
                return 2;
            if ($percentage <= 60)
                return 3;
            if ($percentage <= 80)
                return 4;
            return 5;
        }

        return 0;
    }

    /**
     * Menghitung total skor mentah dalam satu kategori tertentu (misal: total skor Kebijakan).
     */
    public function calculateCategorySubtotal(array $indicators, string $categoryType): float
    {
        $total = 0;
        foreach ($indicators as $key => $value) {
            $type = 'statis';

            // Penentuan tipe berdasarkan indikator spesifik (disesuaikan dengan kebutuhan)
            if ($categoryType === 'kelembagaan') {
                if (in_array($key, [13, 14])) {
                    $type = 'kuantitas_skala';
                } elseif ($key == 20) {
                    $type = 'persentase_kelembagaan';
                }
            } elseif ($categoryType === 'patriotisme') {
                if ($key == 7) {
                    $type = 'persentase_patriotisme';
                } elseif ($key == 2) {
                    $type = 'benchmark';
                }
            }

            $total += $this->resolveIndicatorScore($key, $value, $type);
        }
        return $total;
    }

    /**
     * Mengalikan subtotal skor kategori dengan bobot dinamis yang diambil dari database.
     */
    public function applyCategoryWeight(float $subtotal, float $weight, int $maxPossibleScore): float
    {
        if ($maxPossibleScore <= 0) {
            return 0;
        }
        // Rumus: (Skor / MaxScore) * Bobot
        return ($subtotal / $maxPossibleScore) * $weight;
    }

    /**
     * Logika perbandingan jumlah mahasiswa terhadap jumlah Prodi dan Fakultas untuk indikator spesifik.
     */
    public function compareWithInstitutionalBenchmarks(int $jumlah, int $jumlahFakultas, int $jumlahProdi): int
    {
        if ($jumlah <= 0)
            return 0;
        if ($jumlah > $jumlahProdi && $jumlahProdi > 0)
            return 5;
        if ($jumlah == $jumlahProdi && $jumlahProdi > 0)
            return 4;
        if ($jumlah > $jumlahFakultas && $jumlah < $jumlahProdi)
            return 3;
        if ($jumlah == $jumlahFakultas && $jumlahFakultas > 0)
            return 2;
        if ($jumlah < $jumlahFakultas)
            return 1;

        return 0;
    }
}
