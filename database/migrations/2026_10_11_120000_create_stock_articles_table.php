<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-stock About, Thesis and WittyScore pages, written in the CMS like blog
 * posts. One article per stock per type; the URL comes from the stock's slug
 * and the type (/unlisted-shares/{slug}/{type}/), so there is no slug or
 * category column. The blog-shaped columns mirror blog_posts.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ul_stocks_fincode');
            $table->foreign('ul_stocks_fincode', 'stock_articles_fincode_fk')
                ->references('UL_STOCKS_FINCODE')->on('unlisted_stocks')->cascadeOnDelete();
            $table->string('type', 20);                   // about | thesis | wittyscore

            $table->string('title');
            $table->text('summary')->nullable();
            $table->text('intro')->nullable();
            $table->longText('content')->nullable();      // HTML from TinyMCE, stored after clean()

            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('hero_icon', 40)->nullable();

            $table->json('chips')->nullable();
            $table->json('takeaways')->nullable();
            $table->json('faqs')->nullable();             // [{tab, q, a}]
            $table->json('sources')->nullable();          // [{label, href}]
            $table->json('video')->nullable();            // {youtube_id, caption}
            $table->json('related_post_ids')->nullable(); // blog post ids

            $table->string('lead_heading')->nullable();
            $table->text('lead_subtext')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();

            $table->string('status', 20)->default('draft');
            $table->unsignedSmallInteger('reading_minutes')->default(1);
            $table->timestamp('published_at')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users', 'uid')->nullOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users', 'uid')->nullOnDelete();
            $table->foreignId('locked_by')->nullable()->constrained('users', 'uid')->nullOnDelete();
            $table->timestamp('locked_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Trashed rows count too: a stock gets a trashed article back by
            // restoring it, not by starting a second one.
            $table->unique(['ul_stocks_fincode', 'type'], 'stock_articles_fincode_type_unique');
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_articles');
    }
};
