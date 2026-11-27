<?php

namespace App\Repositories;

use Illuminate\Database\Query\Builder;
use InvalidArgumentException;

final readonly class ReadPage
{
    public function __construct(public int $number = 1, public int $size = 50)
    {
        if ($size < 1 || $size > 100 || $number < 1 || $number > intdiv(PHP_INT_MAX, $size)) {
            throw new InvalidArgumentException('Use a positive page number and between 1 and 100 records per page.');
        }
    }

    public function apply(Builder $query): Builder
    {
        return $query->forPage($this->number, $this->size);
    }
}
