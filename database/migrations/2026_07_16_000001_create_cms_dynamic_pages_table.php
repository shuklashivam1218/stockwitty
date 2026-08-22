<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_dynamic_pages', function (Blueprint $table) {
            $table->integer('CMS_PAGE_ID')->autoIncrement()->primary();
            $table->string('CMS_PAGE_SLUG')->unique();
            $table->string('CMS_PAGE_TITLE')->nullable();
            $table->text('CMS_PAGE_DESCRIPTION')->nullable();
            $table->longText('CMS_PAGE_CONTENT')->nullable();
            $table->string('CMS_PAGE_ACTIVE', 1)->default('1');
            $table->timestamp('CMS_PAGE_INSERT_TIME')->nullable();
            $table->timestamp('CMS_PAGE_UPDATE_TIME')->nullable();
        });

        DB::table('cms_dynamic_pages')->insert([
            'CMS_PAGE_SLUG'        => 'disclaimer',
            'CMS_PAGE_TITLE'       => 'Disclaimer',
            'CMS_PAGE_DESCRIPTION' => 'Investments carry risk. This disclaimer explains the limits of our information and tools — please read it before making any decision.',
            'CMS_PAGE_CONTENT'     => $this->disclaimerSeedContent(),
            'CMS_PAGE_ACTIVE'      => '1',
            'CMS_PAGE_INSERT_TIME' => now(),
            'CMS_PAGE_UPDATE_TIME' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_dynamic_pages');
    }

    private function disclaimerSeedContent(): string
    {
        return <<<'HTML'
<h2 id="general">General Disclaimer</h2>
<p>The information, tools, calculators, research, and content provided on StocksWitty ("Platform") are for <strong>general informational and educational purposes only</strong>. While we strive for accuracy, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, or suitability of the information for any purpose.</p>

<h2 id="noadvice">Not Investment, Legal, or Tax Advice</h2>
<p>Nothing on the Platform constitutes investment advice, financial advice, legal advice, tax advice, or a recommendation or solicitation to buy, sell, or hold any security or financial product. StocksWitty is <strong>not</strong> a SEBI-registered Investment Adviser or Portfolio Manager.</p>
<p>You should not act on any information on the Platform without obtaining specific, professional advice tailored to your circumstances from a SEBI-registered investment adviser, chartered accountant, or other qualified professional. <strong>All investment decisions are made solely at your own discretion and risk.</strong></p>

<h2 id="marketrisk">Market Risk</h2>
<p>All investments carry risk. The price and value of investments and the income from them can fluctuate due to market conditions, economic factors, regulatory changes, and company-specific developments. You may lose part or all of your invested capital. There is no assurance that any investment objective will be achieved.</p>

<h2 id="unlisted">Specific Risks of Unlisted Shares</h2>
<p>Unlisted/pre-IPO shares carry additional and elevated risks that you must understand:</p>
<ul>
<li><strong>Liquidity risk:</strong> Unlisted shares cannot be sold instantly. Exiting may take days or weeks and may not always be possible at your desired price.</li>
<li><strong>Valuation &amp; price-discovery risk:</strong> There is no continuous exchange-based price. Prices are indicative, can vary between dealers, and carry wider buy-sell spreads.</li>
<li><strong>IPO uncertainty:</strong> A company "expected to IPO" may delay indefinitely, withdraw its DRHP, or never list. Listing is not guaranteed.</li>
<li><strong>Listing-price risk:</strong> A company may list <em>below</em> its last unlisted price, resulting in losses.</li>
<li><strong>Lock-in:</strong> Pre-IPO shares may be subject to a lock-in period after listing during which you cannot sell.</li>
<li><strong>Down-round &amp; dilution risk:</strong> Subsequent funding rounds may occur at lower valuations, reducing the value of your holding.</li>
<li><strong>Information asymmetry:</strong> Unlisted companies disclose less than listed ones; data may be limited or dated.</li>
</ul>

<h2 id="mf">Mutual Funds &amp; PMS</h2>
<p><strong>Mutual fund investments are subject to market risks. Read all scheme-related documents carefully.</strong> NAVs fluctuate with market movements. PMS is a market-linked product with a high minimum investment (₹50 lakh as mandated by SEBI) and is intended for sophisticated/HNI investors. Past performance of any fund or strategy does not guarantee future results. We act only as a distributor; we do not manage funds or portfolios.</p>

<h2 id="performance">Past Performance</h2>
<p>Any past performance figures, historical returns, CAGR, or projections shown on the Platform are for illustration only. <strong>Past performance is not indicative of future results.</strong> Calculator outputs are estimates based on assumptions you provide and do not represent guaranteed or promised returns.</p>

<h2 id="accuracy">Accuracy &amp; Timeliness of Information</h2>
<p>Prices, returns, financials, AUM, ratings, and other data may be sourced from third parties, may be delayed, and may contain errors or become outdated. We do not warrant that such information is accurate, complete, or current. You should independently verify any information before relying on it.</p>

<h2 id="ai">AI Tools, WittyScore &amp; Curated Picks</h2>
<p>Our AI-powered search, "WittyScore", AI insights, curated picks, and rankings are <strong>proprietary opinions and informational aids</strong> generated from publicly available data and our methodology. They are inherently subjective, may be wrong, and must not be treated as investment advice or a guarantee of outcomes. Use them as one input among many, not as a decision-maker.</p>

<h2 id="thirdparty">Third-Party Content</h2>
<p>The Platform may display content, data, or opinions from third parties. Such content does not represent the views of StocksWitty, and we are not responsible for its accuracy or reliability.</p>

<h2 id="external">External Links</h2>
<p>The Platform may contain links to external websites operated by third parties (e.g., SEBI, AMFI, AMCs, depositories). We provide these links for convenience only and are not responsible for the content, accuracy, or practices of those websites.</p>

<h2 id="contact">Contact</h2>
<div class="contact-block">
<h4>Questions about this Disclaimer?</h4>
<div class="contact-row"><strong>Email</strong><span>support@stockswitty.com</span></div>
<div class="contact-row"><strong>Phone / WhatsApp</strong><span>+91 [Insert number]</span></div>
</div>
HTML;
    }
};
