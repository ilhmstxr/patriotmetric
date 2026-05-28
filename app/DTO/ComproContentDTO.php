<?php

namespace App\DTO;

use App\Models\ComproContent;

readonly class ComproContentDTO
{
    public function __construct(
        public string $page,
        public string $section,
        public string $key,
        public string $type,
        public string|array|null $value,
        public int $order = 0,
    ) {}

    public static function fromModel(ComproContent $model): self
    {
        return new self(
            page: $model->page,
            section: $model->section,
            key: $model->key,
            type: $model->type,
            value: $model->value,
            order: $model->order,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            page: $data['page'],
            section: $data['section'],
            key: $data['key'],
            type: $data['type'],
            value: $data['value'] ?? null,
            order: $data['order'] ?? 0,
        );
    }
}
