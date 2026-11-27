<?php

namespace App\Repositories;

final readonly class IntakeCriteria
{
    public function __construct(
        public ?int $id = null,
        public ?string $reference = null,
    ) {}
}
