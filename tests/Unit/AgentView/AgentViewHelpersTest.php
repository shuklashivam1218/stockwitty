<?php

namespace Tests\Unit\AgentView;

use App\Support\AgentView\AgentRequest;
use App\Support\AgentView\AgentTables;
use App\Support\AgentView\CalculatorFormulas;
use App\Support\AgentView\PageToMarkdown;
use Tests\TestCase;

class AgentViewHelpersTest extends TestCase
{
    public function test_page_and_twin_paths_map_both_ways(): void
    {
        $pairs = [
            '/'                              => '/index.md',
            '/blog/'                         => '/blog.md',
            '/blog/tax-on-unlisted-shares/'  => '/blog/tax-on-unlisted-shares.md',
            '/unlisted-shares/nse/about/'    => '/unlisted-shares/nse/about.md',
        ];

        foreach ($pairs as $html => $markdown) {
            $this->assertSame($markdown, AgentRequest::markdownPathFor($html));
            $this->assertSame($html, AgentRequest::htmlPathFor($markdown));
        }
    }

    public function test_indian_number_grouping_matches_en_in_locale(): void
    {
        $this->assertSame('999', AgentTables::indianNumber(999));
        $this->assertSame('1,00,000', AgentTables::indianNumber(100000));
        $this->assertSame('1,23,45,678', AgentTables::indianNumber(12345678));
        $this->assertSame('12,34,567.80', AgentTables::indianNumber(1234567.8, 2));
        $this->assertSame('₹12,00,000', AgentTables::inr(1200000));
    }

    public function test_min_investment_uses_the_same_lakh_threshold_as_the_page(): void
    {
        $this->assertSame('₹32,200', AgentTables::minInvestment(322, 100));
        $this->assertSame('₹2.07L', AgentTables::minInvestment(2065, 100));
        $this->assertSame('—', AgentTables::minInvestment(0, 100));
    }

    public function test_calculator_formulas_match_the_javascript_calculators(): void
    {
        // ₹10,000/month at 12% for 10 years (sip-calculator.js defaults).
        $sip = CalculatorFormulas::sip(10000, 12, 10);
        $this->assertSame(1200000.0, $sip['invested']);
        $this->assertSame(2323391.0, round($sip['total']));

        // ₹1,00,000 for 5 years at 9.1%, quarterly (Suryoday calculator).
        $this->assertSame(156816.0, CalculatorFormulas::fdQuarterly(100000, 9.1, 5)['maturity']);
    }

    public function test_cleaner_drops_widgets_and_hidden_ui_but_keeps_marked_content(): void
    {
        $html = <<<'HTML'
            <html><head><title>T</title><link rel="canonical" href="https://example.com/p/"></head><body>
            <nav aria-label="Breadcrumb"><ol><li>Home</li><li>Page</li></ol></nav>
            <h1>Heading &amp; more</h1>
            <form><input name="q"><button>Go</button></form>
            <div data-agent-skip>Lead form</div>
            <p x-show="done" style="display: none;">Request received</p>
            <table data-agent-keep style="display: none;"><tr><th>Year</th></tr><tr><td>FY25</td></tr></table>
            <details><summary>Is it legal?</summary><p>Yes.</p></details>
            <dl><dt>Lot size</dt><dd>100</dd></dl>
            <a href="/blog/x/"><h3>Card title</h3><p>Card text</p></a>
            <a href="#faq">Jump</a>
            </body></html>
            HTML;

        $markdown = (new PageToMarkdown())->convert($html, 'https://example.com');

        $this->assertStringContainsString('breadcrumb: "Home › Page"', $markdown);
        $this->assertStringContainsString('# Heading & more', $markdown);
        $this->assertStringNotContainsString('Lead form', $markdown);
        $this->assertStringNotContainsString('Request received', $markdown);
        $this->assertStringContainsString('| FY25 |', $markdown);
        $this->assertStringContainsString('### Is it legal?', $markdown);
        $this->assertStringContainsString('- **Lot size:** 100', $markdown);
        $this->assertStringContainsString('### [Card title](https://example.com/blog/x/)', $markdown);
        $this->assertStringContainsString('Jump', $markdown);
        $this->assertStringNotContainsString('#faq', $markdown);
        $this->assertStringNotContainsString('<', $markdown);
    }
}
