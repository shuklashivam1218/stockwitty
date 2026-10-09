<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Styled blocks inside a post body
    |--------------------------------------------------------------------------
    |
    | The only classes the TinyMCE "Styles" menu can apply and the only ones
    | the `blog` purifier profile lets through (config/purifier.php keeps its
    | own copy of this list — BlogEditorConfigTest checks they match).
    | Public styling for them lives with the blog post layout.
    |
    */

    'content_classes' => [
        'sw-callout'   => 'Callout box',
        'sw-pullquote' => 'Pull quote',
        'sw-checklist' => 'Checklist',
        'sw-steps'     => 'Numbered steps',
        'sw-table'     => 'Comparison table',
    ],

    /*
    |--------------------------------------------------------------------------
    | Hero icons
    |--------------------------------------------------------------------------
    |
    | Shown in the post hero when there is no featured image. Every name must
    | exist in resources/views/components/sw/icon.blade.php (tested).
    |
    */

    'hero_icons' => [
        'file-text', 'receipt', 'banknote', 'scale', 'shield-check', 'alert-triangle',
        'help-circle', 'trending-up', 'line-chart', 'bar-chart-3', 'pie-chart', 'landmark',
        'briefcase', 'coins', 'calculator', 'file-search', 'newspaper', 'rocket', 'layers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Limits on the structured blocks around the body
    |--------------------------------------------------------------------------
    */

    'max_chips'     => 6,
    'max_takeaways' => 8,
    'max_faqs'      => 20,
    'max_sources'   => 12,
    'max_related'   => 3,

];
