<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Carries the old rich-text About (unlisted_about) and Thesis (unlisted_thesis)
 * content into stock_articles as drafts, so nothing is lost when those
 * editors go. Drafts aren't public; an editor reviews and publishes them.
 * The old tables are left untouched.
 */
return new class extends Migration
{
    private const SOURCES = [
        'about'  => ['unlisted_about', 'UL_ABOUT', 'Company Profile', 'landmark'],
        'thesis' => ['unlisted_thesis', 'UL_THESIS', 'Investment Thesis', 'file-search'],
    ];

    public function up(): void
    {
        $now = now();

        foreach (self::SOURCES as $type => [$table, $p, $titleSuffix, $icon]) {
            // The newest row per stock is the live one, as the old editor saved it.
            $latest = DB::table($table)
                ->selectRaw("{$p}_FINCODE as fincode, MAX({$p}_ID) as id")
                ->groupBy("{$p}_FINCODE");

            $rows = DB::table("{$table} as t")
                ->joinSub($latest, 'latest', 'latest.id', '=', "t.{$p}_ID")
                ->join('unlisted_stocks as s', 's.UL_STOCKS_FINCODE', '=', "t.{$p}_FINCODE")
                ->whereNotNull("t.{$p}_CONTENT")
                ->where("t.{$p}_CONTENT", '!=', '')
                ->get(['s.UL_STOCKS_FINCODE as fincode', 's.UL_STOCKS_COMPNAME as name', "t.{$p}_CONTENT as content"]);

            foreach ($rows as $row) {
                $exists = DB::table('stock_articles')->where('ul_stocks_fincode', $row->fincode)->where('type', $type)->exists();
                $html   = clean($row->content, 'blog');

                if ($exists || trim(strip_tags($html)) === '') {
                    continue;
                }

                DB::table('stock_articles')->insert([
                    'ul_stocks_fincode' => $row->fincode,
                    'type'              => $type,
                    'title'             => $row->name . ': ' . $titleSuffix,
                    'content'           => $html,
                    'hero_icon'         => $icon,
                    'status'            => 'draft',
                    'reading_minutes'   => max(1, (int) ceil(str_word_count(strip_tags($html)) / 200)),
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Imported drafts are the only rows with no author; anything an editor
        // created or published stays.
        DB::table('stock_articles')
            ->whereIn('type', array_keys(self::SOURCES))
            ->where('status', 'draft')
            ->whereNull('created_by')
            ->delete();
    }
};
