<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * The Markdown twins served by App\Http\Middleware\ServeAgentMarkdown.
 * Uses static (non-database) pages so it runs without a seeded DB.
 */
class AgentMarkdownTest extends TestCase
{
    private const STATIC_PAGES = [
        '/blog/tax-on-unlisted-shares/',
        '/wittyscore/',
        '/calculators/',
        '/fixed-deposits/suryoday/',
        '/case-studies/nse-pre-ipo-journey/',
        '/contact/',
    ];

    public function test_every_static_page_has_a_markdown_twin(): void
    {
        foreach (self::STATIC_PAGES as $path) {
            $twin = rtrim($path, '/') . '.md';
            $response = $this->get($twin);

            $response->assertOk();
            $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
            $this->assertStringContainsString('rel="canonical"', $response->headers->get('Link'), $twin);

            $markdown = $response->getContent();
            $this->assertStringStartsWith("---\ntitle: ", $markdown, $twin);
            $this->assertMatchesRegularExpression('/^# /m', $markdown, "{$twin} has an H1");
            $this->assertStringNotContainsString('<script', $markdown, $twin);
            $this->assertStringNotContainsString('navBar', $markdown, "{$twin} has no site chrome");
        }
    }

    public function test_agent_only_content_reaches_the_twin_but_not_the_human_page(): void
    {
        $this->get('/calculators.md')->assertSee('SIP calculator — worked example', false);
        $this->get('/calculators/')->assertDontSee('SIP calculator — worked example', false);
    }

    public function test_accept_header_negotiates_markdown_on_the_page_url(): void
    {
        $this->get('/wittyscore/', ['Accept' => 'text/markdown'])
            ->assertOk()
            ->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');

        $this->get('/wittyscore/', ['Accept' => 'text/html,application/xhtml+xml,*/*;q=0.8'])
            ->assertOk()
            ->assertHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function test_html_pages_advertise_their_twin(): void
    {
        $response = $this->get('/wittyscore/');

        $this->assertStringContainsString('/wittyscore.md>; rel="alternate"; type="text/markdown"', $response->headers->get('Link'));
        $this->assertStringContainsString('Accept', $response->headers->get('Vary'));
        $response->assertSee('type="text/markdown"', false);
        $response->assertSee("agentView(", false);
    }

    public function test_private_and_unknown_routes_have_no_twin(): void
    {
        foreach (['/login.md', '/signup.md', '/admin/dashboard.md', '/no-such-page.md'] as $path) {
            $this->get($path)->assertNotFound();
        }

        // Login keeps its normal HTML even when Markdown is requested.
        $this->get('/login', ['Accept' => 'text/markdown'])
            ->assertHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function test_content_never_depends_on_the_user_agent(): void
    {
        $bot     = $this->get('/wittyscore.md', ['User-Agent' => 'GPTBot/1.2'])->getContent();
        $browser = $this->get('/wittyscore.md', ['User-Agent' => 'Mozilla/5.0'])->getContent();

        $this->assertSame($bot, $browser);
    }
}
