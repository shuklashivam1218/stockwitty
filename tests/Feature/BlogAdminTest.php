<?php

namespace Tests\Feature;

use App\Helpers\Privilege;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSlugRedirect;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Admin blog CMS: who gets in, what gets stored, and the edit lock.
 * Uses DatabaseTransactions against the local MySQL DB — never RefreshDatabase.
 */
class BlogAdminTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetPrivilegeCache();
    }

    /** Privilege::get() memoises per process; each acting user needs a fresh read. */
    private function resetPrivilegeCache(): void
    {
        (new \ReflectionProperty(Privilege::class, 'cache'))->setValue(null, null);
    }

    private function makeUser(array $privilege): User
    {
        return User::create([
            'name' => 'Blog Tester', 'email' => 'blog_' . uniqid() . '@example.test',
            'phone' => '9' . random_int(100000000, 999999999), 'unlisted_user_type' => 'unlisted',
            'password' => 'secret-password', 'privilege' => $privilege,
        ]);
    }

    private function actingAsAdmin(User $user): static
    {
        $this->resetPrivilegeCache();

        return $this->withSession(['uid' => $user->uid, 'name' => $user->name, 'privilege' => $user->privilege]);
    }

    private function publishable(array $overrides = []): array
    {
        return $overrides + [
            'title'            => 'How unlisted shares are taxed',
            'summary'          => 'A short summary.',
            'content'          => '<h2>Intro</h2><p>Body text.</p>',
            'category_id'      => BlogCategory::where('slug', 'tax')->value('id'),
            'meta_title'       => 'Tax on unlisted shares',
            'meta_description' => 'How tax works.',
            'status'           => 'published',
        ];
    }

    public function test_only_authors_and_reviewers_get_in(): void
    {
        $this->get('/admin/blog')->assertRedirect(route('login'));

        foreach ([['admin' => true], ['user_master' => true], ['unlisted' => ['unlisted_stocks' => true]]] as $priv) {
            $this->actingAsAdmin($this->makeUser($priv))->get('/admin/blog')->assertForbidden();
        }

        $author = $this->makeUser(['author' => true]);
        foreach (['/admin/blog', '/admin/blog/create', '/admin/blog/categories', '/admin/blog/profile'] as $page) {
            $this->actingAsAdmin($author)->get($page)->assertOk();
        }
        $this->actingAsAdmin($this->makeUser(['reviewer' => true]))->get('/admin/blog/create')->assertOk();
    }

    public function test_draft_can_be_saved_half_written(): void
    {
        $author = $this->makeUser(['author' => true]);

        $this->actingAsAdmin($author)
            ->post('/admin/blog', ['title' => 'Work in progress', 'status' => 'draft'])
            ->assertRedirect();

        $post = BlogPost::where('title', 'Work in progress')->firstOrFail();
        $this->assertSame('draft', $post->status);
        $this->assertSame('work-in-progress', $post->slug);
        $this->assertSame($author->uid, $post->created_by);
        $this->assertNull($post->published_at);
    }

    public function test_publishing_requires_the_public_fields(): void
    {
        $this->actingAsAdmin($this->makeUser(['author' => true]))
            ->post('/admin/blog', ['title' => 'Too thin', 'status' => 'published'])
            ->assertSessionHasErrors(['summary', 'content', 'category_id', 'meta_title', 'meta_description']);
    }

    public function test_author_can_publish_and_html_is_purified(): void
    {
        $author = $this->makeUser(['author' => true]);

        $this->actingAsAdmin($author)->post('/admin/blog', $this->publishable([
            'title'   => '<b>Tax</b> guide',
            'content' => '<h2>Intro</h2><p onclick="x()">Hi</p><script>alert(1)</script><a href="javascript:alert(1)">bad</a>',
            'summary' => '<img src=x onerror=alert(1)>Summary',
        ]))->assertRedirect();

        $post = BlogPost::latest('id')->firstOrFail();
        $this->assertTrue($post->isPublished());
        $this->assertNotNull($post->published_at);
        $this->assertSame($author->uid, $post->published_by);
        $this->assertSame('Tax guide', $post->title);
        $this->assertSame('Summary', $post->summary);
        foreach (['<script', 'onclick', 'javascript:'] as $bad) {
            $this->assertStringNotContainsString($bad, $post->content);
        }
        $this->assertStringContainsString('<h2>Intro</h2>', $post->content);
    }

    public function test_second_editor_is_locked_out_until_the_first_leaves(): void
    {
        $alice = $this->makeUser(['author' => true]);
        $bob   = $this->makeUser(['reviewer' => true]);
        $post  = BlogPost::create(['title' => 'Locked', 'slug' => 'locked-' . uniqid(), 'created_by' => $alice->uid]);

        $this->actingAsAdmin($alice)->get("/admin/blog/{$post->id}/edit")->assertOk()->assertDontSee('is currently editing this post');

        $this->actingAsAdmin($bob)->get("/admin/blog/{$post->id}/edit")
            ->assertOk()->assertSee('is currently editing this post');

        // Server-side: Bob's save, publish and trash are all refused.
        $this->actingAsAdmin($bob)->put("/admin/blog/{$post->id}", ['title' => 'Overwritten', 'status' => 'draft'])
            ->assertSessionHas('lock_error');
        $this->actingAsAdmin($bob)->postJson("/admin/blog/{$post->id}/publish-toggle")->assertStatus(423);
        $this->actingAsAdmin($bob)->deleteJson("/admin/blog/{$post->id}")->assertStatus(423);
        $this->assertSame('Locked', $post->fresh()->title);
        $this->assertFalse($post->fresh()->trashed());

        // Alice leaves; Bob can now take over.
        $this->actingAsAdmin($alice)->post("/admin/blog/{$post->id}/release-lock");
        $this->actingAsAdmin($bob)->get("/admin/blog/{$post->id}/edit")->assertDontSee('is currently editing this post');
    }

    public function test_stale_lock_expires(): void
    {
        $alice = $this->makeUser(['author' => true]);
        $bob   = $this->makeUser(['author' => true]);
        $post  = BlogPost::create(['title' => 'Stale', 'slug' => 'stale-' . uniqid()]);
        DB::table('blog_posts')->where('id', $post->id)->update(['locked_by' => $alice->uid, 'locked_at' => now()->subMinutes(10)]);

        $this->actingAsAdmin($bob)->put("/admin/blog/{$post->id}", ['title' => 'Bob saved', 'status' => 'draft'])
            ->assertSessionMissing('lock_error');
        $this->assertSame('Bob saved', $post->fresh()->title);
    }

    public function test_changing_the_slug_keeps_a_redirect_from_the_old_one(): void
    {
        $user = $this->makeUser(['author' => true]);
        $this->actingAsAdmin($user)->post('/admin/blog', $this->publishable(['title' => 'Old title']));
        $post = BlogPost::latest('id')->firstOrFail();
        $old  = $post->slug;

        $this->actingAsAdmin($user)->put("/admin/blog/{$post->id}", $this->publishable(['slug' => 'New Title!']))
            ->assertSessionHasNoErrors();

        $this->assertSame('new-title', $post->fresh()->slug);
        $this->assertSame($post->id, BlogSlugRedirect::where('old_slug', $old)->value('blog_post_id'));

        // Another post can't take the retired slug while it redirects.
        $this->actingAsAdmin($user)->post('/admin/blog', ['title' => 'Old title', 'status' => 'draft']);
        $this->assertNotSame($old, BlogPost::latest('id')->value('slug'));
    }

    public function test_list_publish_button_refuses_incomplete_drafts(): void
    {
        $user = $this->makeUser(['author' => true]);
        $post = BlogPost::create(['title' => 'Empty', 'slug' => 'empty-' . uniqid()]);

        $this->actingAsAdmin($user)->postJson("/admin/blog/{$post->id}/publish-toggle")
            ->assertStatus(422)->assertJsonPath('success', false);
        $this->assertFalse($post->fresh()->isPublished());
    }

    public function test_trash_restore_and_force_delete(): void
    {
        $user = $this->makeUser(['reviewer' => true]);
        $post = BlogPost::create(['title' => 'Bin me', 'slug' => 'bin-' . uniqid()]);

        $this->actingAsAdmin($user)->deleteJson("/admin/blog/{$post->id}")->assertOk();
        $this->assertTrue(BlogPost::withTrashed()->find($post->id)->trashed());

        $this->actingAsAdmin($user)->postJson("/admin/blog/{$post->id}/restore")->assertOk();
        $this->assertFalse($post->fresh()->trashed());

        $this->actingAsAdmin($user)->deleteJson("/admin/blog/{$post->id}");
        $this->actingAsAdmin($user)->deleteJson("/admin/blog/{$post->id}/force")->assertOk();
        $this->assertNull(BlogPost::withTrashed()->find($post->id));
    }

    public function test_category_with_posts_cannot_be_deleted(): void
    {
        $user = $this->makeUser(['author' => true]);
        $cat  = BlogCategory::create(['name' => 'Temp ' . uniqid(), 'slug' => 'temp-' . uniqid()]);
        BlogPost::create(['title' => 'In cat', 'slug' => 'in-cat-' . uniqid(), 'category_id' => $cat->id]);

        $this->actingAsAdmin($user)->delete("/admin/blog/categories/{$cat->id}")->assertSessionHas('error');
        $this->assertNotNull(BlogCategory::find($cat->id));
    }

    public function test_author_profile_only_accepts_safe_links(): void
    {
        $user = $this->makeUser(['author' => true]);

        $this->actingAsAdmin($user)->post('/admin/blog/profile', [
            'author_linkedin' => 'javascript:alert(1)',
            'author_website'  => 'data:text/html,hi',
        ])->assertSessionHasErrors(['author_linkedin', 'author_website']);

        $this->actingAsAdmin($user)->post('/admin/blog/profile', [
            'author_designation' => '<b>Analyst</b>',
            'author_linkedin'    => 'https://linkedin.com/in/x',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Analyst', $user->fresh()->author_designation);
    }
}
