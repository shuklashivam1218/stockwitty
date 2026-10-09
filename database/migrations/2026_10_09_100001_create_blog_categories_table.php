<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('slug', 80)->unique();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seeded here rather than in a seeder: deploy.sh only runs migrate,
        // and the blog index filter chips need these from day one.
        $now = now();
        DB::table('blog_categories')->insert(array_map(fn ($c) => $c + [
            'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
        ], [
            ['name' => 'Basics',           'slug' => 'basics',           'sort_order' => 1],
            ['name' => 'Buying & Selling', 'slug' => 'buying-selling',   'sort_order' => 2],
            ['name' => 'Tax',              'slug' => 'tax',              'sort_order' => 3],
            ['name' => 'Analysis',         'slug' => 'analysis',         'sort_order' => 4],
            ['name' => 'Glossary',         'slug' => 'glossary',         'sort_order' => 5],
        ]));
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
