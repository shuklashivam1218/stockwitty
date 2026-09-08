<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\UpsertsSqlDump;
use Illuminate\Database\Seeder;

/**
 * One-time import from a live-site SQL export
 * (u397570845_unlisted_gain (1).sql, Sep 2026 — price history only).
 * Wipes unlisted_price_data and reloads it from the dump.
 *
 * Run UnlistedStocksSeeder first — price rows reference fincodes that
 * must already exist in unlisted_stocks.
 */
class UnlistedPriceDataSeeder extends Seeder
{
    use UpsertsSqlDump;

    public function run(): void
    {
        $rows = $this->replaceTableFromSqlFile('unlisted_price_data', __DIR__ . '/data/unlisted_price_data.sql');

        $this->command?->info("Unlisted price data: inserted {$rows} rows.");
    }
}
