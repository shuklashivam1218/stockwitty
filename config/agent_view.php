<?php

/*
|--------------------------------------------------------------------------
| AI agent view
|--------------------------------------------------------------------------
|
| Every public page has a Markdown twin for AI crawlers and agents, served
| at the same path with a .md suffix (/blog/tax-on-unlisted-shares.md,
| /index.md for the homepage) or at the page's own URL when the request
| sends "Accept: text/markdown". The twin is generated from the page's own
| rendered HTML, so it can never drift from what humans see. The
| "Human view / AI agent" toggle on each page shows the same twin.
|
| See App\Http\Middleware\ServeAgentMarkdown for the request flow.
|
*/

return [

    /*
     | Route names (Str::is patterns) that get a Markdown twin. Anything not
     | listed here (auth, admin, profile, health check) answers .md with 404.
     */
    'routes' => [
        'home',
        'sw.*',
        'disclaimer',
    ],

    /*
     | How long a generated twin is cached. Company pages carry live prices,
     | so keep this short. Every deploy clears it (deploy.sh runs cache:clear).
     */
    'cache_minutes' => 15,

    /*
     | Content Signals (contentsignals.org): what crawlers may use our content
     | for. Mirrored in public/robots.txt; also sent as a header on the twin.
     */
    'content_signal' => 'search=yes, ai-input=yes, ai-train=yes',

    /*
     | AI crawlers that public/robots.txt explicitly allows. Shown on the
     | toggle's metadata panel; keep in sync with robots.txt.
     */
    'allowed_crawlers' => [
        'GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'PerplexityBot',
        'Google-Extended', 'Applebot-Extended', 'Amazonbot',
    ],

    /*
     | Appended to every twin so an agent quoting a single page still carries
     | the compliance position with it.
     */
    'footer_note' => 'StockWitty is a distributor of unlisted shares and an information platform, not a SEBI-registered investment adviser. Unlisted shares are illiquid and high-risk, with no guarantee of any IPO, listing or exit. This is information, not investment advice.',

];
