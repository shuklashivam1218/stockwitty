<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * clean() (mews/purifier) is what makes admin-authored TinyMCE HTML safe to
 * render raw on public pages. These pin the parts we rely on.
 */
class PurifierTest extends TestCase
{
    public function test_script_and_event_handlers_are_stripped(): void
    {
        $out = clean('<p>Hi</p><script>alert(1)</script><img src="x.png" onerror="alert(1)" alt="a">');

        $this->assertStringNotContainsString('<script', $out);
        $this->assertStringNotContainsString('onerror', $out);
        $this->assertStringContainsString('<img src="x.png" alt="a"', $out);
    }

    public function test_javascript_links_lose_their_href(): void
    {
        $this->assertStringNotContainsString('javascript:', clean('<a href="javascript:alert(1)">x</a>'));
    }

    public function test_normal_article_markup_survives(): void
    {
        $out = clean('<h2>Title</h2><p><strong>Bold</strong> <a href="https://www.sebi.gov.in/" target="_blank">SEBI</a></p><ul><li>One</li></ul><table><tr><td>1</td></tr></table>');

        foreach (['<h2>Title</h2>', '<strong>Bold</strong>', 'href="https://www.sebi.gov.in/"', '<li>One</li>', '<td>1</td>'] as $kept) {
            $this->assertStringContainsString($kept, $out);
        }
    }
}
