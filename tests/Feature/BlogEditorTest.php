<?php

namespace Tests\Feature;

use App\Helpers\Privilege;
use App\Http\Controllers\BlogPostsController;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Phase 3: the structured blocks around a post body and the `blog`
 * purifier profile. DatabaseTransactions only — never RefreshDatabase.
 */
class BlogEditorTest extends TestCase
{
    use DatabaseTransactions;

    private User $author;

    protected function setUp(): void
    {
        parent::setUp();
        (new \ReflectionProperty(Privilege::class, 'cache'))->setValue(null, null);

        $this->author = User::create([
            'name' => 'Editor Tester', 'email' => 'editor_' . uniqid() . '@example.test',
            'phone' => '9' . random_int(100000000, 999999999), 'unlisted_user_type' => 'unlisted',
            'password' => 'secret-password', 'privilege' => ['author' => true],
        ]);
        $this->withSession(['uid' => $this->author->uid, 'privilege' => $this->author->privilege]);
    }

    private function draft(array $fields): \Illuminate\Testing\TestResponse
    {
        return $this->post('/admin/blog', $fields + ['title' => 'Blocks ' . uniqid(), 'status' => 'draft']);
    }

    public function test_purifier_classes_match_the_editor_styles(): void
    {
        $this->assertEqualsCanonicalizing(
            array_keys(config('blog.content_classes')),
            config('purifier.settings.blog')['Attr.AllowedClasses'] // the key itself contains a dot
        );
    }

    public function test_every_hero_icon_exists(): void
    {
        $iconFile = file_get_contents(resource_path('views/components/sw/icon.blade.php'));

        foreach (config('blog.hero_icons') as $icon) {
            $this->assertStringContainsString("'{$icon}' =>", $iconFile, "Icon {$icon} is missing from x-sw.icon");
        }
    }

    public function test_body_keeps_only_styled_block_classes_and_safe_markup(): void
    {
        $this->draft(['content' =>
            '<div class="sw-callout other"><p><strong>Note</strong> body</p></div>'
            . '<ul class="sw-checklist"><li>One</li></ul>'
            . '<p style="color:red;text-align:center">x</p>'
            . '<img src="data:image/png;base64,AAAA" alt="d">'
            . '<iframe src="https://evil.example"></iframe>',
        ])->assertSessionHasNoErrors();

        $html = BlogPost::latest('id')->value('content');

        $this->assertStringContainsString('<div class="sw-callout">', $html);
        $this->assertStringContainsString('<ul class="sw-checklist">', $html);
        $this->assertStringContainsString('text-align:center', $html);
        foreach (['other', 'color:red', 'data:image', '<iframe'] as $gone) {
            $this->assertStringNotContainsString($gone, $html);
        }
    }

    public function test_structured_blocks_are_saved_as_clean_arrays(): void
    {
        $this->draft([
            'intro'          => "First para.\n\nSecond <b>para</b>.",
            'chips_text'     => 'Unlisted Shares, Tax , , 2026',
            'takeaways_text' => "One\n\n  Two  \n<script>x</script>Three",
            'faqs'           => [
                ['tab' => 'Basics', 'q' => 'What?', 'a' => 'This.'],
                ['tab' => '', 'q' => '', 'a' => ''],             // blank row: dropped
                ['tab' => '', 'q' => 'No tab?', 'a' => 'Gets General.'],
            ],
            'sources'        => [
                ['label' => 'SEBI', 'href' => 'https://www.sebi.gov.in/'],
                ['label' => '', 'href' => ''],
            ],
            'video_url'      => 'https://youtu.be/dQw4w9WgXcQ?t=10',
            'video_caption'  => 'Watch this',
            'hero_icon'      => 'receipt',
        ])->assertSessionHasNoErrors();

        $post = BlogPost::latest('id')->first();

        $this->assertSame("First para.\n\nSecond para.", $post->intro);
        $this->assertSame(['Unlisted Shares', 'Tax', '2026'], $post->chips);
        $this->assertSame(['One', 'Two', 'xThree'], $post->takeaways);
        $this->assertCount(2, $post->faqs);
        $this->assertSame('General', $post->faqs[1]['tab']);
        $this->assertSame([['label' => 'SEBI', 'href' => 'https://www.sebi.gov.in/']], $post->sources);
        $this->assertSame(['youtube_id' => 'dQw4w9WgXcQ', 'caption' => 'Watch this'], $post->video);
        $this->assertSame('receipt', $post->hero_icon);
    }

    public function test_faq_tab_cannot_break_out_of_an_alpine_expression(): void
    {
        $this->draft(['faqs' => [['tab' => "x'); alert(1); ('", 'q' => 'Q', 'a' => 'A']]])
            ->assertSessionHasErrors('faqs.0.tab');
    }

    public function test_unsafe_or_wrong_links_are_rejected(): void
    {
        $this->draft([
            'sources'   => [['label' => 'Bad', 'href' => 'javascript:alert(1)']],
            'video_url' => 'https://vimeo.com/123',
            'hero_icon' => 'not-an-icon',
        ])->assertSessionHasErrors(['sources.0.href', 'video_url', 'hero_icon']);
    }

    public function test_limits_and_related_posts(): void
    {
        $this->draft(['takeaways_text' => implode("\n", range(1, config('blog.max_takeaways') + 1))])
            ->assertSessionHasErrors('takeaways');

        $a = BlogPost::create(['title' => 'A', 'slug' => 'a-' . uniqid()]);
        $b = BlogPost::create(['title' => 'B', 'slug' => 'b-' . uniqid()]);

        // A post can't list itself as related.
        $this->put("/admin/blog/{$a->id}", ['title' => 'A', 'status' => 'draft', 'related_post_ids' => [$a->id]])
            ->assertSessionHasErrors('related_post_ids.0');

        $this->put("/admin/blog/{$a->id}", ['title' => 'A', 'status' => 'draft', 'related_post_ids' => [$b->id]])
            ->assertSessionHasNoErrors();
        $this->assertSame([$b->id], $a->fresh()->related_post_ids);
    }

    public function test_youtube_id_parsing(): void
    {
        $id = 'dQw4w9WgXcQ';
        foreach ([
            "https://www.youtube.com/watch?v={$id}", "https://youtube.com/watch?feature=share&v={$id}",
            "https://youtu.be/{$id}", "https://www.youtube.com/shorts/{$id}", "https://m.youtube.com/watch?v={$id}&t=3",
            "https://www.youtube.com/embed/{$id}",
        ] as $url) {
            $this->assertSame($id, BlogPostsController::youtubeId($url), $url);
        }

        foreach (['https://vimeo.com/1', "https://evil.com/watch?v={$id}", "https://www.youtube.com.evil.com/watch?v={$id}", ''] as $url) {
            $this->assertNull(BlogPostsController::youtubeId($url), $url);
        }
    }

    public function test_editor_page_renders_with_blocks(): void
    {
        $post = BlogPost::create([
            'title' => 'Render', 'slug' => 'render-' . uniqid(), 'category_id' => BlogCategory::value('id'),
            'faqs' => [['tab' => 'Basics', 'q' => 'Q <b>1</b>', 'a' => 'A1']],
            'sources' => [['label' => 'SEBI', 'href' => 'https://www.sebi.gov.in/']],
        ]);

        $this->get("/admin/blog/{$post->id}/edit")
            ->assertOk()
            ->assertSee('faqs[0][q]', false)
            ->assertSee('Q &lt;b&gt;1&lt;/b&gt;', false)
            ->assertSee('id="faqRowTemplate"', false)
            ->assertSee("selector: 'ul', classes: 'sw-checklist'", false);
    }
}
