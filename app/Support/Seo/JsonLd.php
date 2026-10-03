<?php

namespace App\Support\Seo;

/**
 * Builders for schema.org JSON-LD. Each method returns a plain array for one
 * entity; script() serialises one or more of them into a <script> tag.
 *
 * Where each entity is emitted:
 *   Organization + WebSite  every page (layouts/sw.blade.php)
 *   BreadcrumbList          <x-sw.breadcrumb>
 *   FAQPage                 <x-sw.faq-schema> (home FAQ, blog posts, company pages)
 *   Article                 <x-sw.blog-post-layout>
 */
final class JsonLd
{
    public static function organization(): array
    {
        $org = config('seo.organization');

        return [
            '@type'         => ['Organization', 'FinancialService'],
            '@id'           => $org['url'] . '#organization',
            'name'          => $org['name'],
            'alternateName' => $org['alternate_name'],
            'url'           => $org['url'],
            'logo'          => $org['logo'],
            'email'         => $org['email'],
            'description'   => $org['description'],
            'areaServed'    => $org['area_served'],
        ];
    }

    public static function website(): array
    {
        $site = config('seo.website');

        return [
            '@type'      => 'WebSite',
            '@id'        => $site['url'] . '#website',
            'name'       => $site['name'],
            'url'        => $site['url'],
            'inLanguage' => $site['in_language'],
            'publisher'  => ['@id' => config('seo.organization.url') . '#organization'],
        ];
    }

    /**
     * @param array<int, array{label: string, href?: string}> $items  same shape <x-sw.breadcrumb> takes;
     *                                                               the last item is the current page
     */
    public static function breadcrumbs(array $items, string $currentUrl): array
    {
        $elements = [];

        foreach (array_values($items) as $i => $item) {
            $elements[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $item['label'],
                'item'     => empty($item['href']) ? $currentUrl : self::absolute($item['href']),
            ];
        }

        return ['@type' => 'BreadcrumbList', 'itemListElement' => $elements];
    }

    /** @param iterable<array{q: string, a: string}> $faqs */
    public static function faqPage(iterable $faqs): array
    {
        $questions = [];

        foreach ($faqs as $faq) {
            $questions[] = [
                '@type'          => 'Question',
                'name'           => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ];
        }

        return ['@type' => 'FAQPage', 'mainEntity' => $questions];
    }

    public static function article(string $headline, string $description, string $url, ?string $image = null): array
    {
        $publisher = ['@id' => config('seo.organization.url') . '#organization'];

        return array_filter([
            '@type'            => 'Article',
            'headline'         => $headline,
            'description'      => $description,
            'url'              => $url,
            'mainEntityOfPage' => $url,
            'image'            => $image ? self::absolute($image) : null,
            'inLanguage'       => config('seo.website.in_language'),
            'author'           => $publisher,
            'publisher'        => $publisher,
        ]);
    }

    /** One <script type="application/ld+json"> holding the given entities. */
    public static function script(array ...$entities): string
    {
        $payload = count($entities) === 1
            ? ['@context' => 'https://schema.org'] + $entities[0]
            : ['@context' => 'https://schema.org', '@graph' => $entities];

        // JSON_HEX_TAG turns "<" into <, so text coming from the database
        // (FAQ answers) can never close the script tag early.
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);

        return '<script type="application/ld+json">' . $json . '</script>';
    }

    /** The canonical URL of the page being rendered, built the same way as the layout's canonical tag. */
    public static function currentUrl(): string
    {
        return rtrim(config('app.url'), '/') . request()->getPathInfo();
    }

    private static function absolute(string $href): string
    {
        return preg_match('#^https?://#i', $href)
            ? $href
            : rtrim(config('app.url'), '/') . '/' . ltrim($href, '/');
    }
}
