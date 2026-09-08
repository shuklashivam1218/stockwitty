<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unlisted_seo_meta', function (Blueprint $table) {
            $table->id('UL_SEO_ID');
            $table->integer('UL_SEO_FINCODE')->index();

            // Main company page (/unlisted-shares/{slug}/)
            $table->string('UL_SEO_COMPANY_TITLE', 255)->nullable();
            $table->string('UL_SEO_COMPANY_DESCRIPTION', 500)->nullable();
            $table->string('UL_SEO_COMPANY_KEYWORDS', 500)->nullable();

            // About page (/unlisted-shares/{slug}/about/)
            $table->string('UL_SEO_ABOUT_TITLE', 255)->nullable();
            $table->string('UL_SEO_ABOUT_DESCRIPTION', 500)->nullable();
            $table->string('UL_SEO_ABOUT_KEYWORDS', 500)->nullable();

            // Thesis page (/unlisted-shares/{slug}/thesis/)
            $table->string('UL_SEO_THESIS_TITLE', 255)->nullable();
            $table->string('UL_SEO_THESIS_DESCRIPTION', 500)->nullable();
            $table->string('UL_SEO_THESIS_KEYWORDS', 500)->nullable();

            $table->string('UL_SEO_ACTIVE', 1)->default('1');
            $table->timestamp('UL_SEO_INSERT_TIME')->nullable();
            $table->timestamp('UL_SEO_UPDATE_TIME')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unlisted_seo_meta');
    }
};
