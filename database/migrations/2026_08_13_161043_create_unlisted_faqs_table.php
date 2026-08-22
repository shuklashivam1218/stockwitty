<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unlisted_faqs', function (Blueprint $table) {
            $table->id('UL_FAQ_ID');
            $table->integer('UL_FAQ_FINCODE')->index();
            $table->string('UL_FAQ_TARGET', 20);
            $table->string('UL_FAQ_QUESTION', 500);
            $table->text('UL_FAQ_ANSWER')->nullable();
            $table->integer('UL_FAQ_SORT_ORDER')->default(0);
            $table->string('UL_FAQ_ACTIVE', 1)->default('1');
            $table->timestamp('UL_FAQ_INSERT_TIME')->nullable();
            $table->timestamp('UL_FAQ_UPDATE_TIME')->nullable();

            $table->index(['UL_FAQ_FINCODE', 'UL_FAQ_TARGET', 'UL_FAQ_ACTIVE'], 'unlisted_faqs_fincode_target_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unlisted_faqs');
    }
};
