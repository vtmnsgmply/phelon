<?php

namespace App\Repositories;

use DateTimeInterface;

final readonly class BookingCriteria
{
    public function __construct(
        public ?int $id = null,
        public ?string $reference = null,
        public ?string $service = null,
        public ?string $subtype = null,
        public ?DateTimeInterface $startsFrom = null,
        public ?DateTimeInterface $startsThrough = null,
    ) {}
}
