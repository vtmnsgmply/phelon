<?php

namespace App\Repositories;

use DateTimeInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class ResourcePurchaseRepository
{
    public function find(string $transaction): ?object
    {
        return $this->forTransaction($transaction)
            ->first(['transactionId', 'total', 'access_code', 'expiry_code', 'email']);
    }

    public function replaceCode(string $transaction, string $code, DateTimeInterface $expiresAt): void
    {
        $this->forTransaction($transaction)->update([
            'access_code' => $code,
            'expiry_code' => $expiresAt,
        ]);
    }

    private function forTransaction(string $transaction): Builder
    {
        return DB::table('customers')->where('transactionId', $transaction);
    }
}
