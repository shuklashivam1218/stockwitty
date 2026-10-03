<?php

namespace App\Support\AgentView;

/**
 * Assembles the final twin: YAML front matter (what the page is and where it
 * lives), the converted body, and a short footer pointing agents to the site
 * index and the compliance note.
 */
final class MarkdownDocument
{
    /**
     * @param array{title: string, description: string, canonical: string, breadcrumb: string} $meta
     */
    public static function compose(array $meta, string $body, string $siteUrl): string
    {
        $siteUrl = rtrim($siteUrl, '/');

        return self::frontMatter($meta)
            . "\n\n" . self::tidy($body)
            . "\n\n---\n\n"
            . self::footer($meta['canonical'], $siteUrl)
            . "\n";
    }

    private static function frontMatter(array $meta): string
    {
        $fields = array_filter([
            'title'       => $meta['title'],
            'description' => $meta['description'],
            'url'         => $meta['canonical'],
            'breadcrumb'  => $meta['breadcrumb'],
        ], fn ($value) => $value !== '');

        $lines = ['---'];
        foreach ($fields as $key => $value) {
            // A JSON string is also a valid double-quoted YAML scalar, so
            // colons, quotes and ₹ in titles can never break the front matter.
            $lines[] = $key . ': ' . json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        $lines[] = '---';

        return implode("\n", $lines);
    }

    private static function footer(string $canonical, string $siteUrl): string
    {
        $lines = array_filter([
            $canonical !== '' ? "Source page: {$canonical}" : null,
            "Site index for AI agents: {$siteUrl}/llms.txt",
            "Sitemap: {$siteUrl}/sitemap.xml",
            config('agent_view.footer_note'),
        ]);

        return implode("\n\n", $lines);
    }

    /**
     * Clean up what Blade indentation and removed widgets leave behind:
     * entities (&amp;), padded link text ("[ Title ](url)"), runs of spaces,
     * stray leading spaces (nested list indents are kept) and blank-line runs.
     */
    private static function tidy(string $markdown): string
    {
        $markdown = html_entity_decode($markdown, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $markdown = preg_replace('/\[\s+/u', '[', $markdown);
        $markdown = preg_replace('/\s+\]\(/u', '](', $markdown);
        $markdown = preg_replace('/(?<=\S)[ \t]{2,}(?=\S)/u', ' ', $markdown);
        $markdown = preg_replace('/^[ \t]+(?![-*+] |\d+\. )/mu', '', $markdown);
        // An inline badge right before a heading ("01### Submit KYC",
        // "Investment Thesis # Title") gets its own line.
        $markdown = preg_replace('/^([^\n#]*[^\s#])[ \t]*(#{1,6} )/mu', "$1\n\n$2", $markdown);
        $markdown = preg_replace('/[ \t]+$/mu', '', $markdown);
        $markdown = preg_replace("/\n{3,}/", "\n\n", $markdown);

        return trim($markdown);
    }
}
