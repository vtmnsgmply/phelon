<?php

require_once __DIR__ . '/service-catalog.php';

function phelon_discovery_options(): array
{
    $options = ['' => 'General / not sure yet'];
    foreach (phelon_services() as $slug => $service) {
        $options[$slug] = $service['title'];
        foreach ($service['subtypes'] ?? [] as $key => $subtype) {
            $options[$slug . '/' . $key] = $subtype['title'];
        }
    }
    return $options;
}

function phelon_discovery_selection(array $query): ?array
{
    $service = $query['service'] ?? null;
    $subtype = $query['subservice'] ?? null;
    if (array_key_exists('interest', $query)) {
        if ($service !== null || $subtype !== null || !is_string($query['interest'])) {
            return null;
        }
        if ($query['interest'] !== '') {
            $parts = explode('/', $query['interest'], 2);
            [$service, $subtype] = [$parts[0], $parts[1] ?? null];
        }
    }

    if ($service === null && $subtype === null) {
        return ['service' => null, 'subtype' => null, 'option' => '', 'label' => null, 'code' => null];
    }

    $entry = is_string($service) ? (phelon_services()[$service] ?? null) : null;
    if ($entry === null || ($subtype !== null && (!is_string($subtype) || !isset($entry['subtypes'][$subtype])))) {
        return null;
    }

    return [
        'service' => $service,
        'subtype' => $subtype,
        'option' => $service . ($subtype === null ? '' : '/' . $subtype),
        'label' => $subtype === null ? $entry['title'] : $entry['subtypes'][$subtype]['title'],
        'code' => $subtype ?? $service,
    ];
}

