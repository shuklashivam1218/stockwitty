<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unlisted_company_insights', function (Blueprint $table) {
            $table->id('UL_CI_ID');
            $table->integer('UL_CI_FINCODE')->index();

            $table->longText('UL_CI_AI_SUMMARY')->nullable();

            $table->string('UL_CI_FOUNDERS_INTRO', 500)->nullable();
            $table->text('UL_CI_FOUNDERS_QUOTE')->nullable();
            $table->longText('UL_CI_FOUNDERS_VERDICT')->nullable();

            // One item per line, parsed on render:
            // Timeline: "Date | milestone text"   Facts: "Label | Value"
            $table->longText('UL_CI_IPO_TIMELINE')->nullable();
            $table->longText('UL_CI_IPO_FACTS')->nullable();

            $table->string('UL_CI_ACTIVE', 1)->default('1');
            $table->timestamp('UL_CI_INSERT_TIME')->nullable();
            $table->timestamp('UL_CI_UPDATE_TIME')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unlisted_company_insights');
    }
};
