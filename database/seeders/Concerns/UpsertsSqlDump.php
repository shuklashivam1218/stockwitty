<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\DB;

/**
 * Wipes a table and reloads it from a raw mysqldump data file — a clean
 * replace rather than a merge, so the table ends up exactly matching the
 * dump with no stale leftover rows.
 */
trait UpsertsSqlDump
{
    private function replaceTableFromSqlFile(string $table, string $path): int
    {
        DB::table($table)->delete();

        $statements = $this->splitInsertStatements(file_get_contents($path));
        $rows = 0;

        foreach ($statements as $statement) {
            if (!str_starts_with($statement, 'INSERT INTO')) {
                continue;
            }

            // MySQL's legacy zero-date, invalid under strict mode — the
            // columns are nullable, so treat it as "no date" rather than
            // failing the whole batch.
            $statement = str_replace("'0000-00-00 00:00:00'", 'NULL', $statement);

            DB::unprepared($statement);
            $rows += preg_match_all('/^\(/m', $statement);
        }

        return $rows;
    }

    /**
     * Split a dump file into whole `INSERT INTO ... ;` statements. Each
     * statement is a run of lines starting at a line beginning with
     * "INSERT INTO"; blank lines and `--` comment lines (mysqldump's
     * trailing section separators) are dropped rather than accumulated,
     * since they'd otherwise land at the end of the last statement in the
     * file and swallow anything appended after it.
     */
    private function splitInsertStatements(string $sql): array
    {
        $statements = [];
        $current = [];

        foreach (preg_split('/\R/', $sql) as $line) {
            $trimmed = ltrim($line);

            if (str_starts_with($trimmed, 'INSERT INTO')) {
                if ($current) {
                    $statements[] = implode("\n", $current);
                }
                $current = [$line];
            } elseif ($current && $trimmed !== '' && !str_starts_with($trimmed, '--')) {
                $current[] = $line;
            }
        }

        if ($current) {
            $statements[] = implode("\n", $current);
        }

        return $statements;
    }
}
