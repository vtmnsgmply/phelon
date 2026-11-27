<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use PDOException;

final class InspectSchema extends Command
{
    protected $signature = 'phelon:schema';
    protected $description = 'Check the configured database and list table metadata without reading records';

    public function handle(): int
    {
        try {
            foreach (Schema::getTableListing(Schema::getCurrentSchemaName(), schemaQualified: false) as $table) {
                $this->line($table);
            }
        } catch (PDOException $error) {
            $this->error('Schema check failed. Driver code: '.($error->errorInfo[1] ?? 'unavailable').'.');
            return self::FAILURE;
        }
        return self::SUCCESS;
    }
}
