<?php

/**
 * Konfigurasi Rubrik Patriot Metric
 * 
 * File ini berisi mapping ID pertanyaan ke rumus perhitungan otomatis.
 * User dapat mengisi rumus spesifik per ID pertanyaan di bawah.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Auto-Calculation Formulas
    |--------------------------------------------------------------------------
    |
    | Mapping ID pertanyaan ke rumus perhitungan otomatis.
    | Setiap entry berisi:
    | - 'type': tipe perhitungan (percentage, benchmark, kuantitas_skala, custom)
    | - 'params': parameter tambahan jika diperlukan
    |
    | Contoh penggunaan:
    | 5 => [
    |     'type' => 'percentage',
    |     'max_value' => 100,
    |     'thresholds' => [25, 50, 75, 100], // Threshold untuk skor 1-5
    | ],
    |
    | 13 => [
    |     'type' => 'kuantitas_skala',
    |     'max_score' => 5,
    | ],
    |
    | 2 => [
    |     'type' => 'benchmark',
    |     'compare_with' => ['fakultas', 'prodi'],
    | ],
    */

    'auto_calculation' => [
        // Contoh: ID 5 - Persentase kelembagaan
        // 5 => [
        //     'type' => 'percentage',
        //     'thresholds' => [25, 50, 75, 100],
        // ],

        // Contoh: ID 7 - Persentase patriotisme
        // 7 => [
        //     'type' => 'percentage',
        //     'thresholds' => [20, 40, 60, 80, 100],
        // ],

        // Contoh: ID 13 - Kuantitas x Skala
        // 13 => [
        //     'type' => 'kuantitas_skala',
        //     'max_score' => 5,
        // ],

        // Contoh: ID 2 - Benchmark (perbandingan dengan fakultas/prodi)
        // 2 => [
        //     'type' => 'benchmark',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Skor Maksimal per Pertanyaan
    |--------------------------------------------------------------------------
    |
    | Nilai default untuk skor maksimal jika tidak diset di database.
    */
    'default_skor_maksimal' => 5,

    /*
    |--------------------------------------------------------------------------
    | Threshold Persentase untuk Skor
    |--------------------------------------------------------------------------
    |
    | Mapping persentase ke skor (0-5).
    | Digunakan untuk tipe perhitungan percentage.
    */
    'percentage_thresholds' => [
        'kelembagaan' => [25, 50, 75, 100],
        'patriotisme' => [20, 40, 60, 80, 100],
    ],
];
