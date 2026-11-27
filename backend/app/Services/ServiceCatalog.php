<?php

namespace App\Services;

final class ServiceCatalog
{
    private ?array $entries = null;

    private function entries(): array
    {
        return $this->entries ??= json_decode(
            file_get_contents(resource_path('catalog/services.json')), true, flags: JSON_THROW_ON_ERROR
        );
    }

    public function label(?string $key, ?string $subtype): string
    {
        $service = $key === null ? null : ($this->entries()[$key] ?? null);
        if ($subtype !== null && isset($service['subtypes'][$subtype])) {
            return $service['subtypes'][$subtype]['title'];
        }
        return $service['title'] ?? 'General / not sure yet';
    }

    public function resolve(?string $interest): ?array
    {
        if ($interest === null || $interest === '') {
            return [null, null];
        }
        [$key, $subtype] = array_pad(explode('/', $interest, 2), 2, null);
        $catalog = $this->entries();
        if (!isset($catalog[$key]) || ($subtype !== null && !isset($catalog[$key]['subtypes'][$subtype]))) {
            return null;
        }
        return [$key, $subtype];
    }
}
