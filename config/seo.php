<?php

/*
|--------------------------------------------------------------------------
| Site identity for structured data (JSON-LD)
|--------------------------------------------------------------------------
|
| Read by App\Support\Seo\JsonLd. The visible brand is "StockWitty"; the
| domain is stockswitty.com, so the domain spelling is declared as an
| alternate name to tie both spellings to the same organisation.
|
*/

return [

    'organization' => [
        'name'           => 'StockWitty',
        'alternate_name' => 'StocksWitty',
        'url'            => 'https://www.stockswitty.com/',
        'logo'           => 'https://www.stockswitty.com/favicon.svg',
        'email'          => 'hello@stockswitty.com',
        'description'    => 'Investment platform for unlisted and pre-IPO shares in India, with honest research, transparent pricing and same-day demat delivery. A distributor of unlisted shares, not a SEBI-registered investment adviser.',
        'area_served'    => 'IN',
    ],

    'website' => [
        'name'        => 'StockWitty',
        'url'         => 'https://www.stockswitty.com/',
        'in_language' => 'en-IN',
    ],

];
