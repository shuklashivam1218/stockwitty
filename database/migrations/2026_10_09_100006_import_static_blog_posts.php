<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Moves the seven hand-written Blade posts into blog_posts so they become
 * editable from the admin. Data comes from database/data/blog_posts_2026_10.json,
 * exported once from the old views (bodies already passed through the `blog`
 * purifier profile). Lives in a migration, not a seeder, because deploy.sh
 * only runs `migrate` — local and production get exactly the same rows.
 *
 * Plain query builder on purpose: the BlogPost model will keep changing,
 * this migration must not.
 */
return new class extends Migration
{
    private const DATA_FILE = 'data/blog_posts_2026_10.json';

    public function up(): void
    {
        $posts      = $this->posts();
        $categories = DB::table('blog_categories')->pluck('id', 'name');

        foreach ($posts as $p) {
            // Idempotent: never touch a slug that already exists (e.g. re-run, or edited since).
            if (DB::table('blog_posts')->where('slug', $p['slug'])->exists()) {
                continue;
            }

            $publishedAt = Carbon::parse($p['published_at'])->setTimezone(config('app.timezone'));

            DB::table('blog_posts')->insert([
                'category_id'        => $categories[$p['category']] ?? null,
                'title'              => $p['title'],
                'slug'               => $p['slug'],
                'summary'            => $p['summary'],
                'intro'              => $p['intro'],
                'content'            => $p['content'],
                'featured_image'     => $p['featured_image'],
                'featured_image_alt' => $p['featured_image_alt'],
                'hero_icon'          => $p['hero_icon'],
                'chips'              => $this->json($p['chips']),
                'takeaways'          => $this->json($p['takeaways']),
                'faqs'               => $this->json($p['faqs']),
                'sources'            => $this->json($p['sources']),
                'video'              => null,
                'related_post_ids'   => null,
                'lead_heading'       => $p['lead_heading'],
                'lead_subtext'       => $p['lead_subtext'],
                'meta_title'         => $p['meta_title'],
                'meta_description'   => $p['meta_description'],
                'status'             => 'published',
                'is_featured'        => $p['is_featured'],
                'reading_minutes'    => max(1, (int) ceil(str_word_count(strip_tags($p['intro'] . ' ' . $p['content'])) / 200)),
                'published_at'       => $publishedAt,
                'created_at'         => $publishedAt,
                'updated_at'         => Carbon::parse($p['updated_at'])->setTimezone(config('app.timezone')),
            ]);
        }

        // Second pass: related links point at other imported posts by slug.
        $ids = DB::table('blog_posts')->whereIn('slug', array_column($posts, 'slug'))->pluck('id', 'slug');
        foreach ($posts as $p) {
            $related = array_values(array_filter(array_map(fn ($slug) => $ids[$slug] ?? null, $p['related_slugs'])));
            DB::table('blog_posts')->where('slug', $p['slug'])->whereNull('related_post_ids')
                ->update(['related_post_ids' => $this->json($related)]);
        }
    }

    public function down(): void
    {
        DB::table('blog_posts')->whereIn('slug', array_column($this->posts(), 'slug'))->delete();
    }

    private function posts(): array
    {
        return json_decode(file_get_contents(database_path(self::DATA_FILE)), true, 512, JSON_THROW_ON_ERROR);
    }

    private function json(?array $value): ?string
    {
        return $value ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;
    }
};
