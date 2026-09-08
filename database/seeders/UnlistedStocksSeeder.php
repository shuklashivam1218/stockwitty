<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\UpsertsSqlDump;
use Illuminate\Database\Seeder;

/**
 * One-time import from a live-site SQL export
 * (u397570845_unlisted_gain (1).sql, Sep 2026 — company master data only).
 * Wipes unlisted_stocks and reloads it from the dump.
 */
class UnlistedStocksSeeder extends Seeder
{
    use UpsertsSqlDump;

    public function run(): void
    {
        $rows = $this->replaceTableFromSqlFile('unlisted_stocks', __DIR__ . '/data/unlisted_stocks.sql');

        $this->command?->info("Unlisted stocks: inserted {$rows} rows.");
    }
}
