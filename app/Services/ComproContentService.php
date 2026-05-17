<?php

namespace App\Services;

use App\Models\ComproContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComproContentService
{
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get all content for a specific page, grouped by section.
     * Results are cached for 1 hour. Empty results are NOT cached.
     */
    public function getPageContent(string $page): Collection
    {
        try {
            $cacheKey = "compro_content.{$page}";

            // Check cache first
            $cached = Cache::get($cacheKey);
            if ($cached !== null && $cached->isNotEmpty()) {
                return $cached;
            }

            // Query database
            $result = ComproContent::forPage($page)
                ->orderBy('section')
                ->orderBy('order')
                ->get()
                ->groupBy('section');

            // Only cache non-empty results
            if ($result->isNotEmpty()) {
                Cache::put($cacheKey, $result, self::CACHE_TTL);
            }

            return $result;
        } catch (\Throwable $e) {
            Log::error('ComproContentService::getPageContent failed', [
                'page' => $page,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get a single content value by page, section, and key.
     */
    public function getValue(string $page, string $section, string $key): string|array|null
    {
        try {
            $content = ComproContent::forPage($page)
                ->forSection($section)
                ->where('key', $key)
                ->first();

            return $content?->value;
        } catch (\Throwable $e) {
            Log::error('ComproContentService::getValue failed', [
                'page' => $page,
                'section' => $section,
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update static content values for a page.
     * Wraps all updates in a DB transaction for atomicity.
     */
    public function updateStaticContent(string $page, array $data): void
    {
        try {
            DB::transaction(function () use ($page, $data) {
                foreach ($data as $sectionKey => $value) {
                    [$section, $key] = explode('.', $sectionKey, 2);

                    ComproContent::updateOrCreate(
                        ['page' => $page, 'section' => $section, 'key' => $key],
                        ['value' => $value]
                    );
                }
            });

            $this->clearCache($page);
        } catch (\Throwable $e) {
            Log::error('ComproContentService::updateStaticContent failed', [
                'page' => $page,
                'data_keys' => array_keys($data),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update repeater content for a specific section.
     */
    public function updateRepeaterContent(string $page, string $section, string $key, array $items): void
    {
        try {
            ComproContent::updateOrCreate(
                ['page' => $page, 'section' => $section, 'key' => $key],
                ['value' => $items, 'type' => 'repeater']
            );

            $this->clearCache($page);
        } catch (\Throwable $e) {
            Log::error('ComproContentService::updateRepeaterContent failed', [
                'page' => $page,
                'section' => $section,
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Clear cache for a page.
     */
    public function clearCache(string $page): void
    {
        Cache::forget("compro_content.{$page}");
    }

    /**
     * Clear cache for all compro pages.
     */
    public function clearAllCache(): void
    {
        $pages = array_keys($this->getPageStructure());
        foreach ($pages as $page) {
            Cache::forget("compro_content.{$page}");
        }
    }

    /**
     * Get all pages with their sections for admin navigation.
     */
    public function getPageStructure(): array
    {
        return [
            'welcome' => ['hero', 'about', 'institusi', 'timeline', 'instagram'],
            'profile' => ['hero', 'latar-belakang', 'tujuan-utama'],
            'visi-misi' => ['hero', 'visi', 'misi'],
            'tim' => ['hero', 'team-grid'],
            'penghargaan' => ['hero', 'daftar-penerima'],
            'panduan' => ['hero', 'steps', 'faq'],
            'pengumuman' => ['hero', 'artikel'],
        ];
    }
}
