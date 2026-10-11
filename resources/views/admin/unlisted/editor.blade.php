@extends('layout.admin')

@section('title', ($stock->exists ? 'Edit ' . $stock->UL_STOCKS_COMPNAME : 'New Stock') . ' | Unlisted Stocks | Admin | StocksWitty')

@php
    use App\Http\Controllers\UnlistedStockEditorController as Editor;

    // Same page creates a stock; prices and financials need it to exist first.
    $isNew = !$stock->exists;
    // Built here, not inside @json() below: a multi-line array in a Blade
    // directive's parentheses compiles to broken PHP.
    $uploadUrls = $isNew ? null : [
        'thesis' => route('admin.unlisted.stocks.thesis.upload', $stock->UL_STOCKS_FINCODE),
        'about'  => route('admin.unlisted.stocks.about.upload', $stock->UL_STOCKS_FINCODE),
    ];

    // Value for a field: the agent's last submit if validation bounced it, else what's saved.
    $v = fn (string $field, $row = null) => old($field, $row ? $row->$field : $stock->$field);
    // Section switches default to on for sections that have never been saved.
    $isOn = fn (string $prefix, $row) => (bool) old("{$prefix}_ACTIVE", $row ? $row->{"{$prefix}_ACTIVE"} === '1' : true);

    $ws    = $sections['witty_score'];
    $ci    = $sections['insights'];
    $th    = $sections['thesis'];
    $abx   = $sections['about_extra'];
    $about = $sections['about'];
    $seo   = $sections['seo'];

    $nav = [
        'basics'      => ['Basics', true],
        'profile'     => ['Company Profile', $filled['profile']],
        'prices'      => ['Price', $filled['prices']],
        'financials'  => ['Financials', $filled['financials']],
        'witty-score' => ['WittyScore', $filled['witty_score']],
        'insights'    => ['Insights', $filled['insights']],
        'thesis'      => ['Thesis', $filled['thesis']],
        'about-page'  => ['About Page', $filled['about_extra']],
        'faqs'        => ['FAQs', $filled['faqs']],
        'seo'         => ['SEO', $filled['seo']],
        'legacy-about'=> ['Old About', $filled['about']],
    ];

    $yesNo   = ['Yes' => 'Yes', 'No' => 'No'];
    $ratings = [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'];

    $pillars = [
        'UL_WS_FINANCIAL_HEALTH' => 'Financial Health',
        'UL_WS_VALUATION'        => 'Valuation',
        'UL_WS_GROWTH_POTENTIAL' => 'Growth Potential',
        'UL_WS_IPO_PROBABILITY'  => 'IPO Probability',
        'UL_WS_LIQUIDITY_SAFETY' => 'Liquidity & Safety',
    ];

    $aboutProse = [
        'UL_ABX_OVERVIEW'             => 'Overview of the company',
        'UL_ABX_OPERATIONS'           => 'What it does & how it operates',
        'UL_ABX_GEOGRAPHY'            => 'Geographical presence',
        'UL_ABX_INDUSTRY_POSITION'    => 'Industry position',
        'UL_ABX_SHAREHOLDING'         => 'Shareholding framework',
        'UL_ABX_INVESTOR_INTEREST'    => 'Why it draws investor interest',
        'UL_ABX_MARKET_LANDSCAPE'     => 'Market landscape & reach',
        'UL_ABX_COMPETITIVE_STRENGTH' => 'Competitive strength',
    ];
    $aboutLists = [
        'UL_ABX_VERTICALS'         => ['Business verticals', 'One per line — Title | Description'],
        'UL_ABX_REVENUE_SEGMENTS'  => ['Revenue segments', 'One per line — Segment | Description'],
        'UL_ABX_HISTORY'           => ['History & evolution', 'One per line — Year | Milestone'],
        'UL_ABX_PRODUCTS_SERVICES' => ['Products & services', 'One per line — Name | Description'],
        'UL_ABX_SOURCES'           => ['Sources & references', 'One per line — Label | https://…'],
    ];
    $swot = [
        'UL_ABX_SWOT_STRENGTHS'     => 'Strengths',
        'UL_ABX_SWOT_WEAKNESSES'    => 'Weaknesses',
        'UL_ABX_SWOT_OPPORTUNITIES' => 'Opportunities',
        'UL_ABX_SWOT_THREATS'       => 'Threats',
    ];
    $slugShown = $stock->UL_STOCKS_SLUG ?? 'company-slug';
    $seoPages = [
        'COMPANY' => ['Company page', '/unlisted-shares/' . $slugShown . '/'],
        'ABOUT'   => ['About page', '/unlisted-shares/' . $slugShown . '/about/'],
        'THESIS'  => ['Thesis page', '/unlisted-shares/' . $slugShown . '/thesis/'],
    ];
@endphp

@section('content')
<div class="admin-main">

    <div class="se-head">
        <h1 class="admin-page-title">{{ $isNew ? 'New Stock' : $stock->UL_STOCKS_COMPNAME }}</h1>
        <div class="se-head-links">
            <a href="{{ route('admin.unlisted') }}" class="cms-back-link"><i class="fa-solid fa-arrow-left"></i> All stocks</a>
            @unless($isNew)
                <a href="{{ route('sw.unlisted-shares.company', $stock->UL_STOCKS_SLUG) }}" target="_blank" rel="noopener" class="cms-back-link">
                    View live page <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            @endunless
        </div>
    </div>

    @if(session('success'))
        <div class="cms-flash-success" style="margin-top:14px;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="cms-lock-banner cms-lock-banner-error" style="margin-top:14px;">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>Nothing was saved — please fix the highlighted fields below. ({{ $errors->first() }})</span>
        </div>
    @endif

    <form id="stockForm" method="POST" enctype="multipart/form-data"
          action="{{ $isNew ? route('admin.unlisted.stocks.store') : route('admin.unlisted.stocks.update', $stock->UL_STOCKS_FINCODE) }}">
        @csrf
        @unless($isNew) @method('PUT') @endunless

        <div class="cms-form-grid">

            <div class="se-main">

                <nav class="se-jump" id="sectionNav" aria-label="Sections">
                    @foreach($nav as $id => [$label, $isFilled])
                        <a href="#{{ $id }}"><span class="se-dot {{ $isFilled ? 'on' : '' }}"></span>{{ $label }}</a>
                    @endforeach
                </nav>

                {{-- ── Basics (unlisted_stocks) ─────────────────────────── --}}
                <section class="admin-card se-section" id="basics">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-building"></i> Basics</h2>
                            <p class="se-section-sub">Name, URL and the facts shown in the company page header. The stock's own Active switch is in the right-hand panel.</p>
                        </div>
                    </div>

                    <div class="se-grid se-grid-2">
                        <div class="cms-field">
                            <label>Company name <span class="req">*</span></label>
                            <input type="text" name="UL_STOCKS_COMPNAME" id="nameInput" value="{{ $v('UL_STOCKS_COMPNAME') }}" maxlength="255" required @if($isNew) autofocus @endif
                                   class="cms-input @error('UL_STOCKS_COMPNAME') is-invalid @enderror">
                            @error('UL_STOCKS_COMPNAME') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                        @if($isNew)
                        {{-- New stock: the slug follows the name until it's typed in by hand. --}}
                        <div class="cms-field">
                            <label>URL slug</label>
                            <input type="text" name="UL_STOCKS_SLUG" id="newSlugInput" value="{{ old('UL_STOCKS_SLUG') }}" maxlength="255" autocomplete="off"
                                   class="cms-input @error('UL_STOCKS_SLUG') is-invalid @enderror" placeholder="Filled in from the name — or type your own">
                            <p class="cms-field-hint">/unlisted-shares/<strong id="newSlugPreview">{{ old('UL_STOCKS_SLUG') ? \Illuminate\Support\Str::slug(old('UL_STOCKS_SLUG')) : 'company-name' }}</strong>/</p>
                            <p class="cms-field-hint">Pick it carefully — once the page is live, the URL should not change.</p>
                            @error('UL_STOCKS_SLUG') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                        @else
                        <div class="cms-field">
                            <label>URL slug</label>
                            <div class="cms-slug-row">
                                <input type="text" name="UL_STOCKS_SLUG" id="slugInput" value="{{ old('UL_STOCKS_SLUG', $stock->UL_STOCKS_SLUG) }}"
                                       data-original="{{ $stock->UL_STOCKS_SLUG }}" maxlength="255"
                                       @unless($errors->has('UL_STOCKS_SLUG')) readonly @endunless
                                       class="cms-input @unless($errors->has('UL_STOCKS_SLUG')) cms-input-locked @endunless @error('UL_STOCKS_SLUG') is-invalid @enderror">
                                <button type="button" id="slugEditToggle" class="cms-slug-toggle-btn">{{ $errors->has('UL_STOCKS_SLUG') ? 'Cancel' : 'Edit' }}</button>
                            </div>
                            <p class="cms-field-hint">/unlisted-shares/<strong id="slugPreview">{{ old('UL_STOCKS_SLUG', $stock->UL_STOCKS_SLUG) }}</strong>/</p>
                            <p class="cms-field-hint cms-field-warning" id="slugWarning" @unless($errors->has('UL_STOCKS_SLUG')) style="display:none;" @endunless>
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                Changing this changes the page's URL — old links and Google results will stop working.
                            </p>
                            @error('UL_STOCKS_SLUG') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                        @endif
                    </div>

                    <div class="se-grid se-grid-4">
                        <div class="cms-field">
                            <label>Industry</label>
                            <select name="UL_STOCKS_IND_CODE" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach($industries as $ind)
                                    <option value="{{ $ind->IM_IND_CODE }}" @selected((string) $v('UL_STOCKS_IND_CODE') === (string) $ind->IM_IND_CODE)>{{ $ind->IM_INDUSTRY }}</option>
                                @endforeach
                            </select>
                            @error('UL_STOCKS_IND_CODE') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="cms-field">
                            <label>Category</label>
                            <select name="UL_STOCKS_CATEGORY" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach(Editor::CATEGORIES as $value => $label)
                                    <option value="{{ $value }}" @selected($v('UL_STOCKS_CATEGORY') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>Tag</label>
                            <select name="UL_STOCKS_TAG" class="cms-input">
                                <option value="">— None —</option>
                                @foreach(Editor::TAGS as $tag)
                                    <option value="{{ $tag }}" @selected($v('UL_STOCKS_TAG') === $tag)>{{ $tag }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>DRHP filed</label>
                            <select name="UL_STOCKS_DRHP_FLAG" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach($yesNo as $value => $label)
                                    <option value="{{ $value }}" @selected($v('UL_STOCKS_DRHP_FLAG') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="cms-field">
                            <label>ISIN</label>
                            <input type="text" name="UL_STOCKS_ISIN" value="{{ $v('UL_STOCKS_ISIN') }}" maxlength="20" class="cms-input @error('UL_STOCKS_ISIN') is-invalid @enderror">
                            @error('UL_STOCKS_ISIN') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="cms-field">
                            <label>Short name</label>
                            <input type="text" name="UL_STOCKS_S_NAME" value="{{ $v('UL_STOCKS_S_NAME') }}" maxlength="100" class="cms-input">
                            @error('UL_STOCKS_S_NAME') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="cms-field">
                            <label>Website</label>
                            <input type="text" name="UL_STOCKS_WEBSITE" value="{{ $v('UL_STOCKS_WEBSITE') }}" maxlength="150" class="cms-input" placeholder="www.example.com">
                            @error('UL_STOCKS_WEBSITE') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="cms-field">
                            <label>Lot size</label>
                            <input type="text" name="UL_STOCKS_LOT_SIZE" value="{{ $v('UL_STOCKS_LOT_SIZE') }}" maxlength="5" class="cms-input">
                            @error('UL_STOCKS_LOT_SIZE') <div class="cms-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="cms-field">
                            <label>Incorporated (month)</label>
                            <select name="UL_STOCKS_INC_MONTH" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach(Editor::MONTHS as $month)
                                    <option value="{{ $month }}" @selected($v('UL_STOCKS_INC_MONTH') === $month)>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>Incorporated (year)</label>
                            <select name="UL_STOCKS_INC_YEAR" class="cms-input">
                                <option value="">— Select —</option>
                                @for($y = (int) date('Y'); $y >= 1900; $y--)
                                    <option value="{{ $y }}" @selected((string) $v('UL_STOCKS_INC_YEAR') === (string) $y)>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>Company rating</label>
                            <select name="UL_STOCKS_COMP_RATING" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach($ratings as $value => $label)
                                    <option value="{{ $value }}" @selected((string) $v('UL_STOCKS_COMP_RATING') === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>Valuation rating</label>
                            <select name="UL_STOCKS_VALUATION_RATING" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach($ratings as $value => $label)
                                    <option value="{{ $value }}" @selected((string) $v('UL_STOCKS_VALUATION_RATING') === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="cms-field">
                            <label>Buy / sell enabled</label>
                            <select name="UL_STOCKS_BUY_SELL_FLAG" class="cms-input">
                                @foreach($yesNo as $value => $label)
                                    <option value="{{ $value }}" @selected(($v('UL_STOCKS_BUY_SELL_FLAG') ?? 'Yes') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>ROFR</label>
                            <select name="UL_STOCKS_ROFR_FLAG" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach($yesNo as $value => $label)
                                    <option value="{{ $value }}" @selected($v('UL_STOCKS_ROFR_FLAG') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>Demat account</label>
                            <select name="UL_STOCKS_DEMAT_ACCOUNT_REQ" class="cms-input">
                                <option value="">— Select —</option>
                                @foreach(Editor::DEMAT as $value => $label)
                                    <option value="{{ $value }}" @selected($v('UL_STOCKS_DEMAT_ACCOUNT_REQ') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cms-field">
                            <label>Quarterly data published</label>
                            <select name="UL_STOCKS_Qtr_Data_Publish" class="cms-input">
                                @foreach($yesNo as $value => $label)
                                    <option value="{{ $value }}" @selected(($v('UL_STOCKS_Qtr_Data_Publish') ?? 'Yes') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                {{-- ── Company profile (unlisted_stocks.UL_STOCKS_ABOUT) ──── --}}
                <section class="admin-card se-section" id="profile">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-align-left"></i> Company Profile</h2>
                            <p class="se-section-sub">The "About the company" block on the company page. Follows the stock's own Active switch.</p>
                        </div>
                    </div>
                    <textarea name="UL_STOCKS_ABOUT" id="profileEditor" class="se-rich" data-upload="about">{{ $v('UL_STOCKS_ABOUT') }}</textarea>
                </section>

                @if($isNew)
                    @foreach(['prices' => ['fa-indian-rupee-sign', 'Price History'], 'financials' => ['fa-chart-column', 'Financials']] as $id => [$icon, $title])
                        <section class="admin-card se-section se-locked" id="{{ $id }}">
                            <h2 class="se-section-title"><i class="fa-solid {{ $icon }}"></i> {{ $title }}</h2>
                            <p class="se-section-sub"><i class="fa-solid fa-lock"></i> Create the stock first — this unlocks as soon as you save.</p>
                        </section>
                    @endforeach
                @else
                    @include('admin.unlisted.partials.editor-prices')
                    @include('admin.unlisted.partials.editor-financials')
                @endif

                {{-- ── WittyScore (unlisted_witty_scores) ───────────────── --}}
                <section class="admin-card se-section" id="witty-score">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-gauge-high"></i> WittyScore</h2>
                            <p class="se-section-sub">Each pillar out of 10. Shown on the company and thesis pages.</p>
                        </div>
                        @include('admin.unlisted.partials.editor-switch', ['name' => 'UL_WS_ACTIVE', 'checked' => $isOn('UL_WS', $ws)])
                    </div>
                    <div class="se-ws-overall">
                        <span>Overall WittyScore</span>
                        <strong><span id="wsOverall">—</span>/10</strong>
                    </div>
                    <div class="se-grid se-grid-3">
                        @foreach($pillars as $field => $label)
                            <div class="cms-field">
                                <label>{{ $label }} <span class="se-hint">{{ (int) round(\App\Models\UnlistedWittyScore::WEIGHTS[$field] * 100) }}% weight</span></label>
                                <input type="number" step="0.1" min="0" max="10" name="{{ $field }}" value="{{ $v($field, $ws) }}"
                                       data-weight="{{ \App\Models\UnlistedWittyScore::WEIGHTS[$field] }}"
                                       class="cms-input se-ws-pillar @error($field) is-invalid @enderror" placeholder="0.0 – 10.0">
                                @error($field) <div class="cms-error">{{ $message }}</div> @enderror
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- ── Insights (unlisted_company_insights) ─────────────── --}}
                <section class="admin-card se-section" id="insights">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-lightbulb"></i> Insights</h2>
                            <p class="se-section-sub">AI summary, Founder's Take and IPO roadmap (company page), plus the thesis-page summary blocks. Leave a box blank to hide it.</p>
                        </div>
                        @include('admin.unlisted.partials.editor-switch', ['name' => 'UL_CI_ACTIVE', 'checked' => $isOn('UL_CI', $ci)])
                    </div>

                    <div class="se-sub-title">Company page</div>
                    <div class="cms-field">
                        <label>AI summary <span class="se-hint">30-second summary paragraph</span></label>
                        <textarea name="UL_CI_AI_SUMMARY" rows="4" class="cms-input">{{ $v('UL_CI_AI_SUMMARY', $ci) }}</textarea>
                    </div>
                    <div class="cms-field">
                        <label>Founder's Take — intro line</label>
                        <input type="text" name="UL_CI_FOUNDERS_INTRO" value="{{ $v('UL_CI_FOUNDERS_INTRO', $ci) }}" maxlength="500" class="cms-input @error('UL_CI_FOUNDERS_INTRO') is-invalid @enderror">
                        @error('UL_CI_FOUNDERS_INTRO') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="se-grid se-grid-2">
                        <div class="cms-field">
                            <label>Founder's Take — pull quote</label>
                            <textarea name="UL_CI_FOUNDERS_QUOTE" rows="4" class="cms-input">{{ $v('UL_CI_FOUNDERS_QUOTE', $ci) }}</textarea>
                        </div>
                        <div class="cms-field">
                            <label>Founder's Take — our verdict</label>
                            <textarea name="UL_CI_FOUNDERS_VERDICT" rows="4" class="cms-input">{{ $v('UL_CI_FOUNDERS_VERDICT', $ci) }}</textarea>
                        </div>
                        <div class="cms-field">
                            <label>IPO timeline <span class="se-hint">One per line — Date | Milestone</span></label>
                            <textarea name="UL_CI_IPO_TIMELINE" rows="5" class="cms-input se-mono">{{ $v('UL_CI_IPO_TIMELINE', $ci) }}</textarea>
                        </div>
                        <div class="cms-field">
                            <label>IPO facts <span class="se-hint">One per line — Label | Value</span></label>
                            <textarea name="UL_CI_IPO_FACTS" rows="5" class="cms-input se-mono">{{ $v('UL_CI_IPO_FACTS', $ci) }}</textarea>
                        </div>
                    </div>

                    <div class="se-sub-title">Thesis page</div>
                    <div class="cms-field">
                        <label>TL;DR summary</label>
                        <textarea name="UL_CI_TLDR" rows="3" class="cms-input">{{ $v('UL_CI_TLDR', $ci) }}</textarea>
                    </div>
                    <div class="se-grid se-grid-2">
                        <div class="cms-field">
                            <label>Bull case <span class="se-hint">one point per line</span></label>
                            <textarea name="UL_CI_BULL_CASE" rows="5" class="cms-input se-mono">{{ $v('UL_CI_BULL_CASE', $ci) }}</textarea>
                        </div>
                        <div class="cms-field">
                            <label>Bear case <span class="se-hint">one point per line</span></label>
                            <textarea name="UL_CI_BEAR_CASE" rows="5" class="cms-input se-mono">{{ $v('UL_CI_BEAR_CASE', $ci) }}</textarea>
                        </div>
                        <div class="cms-field">
                            <label>Might suit you if… <span class="se-hint">one point per line</span></label>
                            <textarea name="UL_CI_SUITS_IF" rows="4" class="cms-input se-mono">{{ $v('UL_CI_SUITS_IF', $ci) }}</textarea>
                        </div>
                        <div class="cms-field">
                            <label>Probably not if… <span class="se-hint">one point per line</span></label>
                            <textarea name="UL_CI_NOT_SUITS_IF" rows="4" class="cms-input se-mono">{{ $v('UL_CI_NOT_SUITS_IF', $ci) }}</textarea>
                        </div>
                    </div>
                    <div class="cms-field">
                        <label>Key risks <span class="se-hint">One per line — Label | Body text</span></label>
                        <textarea name="UL_CI_RISKS" rows="4" class="cms-input se-mono">{{ $v('UL_CI_RISKS', $ci) }}</textarea>
                    </div>
                    <div class="cms-field" style="margin-bottom:0;">
                        <label>Where we land <span class="se-hint">long verdict</span></label>
                        <textarea name="UL_CI_VERDICT_LONG" rows="4" class="cms-input">{{ $v('UL_CI_VERDICT_LONG', $ci) }}</textarea>
                    </div>
                </section>

                {{-- ── Thesis (unlisted_thesis) ─────────────────────────── --}}
                <section class="admin-card se-section" id="thesis">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-file-lines"></i> Investment Thesis</h2>
                            <p class="se-section-sub">Main article on /thesis/.</p>
                        </div>
                        @include('admin.unlisted.partials.editor-switch', ['name' => 'UL_THESIS_ACTIVE', 'checked' => $isOn('UL_THESIS', $th)])
                    </div>
                    <textarea name="UL_THESIS_CONTENT" id="thesisEditor" class="se-rich" data-upload="thesis">{{ $v('UL_THESIS_CONTENT', $th) }}</textarea>
                </section>

                {{-- ── About page sections (unlisted_about_extra) ───────── --}}
                <section class="admin-card se-section" id="about-page">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-circle-info"></i> About Page</h2>
                            <p class="se-section-sub">Structured content for /about/ — narrative, lists and SWOT. Leave a box blank to hide it.</p>
                        </div>
                        @include('admin.unlisted.partials.editor-switch', ['name' => 'UL_ABX_ACTIVE', 'checked' => $isOn('UL_ABX', $abx)])
                    </div>

                    <div class="se-sub-title">Narrative</div>
                    <div class="se-grid se-grid-2">
                        @foreach($aboutProse as $field => $label)
                            <div class="cms-field">
                                <label>{{ $label }}</label>
                                <textarea name="{{ $field }}" rows="4" class="cms-input">{{ $v($field, $abx) }}</textarea>
                            </div>
                        @endforeach
                    </div>

                    <div class="se-sub-title">Lists</div>
                    @foreach($aboutLists as $field => [$label, $hint])
                        <div class="cms-field">
                            <label>{{ $label }} <span class="se-hint">{{ $hint }}</span></label>
                            <textarea name="{{ $field }}" rows="4" class="cms-input se-mono">{{ $v($field, $abx) }}</textarea>
                        </div>
                    @endforeach

                    <div class="se-sub-title">SWOT <span class="se-hint">one point per line</span></div>
                    <div class="se-grid se-grid-2">
                        @foreach($swot as $field => $label)
                            <div class="cms-field">
                                <label>{{ $label }}</label>
                                <textarea name="{{ $field }}" rows="4" class="cms-input se-mono">{{ $v($field, $abx) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- ── FAQs (unlisted_faqs) ─────────────────────────────── --}}
                <section class="admin-card se-section" id="faqs">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-circle-question"></i> FAQs</h2>
                            <p class="se-section-sub">Pick the page each question shows on. Order here is the order on the site; each FAQ has its own switch. Removing a row deletes it when you save.</p>
                        </div>
                    </div>
                    <div id="faqRows" class="blog-repeater" data-empty="No FAQs yet — add the questions investors actually ask.">
                        @foreach(old('faqs', $faqs) as $i => $faq)
                            @include('admin.unlisted.partials.editor-faq-row', ['i' => $i, 'faq' => $faq, 'faqPages' => Editor::FAQ_PAGES])
                        @endforeach
                    </div>
                    <button type="button" class="blog-add-row" id="addFaqBtn"><i class="fa-solid fa-plus"></i> Add FAQ</button>
                </section>

                {{-- ── SEO (unlisted_seo_meta) ──────────────────────────── --}}
                <section class="admin-card se-section" id="seo">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-magnifying-glass"></i> SEO</h2>
                            <p class="se-section-sub">Override the meta title, description and keywords per page. Blank fields keep the auto-generated default.</p>
                        </div>
                        @include('admin.unlisted.partials.editor-switch', ['name' => 'UL_SEO_ACTIVE', 'checked' => $isOn('UL_SEO', $seo)])
                    </div>
                    @foreach($seoPages as $key => [$label, $path])
                        <div class="se-sub-title">{{ $label }} <span class="se-hint">{{ $path }}</span></div>
                        <div class="se-grid se-grid-2">
                            <div class="cms-field">
                                <label>Meta title</label>
                                <input type="text" name="UL_SEO_{{ $key }}_TITLE" value="{{ $v("UL_SEO_{$key}_TITLE", $seo) }}" maxlength="255"
                                       class="cms-input @error("UL_SEO_{$key}_TITLE") is-invalid @enderror">
                                @error("UL_SEO_{$key}_TITLE") <div class="cms-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="cms-field">
                                <label>Meta keywords <span class="se-hint">comma-separated</span></label>
                                <input type="text" name="UL_SEO_{{ $key }}_KEYWORDS" value="{{ $v("UL_SEO_{$key}_KEYWORDS", $seo) }}" maxlength="500"
                                       class="cms-input @error("UL_SEO_{$key}_KEYWORDS") is-invalid @enderror">
                                @error("UL_SEO_{$key}_KEYWORDS") <div class="cms-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="cms-field">
                            <label>Meta description <span class="se-hint">~150–160 characters is ideal</span></label>
                            <textarea name="UL_SEO_{{ $key }}_DESCRIPTION" rows="2" maxlength="500"
                                      class="cms-input @error("UL_SEO_{$key}_DESCRIPTION") is-invalid @enderror">{{ $v("UL_SEO_{$key}_DESCRIPTION", $seo) }}</textarea>
                            @error("UL_SEO_{$key}_DESCRIPTION") <div class="cms-error">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                </section>

                {{-- ── Old About (unlisted_about) — not rendered on the site today ── --}}
                <section class="admin-card se-section" id="legacy-about">
                    <div class="se-section-head">
                        <div>
                            <h2 class="se-section-title"><i class="fa-solid fa-box-archive"></i> Old About Content</h2>
                            <p class="se-section-sub">Kept from the earlier About editor. No public page shows this right now — use "About Page" above for the live /about/ page.</p>
                        </div>
                        @include('admin.unlisted.partials.editor-switch', ['name' => 'UL_ABOUT_ACTIVE', 'checked' => $isOn('UL_ABOUT', $about)])
                    </div>
                    <textarea name="UL_ABOUT_CONTENT" id="aboutEditor" class="se-rich" data-upload="about">{{ $v('UL_ABOUT_CONTENT', $about) }}</textarea>
                </section>

            </div>

            {{-- ── Sidebar ─────────────────────────────────────────────── --}}
            <div class="cms-side-col se-side">

                <div class="admin-card">
                    <div class="cms-side-title">Save</div>
                    <div class="se-status-row">
                        <span>Stock on the site</span>
                        @include('admin.unlisted.partials.editor-switch', [
                            'name' => 'UL_STOCKS_STATUS', 'checked' => (bool) old('UL_STOCKS_STATUS', $stock->UL_STOCKS_STATUS === '1'),
                            'title' => 'Inactive hides the whole stock from the public site',
                        ])
                    </div>
                    @if($isNew)
                        <button type="submit" class="cms-submit-btn" id="saveBtn"><i class="fa-solid fa-plus"></i> Create stock</button>
                    @else
                        <button type="submit" class="cms-submit-btn" id="saveBtn"><i class="fa-solid fa-floppy-disk"></i> Save all changes</button>
                    @endif
                    <p class="se-unsaved" id="unsavedNote"><i class="fa-solid fa-circle-exclamation"></i> You have unsaved changes.</p>
                    @if($isNew)
                        <p class="cms-field-hint" style="margin-top:10px;"><i class="fa-solid fa-circle-info"></i> Only the company name is required. New stocks start inactive — switch it on when the page is ready.</p>
                    @else
                        <p class="cms-field-hint" style="margin-top:10px;"><i class="fa-solid fa-circle-info"></i> One click saves every section on this page. Ctrl + S works too.</p>
                    @endif
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">Logo</div>
                    <img src="{{ $stock->UL_STOCKS_LOGO_LINK ? asset($stock->UL_STOCKS_LOGO_LINK) : '' }}" alt="" id="logoPreview" class="se-logo-preview"
                         @unless($stock->UL_STOCKS_LOGO_LINK) style="display:none;" @endunless>
                    <input type="file" name="logo" id="logoInput" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="cms-input @error('logo') is-invalid @enderror">
                    <p class="cms-field-hint">PNG, JPG, WEBP or SVG, up to 2 MB.</p>
                    @error('logo') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                @unless($isNew)
                <div class="admin-card">
                    <div class="cms-side-title">Stock Info</div>
                    <div class="cms-info-row"><span class="cms-info-label">Fincode</span><span class="cms-info-value">{{ $stock->UL_STOCKS_FINCODE }}</span></div>
                    <div class="cms-info-row"><span class="cms-info-label">Added</span><span class="cms-info-value">{{ $stock->UL_STOCKS_INSERT_TIME?->format('d M Y') ?? '—' }}</span></div>
                    <div class="cms-info-row"><span class="cms-info-label">Last updated</span><span class="cms-info-value">{{ $stock->UL_STOCKS_UPDATE_TIME?->format('d M Y, h:i A') ?? '—' }}</span></div>
                </div>
                @endunless

            </div>
        </div>
    </form>
</div>

{{-- New FAQ rows are cloned from this; __INDEX__ is replaced in JS. --}}
<template id="faqRowTemplate">@include('admin.unlisted.partials.editor-faq-row', ['i' => '__INDEX__', 'faq' => [], 'faqPages' => Editor::FAQ_PAGES])</template>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-form.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-form.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin/stock-editor.css') }}?v={{ filemtime(public_path('assets/css/admin/stock-editor.css')) }}">
@endpush
@endsection

@push('scripts')
<script src="{{ asset('js/tinymce_6.1.2/tinymce.min.js') }}"></script>
<script>
// Shared by the Price and Financials sections. Defined at load time, before
// any of the sections' ready handlers run.
window.seLiveSwitch = function (checked, on, off) {
    return $('<label class="se-switch">').append(
        $('<input type="checkbox">').prop('checked', !!checked),
        $('<span class="se-switch-track">'),
        $('<span class="se-switch-label">').append(
            $('<span class="se-on">').text(on || 'Active'),
            $('<span class="se-off">').text(off || 'Inactive')
        )
    );
};
window.sePager = function ($el, page, lastPage, total, go) {
    $el.empty();
    if (lastPage <= 1) return;
    const btn = function (label, target, disabled) {
        return $('<button type="button" class="se-page-btn">').text(label).prop('disabled', disabled)
            .on('click', function () { go(target); });
    };
    $el.append(
        btn('‹ Newer', page - 1, page <= 1),
        $('<span class="se-page-info">').text('Page ' + page + ' of ' + lastPage + ' · ' + total + ' total'),
        btn('Older ›', page + 1, page >= lastPage)
    );
};

$(function () {
    const CSRF        = $('meta[name="csrf-token"]').attr('content');
    // Upload endpoints are per stock, so images can only go in once it exists.
    const UPLOAD_URLS = @json($uploadUrls);
    const $form = $('#stockForm');

    // ── Unsaved-changes guard: one Save covers the whole page, so leaving
    // without it would quietly drop work. ──
    let dirty = false, submitting = false;
    function markDirty() {
        if (dirty) return;
        dirty = true;
        $('#unsavedNote').addClass('show');
    }
    // Price and Financials save on their own, so typing there isn't "unsaved".
    $form.on('input change', function (e) {
        if (!$(e.target).closest('[data-live]').length) markDirty();
    });
    // Enter inside those sections runs that section's button instead of
    // submitting the whole page.
    $form.on('keydown', '[data-live] input, [data-live] select', function (e) {
        if (e.key !== 'Enter' || $(this).hasClass('se-inline-input')) return;
        e.preventDefault();
        $(this).closest('[data-live]').find('[data-enter]:visible').first().trigger('click');
    });
    $form.on('submit', function () {
        submitting = true;
        tinymce.triggerSave();
        $('#saveBtn').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving…');
    });
    $(window).on('beforeunload', function () {
        if (dirty && !submitting) return 'You have unsaved changes.';
    });
    $(document).on('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
            e.preventDefault();
            $form.trigger('submit');
        }
    });

    // ── Rich-text editors (same toolbar the old modals had) ──
    function uploader(kind) {
        return function (blobInfo) {
            return new Promise(function (resolve, reject) {
                if (!UPLOAD_URLS) {
                    reject({ message: 'Create the stock first, then add images.', remove: true });
                    return;
                }
                const fd = new FormData();
                fd.append('file', blobInfo.blob(), blobInfo.filename());
                fd.append('_token', CSRF);
                $.ajax({ url: UPLOAD_URLS[kind], method: 'POST', data: fd, processData: false, contentType: false })
                    .done(function (res) { res.location ? resolve(res.location) : reject('Upload failed: no location returned.'); })
                    .fail(function (xhr) { reject((xhr.responseJSON && xhr.responseJSON.message) || 'Image upload failed.'); });
            });
        };
    }

    $('.se-rich').each(function () {
        const kind = $(this).data('upload');
        tinymce.init({
            target: this,
            plugins: 'preview importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help quickbars emoticons',
            menubar: 'edit view insert format tools table help',
            toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | forecolor backcolor removeformat | link image media table | charmap emoticons | code fullscreen preview',
            toolbar_sticky: true,
            toolbar_sticky_offset: 112,
            toolbar_mode: 'sliding',
            height: 460,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            contextmenu: 'link image table',
            convert_urls: true,
            document_base_url: window.location.origin + '/',
            images_upload_handler: uploader(kind),
            automatic_uploads: true,
            branding: false,
            promotion: false,
            setup: function (editor) {
                editor.on('input change undo redo', markDirty);
            },
        });
    });

    // ── Jump nav: highlight the section in view ──
    const $navLinks = $('#sectionNav a');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                $navLinks.removeClass('active');
                const $link = $navLinks.filter('[href="#' + entry.target.id + '"]').addClass('active');
                if ($link.length) $link[0].scrollIntoView({ block: 'nearest', inline: 'nearest' });
            });
        }, { rootMargin: '-130px 0px -60% 0px' });
        $('.se-section').each(function () { observer.observe(this); });
    }

    // ── Slug: locked until the agent deliberately unlocks it ──
    function toSlug(text) {
        return text.toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '')
            .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 255);
    }
    $('#slugEditToggle').on('click', function () {
        const $input = $('#slugInput');
        if ($input.prop('readonly')) {
            $input.prop('readonly', false).removeClass('cms-input-locked').trigger('focus');
            $('#slugWarning').show();
            $(this).text('Cancel');
        } else {
            $input.val($input.data('original')).prop('readonly', true).addClass('cms-input-locked').removeClass('is-invalid');
            $('#slugPreview').text($input.data('original'));
            $('#slugWarning').hide();
            $(this).text('Edit');
        }
    });
    $('#slugInput').on('input', function () { $('#slugPreview').text(toSlug($(this).val())); });

    // New stock: follow the name until the slug is typed by hand; clearing it
    // hands control back to the name.
    const $newSlug = $('#newSlugInput');
    if ($newSlug.length) {
        let slugTouched = $newSlug.val().trim() !== '';
        const showPreview = function () { $('#newSlugPreview').text(toSlug($newSlug.val()) || 'company-name'); };
        $('#nameInput').on('input', function () {
            if (!slugTouched) { $newSlug.val(toSlug($(this).val())); showPreview(); }
        });
        $newSlug.on('input', function () { slugTouched = $(this).val().trim() !== ''; showPreview(); });
        $newSlug.on('blur', function () {
            if ($(this).val().trim() !== '') $(this).val(toSlug($(this).val()));
            showPreview();
        });
    }

    // ── WittyScore overall, same weights as the public formula ──
    function recalcOverall() {
        let total = 0, weight = 0;
        $('.se-ws-pillar').each(function () {
            const v = parseFloat($(this).val());
            const w = parseFloat($(this).data('weight'));
            if (!isNaN(v)) { total += v * w; weight += w; }
        });
        $('#wsOverall').text(weight > 0 ? (total / weight).toFixed(1) : '—');
    }
    $(document).on('input', '.se-ws-pillar', recalcOverall);
    recalcOverall();

    // ── Logo preview ──
    $('#logoInput').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) { $('#logoPreview').attr('src', e.target.result).show(); };
        reader.readAsDataURL(file);
    });

    // ── FAQ repeater ──
    let rowCounter = Date.now();
    $('#addFaqBtn').on('click', function () {
        const $row = $($('#faqRowTemplate').html().replace(/__INDEX__/g, String(rowCounter++)));
        $('#faqRows').append($row);
        $row.find('input[name$="[q]"]').trigger('focus');
        markDirty();
    });
    $(document).on('click', '.se-faq-remove', function () {
        const $row = $(this).closest('.se-faq-row');
        const hasText = $row.find('input[name$="[q]"]').val().trim() || $row.find('textarea').val().trim();
        if (hasText && !confirm('Remove this FAQ? It is deleted when you save.')) return;
        $row.remove();
        markDirty();
    });
    $(document).on('click', '.se-faq-up', function () {
        const $row = $(this).closest('.se-faq-row');
        $row.prev('.se-faq-row').before($row);
        markDirty();
    });
    $(document).on('click', '.se-faq-down', function () {
        const $row = $(this).closest('.se-faq-row');
        $row.next('.se-faq-row').after($row);
        markDirty();
    });
    $(document).on('change', '.se-faq-row input[type=checkbox]', function () {
        $(this).closest('.se-faq-row').toggleClass('is-inactive', !this.checked);
    });

    // Bounced back with errors: open at the first problem.
    const $firstError = $('.is-invalid').first();
    if ($firstError.length) {
        $('html, body').scrollTop($firstError.offset().top - 150);
    }
});
</script>
@endpush
