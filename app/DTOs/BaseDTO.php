<?php

namespace App\DTOs;

abstract class BaseDTO
{
    /**
     * Mengonversi DTO ke array yang siap masuk ke Eloquent.
     * Di sini Anda bisa menangani mapping camelCase ke snake_case.
     */
    public function toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $data = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $value = $this->{$name};

            // Opsional: Ubah camelCase ke snake_case otomatis
            $snakeName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
            
            $data[$snakeName] = $value;
        }

        return $data;
    }
}