<?php

namespace App\Services;

use App\Models\ComproContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ComproContentService
{
    private const CACHE_TTL = 3600;
    private const DISK = 'cms';
    private const IMAGE_KEYS = ['foto', 'logo', 'gambar', 'background_image'];
    private const REPEATER_KEYS = [
        'institusi.daftar_baris_1', 'institusi.daftar_baris_2',
        'timeline.daftar', 'instagram.posts', 'tujuan-utama.daftar',
        'misi.daftar', 'team-grid.daftar', 'daftar-penerima.daftar',
        'steps.daftar', 'faq.daftar', 'artikel.daftar', 'berita.daftar',
    ];
    private const STATIC_IMAGE_KEYS = ['hero.background_image'];

    public function getPageContent(string $page): Collection
    {
        try {
            $cacheKey = "compro_content.{$page}";

            try {
                $cached = Cache::get($cacheKey);
                if ($cached !== null && $cached->isNotEmpty()) {
                    return $cached;
                }
            } catch (\Throwable $e) {
            }

            $result = ComproContent::forPage($page)
                ->orderBy('section')
                ->orderBy('order')
                ->get()
                ->groupBy('section');

            if ($result->isNotEmpty()) {
                try {
                    Cache::put($cacheKey, $result, self::CACHE_TTL);
                } catch (\Throwable $e) {
                }
            }

            return $result;
        } catch (\Throwable $e) {
            Log::error('ComproContentService::getPageContent failed', [
                'page' => $page,
                'error' => $e->getMessage(),
            ]);
            return collect();
        }
    }

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
     * Load page content and convert to Filament form-compatible format.
     */
    public function loadFormData(string $page): array
    {
        $pageContent = $this->getPageContent($page);
        $formData = [];

        foreach ($pageContent as $section => $items) {
            foreach ($items as $item) {
                $key = "{$section}.{$item->key}";
                $value = $item->value;

                if ($item->type === 'image' && is_string($value) && $value !== '') {
                    $value = [$value];
                }

                if ($item->type === 'repeater' && is_array($value)) {
                    $value = $this->convertRepeaterImageStringsToArrays($value);
                }

                data_set($formData, $key, $value);
            }
        }

        return $formData;
    }

    /**
     * Save form data: normalize images, cleanup old files, persist to DB.
     */
    public function saveFormData(string $page, array $rawData): void
    {
        $data = $this->flattenFormData($rawData);
        $disk = Storage::disk(self::DISK);

        $disk->makeDirectory('images');
        $staticData = [];
        $repeaterData = [];

        foreach ($data as $sectionKey => $value) {
            if (is_array($value) && $this->isRepeaterField($sectionKey)) {
                [$section, $key] = explode('.', $sectionKey, 2);
                $oldItems = $this->getValue($page, $section, $key);
                $newItems = $this->normalizeRepeaterImages($value);
                $this->cleanupOldRepeaterImages($oldItems, $newItems, $disk);
                $repeaterData[$sectionKey] = $newItems;
            } elseif ($this->isStaticImageField($sectionKey)) {
                [$section, $key] = explode('.', $sectionKey, 2);
                $oldPath = $this->getValue($page, $section, $key);
                $newPath = $this->normalizeImageValue($value);
                $this->cleanupOldImage($oldPath, $newPath, $disk);
                $staticData[$sectionKey] = $newPath;
            } else {
                $staticData[$sectionKey] = $value;
            }
        }

        DB::transaction(function () use ($page, $staticData, $repeaterData) {
            if (!empty($staticData)) {
                $this->updateStaticContent($page, $staticData);
            }

            foreach ($repeaterData as $sectionKey => $items) {
                [$section, $key] = explode('.', $sectionKey, 2);
                $this->updateRepeaterContent($page, $section, $key, $items);
            }
        });


        $this->clearCache($page);
    }

    public function updateStaticContent(string $page, array $data): void
    {
        foreach ($data as $sectionKey => $value) {
            [$section, $key] = explode('.', $sectionKey, 2);

            $attributes = ['value' => $value];
            if ($this->isStaticImageField($sectionKey)) {
                $attributes['type'] = 'image';
            }

            ComproContent::updateOrCreate(
                ['page' => $page, 'section' => $section, 'key' => $key],
                $attributes
            );
        }
    }

    public function updateRepeaterContent(string $page, string $section, string $key, array $items): void
    {
        ComproContent::updateOrCreate(
            ['page' => $page, 'section' => $section, 'key' => $key],
            ['value' => $items, 'type' => 'repeater']
        );
    }

    public function clearCache(string $page): void
    {
        Cache::forget("compro_content.{$page}");
    }

    public function clearAllCache(): void
    {
        $pages = array_keys($this->getPageStructure());
        foreach ($pages as $page) {
            Cache::forget("compro_content.{$page}");
        }
    }

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

    // --- Private helpers ---

    private function flattenFormData(array $rawData): array
    {
        $data = [];
        foreach ($rawData as $section => $fields) {
            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    $data["{$section}.{$key}"] = $value;
                }
            } else {
                $data[$section] = $fields;
            }
        }
        return $data;
    }

    private function convertRepeaterImageStringsToArrays(array $items): array
    {
        return array_map(function ($item) {
            if (!is_array($item)) {
                return $item;
            }
            foreach ($item as $fieldKey => $fieldValue) {
                if (in_array($fieldKey, self::IMAGE_KEYS) && is_string($fieldValue) && $fieldValue !== '') {
                    $item[$fieldKey] = [$fieldValue];
                }
            }
            return $item;
        }, $items);
    }

    private function isRepeaterField(string $sectionKey): bool
    {
        return in_array($sectionKey, self::REPEATER_KEYS);
    }

    private function isStaticImageField(string $sectionKey): bool
    {
        return in_array($sectionKey, self::STATIC_IMAGE_KEYS);
    }

    private function normalizeImageValue(mixed $value): ?string
    {
        if (is_array($value)) {
            return !empty($value) ? (string) collect($value)->first() : null;
        }
        return is_string($value) && $value !== '' ? $value : null;
    }

    private function normalizeRepeaterImages(array $items): array
    {
        return array_values(array_map(function ($item) {
            if (!is_array($item)) {
                return $item;
            }
            foreach ($item as $fieldKey => $fieldValue) {
                if (in_array($fieldKey, self::IMAGE_KEYS)) {
                    $item[$fieldKey] = $this->normalizeImageValue($fieldValue);
                }
            }
            return $item;
        }, $items));
    }

    private function cleanupOldImage(?string $oldPath, ?string $newPath, $disk): void
    {
        if ($oldPath && $oldPath !== $newPath && $disk->exists($oldPath)) {
            $disk->delete($oldPath);
        }
    }

    private function cleanupOldRepeaterImages($oldItems, array $newItems, $disk): void
    {
        if (!is_array($oldItems)) {
            return;
        }

        $newPaths = collect($newItems)
            ->flatMap(fn($item) => is_array($item)
                ? collect($item)->only(self::IMAGE_KEYS)->values()
                : collect())
            ->filter()
            ->all();

        foreach ($oldItems as $oldItem) {
            if (!is_array($oldItem)) {
                continue;
            }
            foreach (self::IMAGE_KEYS as $key) {
                $oldPath = $oldItem[$key] ?? null;
                if ($oldPath && !in_array($oldPath, $newPaths) && $disk->exists($oldPath)) {
                    $disk->delete($oldPath);
                }
            }
        }
    }
}
