<?php

return [
    'migration_admins' => array_values(array_filter(array_map(
        static fn (string $email): string => strtolower(trim($email)),
        explode(',', (string) env('PHELON_MIGRATION_ADMINS', '')),
    ))),
    'booking' => [
        'timezone' => env('PHELON_BOOKING_TIMEZONE'),
        'open' => env('PHELON_BOOKING_OPEN'),
        'close' => env('PHELON_BOOKING_CLOSE'),
        'days' => env('PHELON_BOOKING_DAYS'),
    ],
    'library_enabled' => env('PHELON_LIBRARY_ENABLED', false),
    'legacy_intake_enabled' => env('PHELON_LEGACY_INTAKE_ENABLED', false),
    'checkout' => [
        '47' => env('PHELON_CHECKOUT_47_URL', 'https://link.fastpaydirect.com/payment-link/6a7b3f8073c7ff66b05e7a31'),
        '497' => env('PHELON_CHECKOUT_497_URL'),
        '3497' => env('PHELON_CHECKOUT_3497_URL'),
    ],
];
