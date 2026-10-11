<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A stock's About, Thesis and WittyScore pages are CMS articles now
 * (stock_articles). This removes what the old editors stored and nothing
 * reads any more: the rich-text thesis and about tables (their content was
 * imported as drafts by the migration just before this one), the structured
 * About Page table, the thesis-page insight columns and the About/Thesis
 * SEO columns. down() puts the structure back, empty — data comes from the
 * pre-deploy backup.
 */
return new class extends Migration
{
    private const INSIGHT_COLUMNS = [
        'UL_CI_TLDR', 'UL_CI_BULL_CASE', 'UL_CI_BEAR_CASE', 'UL_CI_SUITS_IF',
        'UL_CI_NOT_SUITS_IF', 'UL_CI_RISKS', 'UL_CI_VERDICT_LONG',
    ];

    private const SEO_COLUMNS = [
        'UL_SEO_ABOUT_TITLE', 'UL_SEO_ABOUT_DESCRIPTION', 'UL_SEO_ABOUT_KEYWORDS',
        'UL_SEO_THESIS_TITLE', 'UL_SEO_THESIS_DESCRIPTION', 'UL_SEO_THESIS_KEYWORDS',
    ];

    public function up(): void
    {
        Schema::dropIfExists('unlisted_thesis');
        Schema::dropIfExists('unlisted_about');
        Schema::dropIfExists('unlisted_about_extra');

        $this->dropColumns('unlisted_company_insights', self::INSIGHT_COLUMNS);
        $this->dropColumns('unlisted_seo_meta', self::SEO_COLUMNS);
    }

    public function down(): void
    {
        foreach (['unlisted_thesis' => 'UL_THESIS', 'unlisted_about' => 'UL_ABOUT'] as $table => $p) {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $t) use ($p) {
                    $t->integer("{$p}_ID")->autoIncrement()->primary();
                    $t->integer("{$p}_FINCODE")->nullable()->index();
                    $t->longText("{$p}_CONTENT")->nullable();
                    $t->string("{$p}_ACTIVE", 1)->nullable();
                    $t->timestamp("{$p}_INSERT_TIME")->nullable();
                    $t->timestamp("{$p}_UPDATE_TIME")->nullable();
                });
            }
        }

        if (!Schema::hasTable('unlisted_about_extra')) {
            Schema::create('unlisted_about_extra', function (Blueprint $t) {
                $t->id('UL_ABX_ID');
                $t->integer('UL_ABX_FINCODE')->index();
                foreach ([
                    'OVERVIEW', 'OPERATIONS', 'GEOGRAPHY', 'INDUSTRY_POSITION', 'SHAREHOLDING',
                    'INVESTOR_INTEREST', 'MARKET_LANDSCAPE', 'COMPETITIVE_STRENGTH', 'VERTICALS',
                    'REVENUE_SEGMENTS', 'HISTORY', 'PRODUCTS_SERVICES', 'SOURCES',
                ] as $c) {
                    $t->longText("UL_ABX_{$c}")->nullable();
                }
                foreach (['STRENGTHS', 'WEAKNESSES', 'OPPORTUNITIES', 'THREATS'] as $c) {
                    $t->text("UL_ABX_SWOT_{$c}")->nullable();
                }
                $t->string('UL_ABX_ACTIVE', 1)->default('1');
                $t->timestamp('UL_ABX_INSERT_TIME')->nullable();
                $t->timestamp('UL_ABX_UPDATE_TIME')->nullable();
            });
        }

        Schema::table('unlisted_company_insights', function (Blueprint $t) {
            foreach (self::INSIGHT_COLUMNS as $c) {
                if (!Schema::hasColumn('unlisted_company_insights', $c)) {
                    $t->longText($c)->nullable();
                }
            }
        });

        Schema::table('unlisted_seo_meta', function (Blueprint $t) {
            foreach (self::SEO_COLUMNS as $c) {
                if (!Schema::hasColumn('unlisted_seo_meta', $c)) {
                    $t->string($c, str_ends_with($c, '_TITLE') ? 255 : 500)->nullable();
                }
            }
        });
    }

    private function dropColumns(string $table, array $columns): void
    {
        $present = array_values(array_filter($columns, fn ($c) => Schema::hasColumn($table, $c)));

        if ($present) {
            Schema::table($table, fn (Blueprint $t) => $t->dropColumn($present));
        }
    }
};
