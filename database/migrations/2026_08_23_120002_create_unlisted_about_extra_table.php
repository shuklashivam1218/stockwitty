<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unlisted_about_extra', function (Blueprint $table) {
            $table->id('UL_ABX_ID');
            $table->integer('UL_ABX_FINCODE')->index();

            // Prose sections (free text, rendered as paragraphs)
            $table->longText('UL_ABX_OVERVIEW')->nullable();
            $table->longText('UL_ABX_OPERATIONS')->nullable();
            $table->longText('UL_ABX_GEOGRAPHY')->nullable();
            $table->longText('UL_ABX_INDUSTRY_POSITION')->nullable();
            $table->longText('UL_ABX_SHAREHOLDING')->nullable();
            $table->longText('UL_ABX_INVESTOR_INTEREST')->nullable();
            $table->longText('UL_ABX_MARKET_LANDSCAPE')->nullable();
            $table->longText('UL_ABX_COMPETITIVE_STRENGTH')->nullable();

            // List sections — one item per line, parsed on render.
            // Verticals / revenue segments / products: "Title | description"
            // History: "Year | milestone text"
            // Sources: "Label | URL"
            $table->longText('UL_ABX_VERTICALS')->nullable();
            $table->longText('UL_ABX_REVENUE_SEGMENTS')->nullable();
            $table->longText('UL_ABX_HISTORY')->nullable();
            $table->longText('UL_ABX_PRODUCTS_SERVICES')->nullable();
            $table->longText('UL_ABX_SOURCES')->nullable();

            // SWOT — one point per line, per quadrant
            $table->text('UL_ABX_SWOT_STRENGTHS')->nullable();
            $table->text('UL_ABX_SWOT_WEAKNESSES')->nullable();
            $table->text('UL_ABX_SWOT_OPPORTUNITIES')->nullable();
            $table->text('UL_ABX_SWOT_THREATS')->nullable();

            $table->string('UL_ABX_ACTIVE', 1)->default('1');
            $table->timestamp('UL_ABX_INSERT_TIME')->nullable();
            $table->timestamp('UL_ABX_UPDATE_TIME')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unlisted_about_extra');
    }
};
