<?php

namespace App\Support\AgentView;

use League\HTMLToMarkdown\Converter\TableConverter;
use League\HTMLToMarkdown\HtmlConverter;

/**
 * Rendered page HTML in, Markdown twin out:
 *   HtmlCleaner (strip widgets, fix links)  ->  league/html-to-markdown  ->  MarkdownDocument
 */
final class PageToMarkdown
{
    public function convert(string $html, string $siteUrl): string
    {
        $cleaned = (new HtmlCleaner($siteUrl))->run($html);

        $body = $this->converter()->convert($cleaned['body']);

        return MarkdownDocument::compose($cleaned['meta'], $body, $siteUrl);
    }

    private function converter(): HtmlConverter
    {
        $converter = new HtmlConverter([
            'header_style'            => 'atx',   // "## Heading", not underlines
            'strip_tags'              => true,    // keep the text of div/span wrappers
            'strip_placeholder_links' => true,    // <a> without href
            'hard_break'              => true,
            'list_item_style'         => '-',
            'use_autolinks'           => false,
        ]);

        $converter->getEnvironment()->addConverter(new TableConverter());

        return $converter;
    }
}
