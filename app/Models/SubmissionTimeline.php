<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SubmissionTimeline extends Model
{
    protected $table = 'submission_timelines';

    protected $fillable = [
        'tahun_periode',
        'opens_at',
        'closes_at',
        'results_published_at',
        'is_locked',
        'note',
    ];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'results_published_at' => 'datetime',
        'is_locked' => 'boolean',
    ];

    /**
     * Cek apakah peserta boleh submit/edit pada tahun_periode tertentu.
     *
     * Aturan:
     * - Tidak ada timeline → diizinkan (default terbuka).
     * - is_locked = true → ditolak (admin force-lock).
     * - now < opens_at → ditolak (belum dibuka).
     * - now > closes_at → ditolak (sudah ditutup).
     */
    public static function canSubmit(int|string $tahunPeriode): array
    {
        $timeline = static::where('tahun_periode', $tahunPeriode)->first();

        if (! $timeline) {
            return [
                'allowed' => true,
                'reason' => null,
                'timeline' => null,
            ];
        }

        $now = Carbon::now();

        if ($timeline->is_locked) {
            return [
                'allowed' => false,
                'reason' => $timeline->note
                    ? 'Submission dikunci oleh admin: ' . $timeline->note
                    : 'Submission saat ini dikunci oleh admin.',
                'timeline' => $timeline,
            ];
        }

        if ($timeline->opens_at && $now->lt($timeline->opens_at)) {
            return [
                'allowed' => false,
                'reason' => 'Submission belum dibuka. Akan dibuka pada '
                    . $timeline->opens_at->translatedFormat('d M Y H:i') . '.',
                'timeline' => $timeline,
            ];
        }

        if ($timeline->closes_at && $now->gt($timeline->closes_at)) {
            return [
                'allowed' => false,
                'reason' => 'Submission sudah ditutup pada '
                    . $timeline->closes_at->translatedFormat('d M Y H:i') . '.',
                'timeline' => $timeline,
            ];
        }

        return [
            'allowed' => true,
            'reason' => null,
            'timeline' => $timeline,
        ];
    }

    /**
     * Cek apakah peserta boleh melihat hasil pada tahun_periode tertentu.
     */
    public static function canViewResults(int|string $tahunPeriode): array
    {
        $timeline = static::where('tahun_periode', $tahunPeriode)->first();

        if (! $timeline) {
            return [
                'allowed' => true,
                'reason' => null,
            ];
        }

        $now = Carbon::now();

        if ($timeline->results_published_at && $now->lt($timeline->results_published_at)) {
            return [
                'allowed' => false,
                'reason' => 'Hasil penilaian belum dipublikasikan. Akan tersedia pada '
                    . $timeline->results_published_at->translatedFormat('d M Y H:i') . '.',
            ];
        }

        return [
            'allowed' => true,
            'reason' => null,
        ];
    }
}
