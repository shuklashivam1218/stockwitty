<?php

/*
|--------------------------------------------------------------------------
| Site identity for structured data (JSON-LD)
|--------------------------------------------------------------------------
|
| Read by App\Support\Seo\JsonLd. The brand is "StocksWitty", matching the
| stockswitty.com domain. The site used "StockWitty" until October 2026, so
| that spelling is declared as an alternate name: search engines and AI
| answers that learned the old name still tie it to the same organisation.
|
*/

return [

    'organization' => [
        'name'           => 'StocksWitty',
        'alternate_name' => 'StockWitty',
        'url'            => 'https://www.stockswitty.com/',
        'logo'           => 'https://www.stockswitty.com/favicon.svg',
        'email'          => 'hello@stockswitty.com',
        'description'    => 'Investment platform for unlisted and pre-IPO shares in India, with honest research, transparent pricing and same-day demat delivery. A distributor of unlisted shares, not a SEBI-registered investment adviser.',
        'area_served'    => 'IN',
    ],

    'website' => [
        'name'        => 'StocksWitty',
        'url'         => 'https://www.stockswitty.com/',
        'in_language' => 'en-IN',
    ],

];
