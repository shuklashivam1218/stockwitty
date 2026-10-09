<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Builds a table of contents from the <h2>s in admin-authored HTML (CMS
 * pages, blog posts). There is no fixed set of sections, so each <h2> gets a
 * unique slug id plus the scroll-anchor classes x-sw.toc-layout's scroll-spy
 * needs, injected into the HTML itself.
 */
class HtmlToc
{
    public const H2_CLASSES = 'mt-14 scroll-mt-28 text-2xl font-bold text-foreground sm:text-3xl';

    /**
     * @param  string[]  $reservedIds  ids already used on the page (e.g. "faq", "sources")
     * @return array{toc: array<int, array{id: string, label: string}>, html: string}
     */
    public static function build(string $html, array $reservedIds = []): array
    {
        if (trim($html) === '') {
            return ['toc' => [], 'html' => $html];
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div id="__toc_root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $toc  = [];
        $seen = $reservedIds;

        foreach (iterator_to_array($dom->getElementsByTagName('h2')) as $h2) {
            $label = trim(preg_replace('/\s+/u', ' ', $h2->textContent));
            if ($label === '') {
                continue;
            }

            $base = Str::slug($label) ?: 'section';
            $id   = $base;
            $n    = 2;
            while (in_array($id, $seen, true)) {
                $id = $base . '-' . $n++;
            }
            $seen[] = $id;

            $h2->setAttribute('id', $id);
            $h2->setAttribute('class', trim($h2->getAttribute('class') . ' ' . self::H2_CLASSES));

            $toc[] = ['id' => $id, 'label' => $label];
        }

        $root = $dom->getElementById('__toc_root');
        $out  = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return ['toc' => $toc, 'html' => $out];
    }
}
