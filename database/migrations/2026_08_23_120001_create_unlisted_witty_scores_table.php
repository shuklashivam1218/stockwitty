<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unlisted_witty_scores', function (Blueprint $table) {
            $table->id('UL_WS_ID');
            $table->integer('UL_WS_FINCODE')->index();

            // Each pillar is scored 0-10 by an admin; the overall WittyScore is
            // computed from these via the fixed weights on the methodology page
            // (Financial Health 30%, Valuation 20%, Growth Potential 20%,
            // IPO Probability 15%, Liquidity & Safety 15%) rather than stored,
            // so it can never drift out of sync with the pillar inputs.
            $table->double('UL_WS_FINANCIAL_HEALTH')->nullable();
            $table->double('UL_WS_VALUATION')->nullable();
            $table->double('UL_WS_GROWTH_POTENTIAL')->nullable();
            $table->double('UL_WS_IPO_PROBABILITY')->nullable();
            $table->double('UL_WS_LIQUIDITY_SAFETY')->nullable();

            $table->string('UL_WS_ACTIVE', 1)->default('1');
            $table->timestamp('UL_WS_INSERT_TIME')->nullable();
            $table->timestamp('UL_WS_UPDATE_TIME')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unlisted_witty_scores');
    }
};
