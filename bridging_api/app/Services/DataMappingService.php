<?php

namespace App\Services;

class DataMappingService
{
    public function mapKeys(mixed $payload, ?array $mapping): mixed
    {
        if (empty($mapping)) {
            return $payload;
        }

        if (!is_array($payload)) {
            return $payload;
        }

        if (array_is_list($payload)) {
            return array_map(function ($item) use ($mapping) {
                return $this->mapKeys($item, $mapping);
            }, $payload);
        }

        $mappedPayload = [];

        foreach ($payload as $key => $value) {
            $newKey = array_key_exists($key, $mapping)
                ? $mapping[$key]
                : $key;

            $mappedPayload[$newKey] = $this->mapKeys($value, $mapping);
        }

        return $mappedPayload;
    }
}