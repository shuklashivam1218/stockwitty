<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unlisted_company_insights', function (Blueprint $table) {
            $table->longText('UL_CI_TLDR')->nullable()->after('UL_CI_AI_SUMMARY');

            // One item per line
            $table->longText('UL_CI_BULL_CASE')->nullable()->after('UL_CI_IPO_FACTS');
            $table->longText('UL_CI_BEAR_CASE')->nullable()->after('UL_CI_BULL_CASE');
            $table->longText('UL_CI_SUITS_IF')->nullable()->after('UL_CI_BEAR_CASE');
            $table->longText('UL_CI_NOT_SUITS_IF')->nullable()->after('UL_CI_SUITS_IF');
            // "Label | body" per line
            $table->longText('UL_CI_RISKS')->nullable()->after('UL_CI_NOT_SUITS_IF');
            $table->longText('UL_CI_VERDICT_LONG')->nullable()->after('UL_CI_RISKS');
        });
    }

    public function down(): void
    {
        Schema::table('unlisted_company_insights', function (Blueprint $table) {
            $table->dropColumn([
                'UL_CI_TLDR', 'UL_CI_BULL_CASE', 'UL_CI_BEAR_CASE',
                'UL_CI_SUITS_IF', 'UL_CI_NOT_SUITS_IF', 'UL_CI_RISKS', 'UL_CI_VERDICT_LONG',
            ]);
        });
    }
};
