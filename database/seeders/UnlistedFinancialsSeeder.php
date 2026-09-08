<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\UpsertsSqlDump;
use Illuminate\Database\Seeder;

/**
 * One-time import from a live-site SQL export
 * (u397570845_unlisted_gain (1).sql, Sep 2026 — financial statements only).
 * Wipes unlisted_financials and reloads it from the dump.
 *
 * Run UnlistedStocksSeeder first — financial rows reference fincodes that
 * must already exist in unlisted_stocks.
 */
class UnlistedFinancialsSeeder extends Seeder
{
    use UpsertsSqlDump;

    public function run(): void
    {
        $rows = $this->replaceTableFromSqlFile('unlisted_financials', __DIR__ . '/data/unlisted_financials.sql');

        $this->command?->info("Unlisted financials: inserted {$rows} rows.");
    }
}
