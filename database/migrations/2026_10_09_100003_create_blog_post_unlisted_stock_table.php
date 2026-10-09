<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_post_unlisted_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->unsignedBigInteger('ul_stocks_fincode');
            $table->foreign('ul_stocks_fincode', 'bpus_fincode_fk')
                ->references('UL_STOCKS_FINCODE')->on('unlisted_stocks')->cascadeOnDelete();
            $table->unique(['blog_post_id', 'ul_stocks_fincode'], 'bpus_post_fincode_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_post_unlisted_stock');
    }
};
