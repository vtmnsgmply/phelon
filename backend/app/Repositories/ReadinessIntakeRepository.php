<?php

namespace App\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class ReadinessIntakeRepository
{
    private const TABLE = 'phelon_intakes';

    public function exists(string $reference): bool
    {
        return $this->query(new IntakeCriteria(reference: $reference))->exists();
    }

    public function legacyAppointmentExists(string $reference): bool
    {
        return DB::table('intake')->where('appointment_id', $reference)->where('intake', 0)->exists();
    }

    public function create(string $reference, string $answers, string $attachments): void
    {
        DB::table(self::TABLE)->insert([
            'appointment_ref' => $reference,
            'answers' => $answers,
            'files' => $attachments,
        ]);
    }

    /** @return Collection<int, \stdClass> */
    public function findIntakes(IntakeCriteria $criteria = new IntakeCriteria(), ReadPage $page = new ReadPage(), bool $withDetails = false): Collection
    {
        $columns = ['id', 'appointment_ref', 'created_at'];
        return $page->apply($this->query($criteria)->orderByDesc('id'))
            ->get($withDetails ? [...$columns, 'answers', 'files'] : $columns);
    }

    private function query(IntakeCriteria $criteria): Builder
    {
        $query = DB::table(self::TABLE);
        if ($criteria->id !== null) {
            $query->where('id', $criteria->id);
        }
        if ($criteria->reference !== null) {
            $query->where('appointment_ref', $criteria->reference);
        }
        return $query;
    }
}
