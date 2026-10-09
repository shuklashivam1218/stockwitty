<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();          // card excerpt on /blog/ and the homepage
            $table->text('intro')->nullable();            // HTML, shown above the TOC'd body
            $table->longText('content')->nullable();      // HTML from TinyMCE, stored after clean()

            $table->string('featured_image')->nullable(); // "images/blog/featured/..." (ImageUpload)
            $table->string('featured_image_alt')->nullable();
            $table->string('hero_icon', 40)->nullable();  // x-sw.icon name, used when there is no image

            // Structured blocks x-sw.blog-post-layout renders around the body.
            $table->json('chips')->nullable();            // ["Unlisted Shares", "Tax"]
            $table->json('takeaways')->nullable();        // ["...", "..."]
            $table->json('faqs')->nullable();             // [{tab, q, a}]
            $table->json('sources')->nullable();          // [{label, href}]
            $table->json('video')->nullable();            // {url, caption}
            $table->json('related_post_ids')->nullable(); // [3, 7]

            $table->string('lead_heading')->nullable();   // null -> layout default
            $table->text('lead_subtext')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();

            // draft | published — any author or reviewer can publish
            $table->string('status', 20)->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('reading_minutes')->default(1);
            $table->timestamp('published_at')->nullable();

            // nullOnDelete: removing an admin user must not be blocked by, or
            // delete, the posts they wrote — the byline falls back instead.
            $table->foreignId('created_by')->nullable()->constrained('users', 'uid')->nullOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users', 'uid')->nullOnDelete();
            $table->foreignId('locked_by')->nullable()->constrained('users', 'uid')->nullOnDelete();
            $table->timestamp('locked_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
