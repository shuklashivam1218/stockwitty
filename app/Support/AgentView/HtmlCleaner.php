<?php

namespace App\Support\AgentView;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use LogicException;

/**
 * Prepares a rendered page's HTML for Markdown conversion. Each step is a
 * small, named DOM pass; run() applies them in order and returns the cleaned
 * <body> HTML plus the page metadata read from <head>.
 *
 * Authoring hooks a Blade view can use (see config/agent_view.php):
 *   data-agent-skip   drop this element from the twin (forms, widgets, CTAs)
 *   data-agent-keep   keep this element even though it is hidden (inactive
 *                     tab panels that hold real content, e.g. financials)
 *   @agentOnly        Blade block rendered only into the twin, for content
 *                     that humans only ever see via JavaScript
 */
final class HtmlCleaner
{
    /** Elements that never carry readable content. */
    private const NOISE_TAGS = [
        'script', 'style', 'noscript', 'template', 'svg', 'canvas', 'iframe',
        'form', 'button', 'input', 'select', 'textarea', 'video', 'audio',
    ];

    /** Layout wrappers the converter would otherwise glue together inline. */
    private const BLOCK_WRAPPERS = [
        'main', 'section', 'article', 'aside', 'header', 'footer', 'figure', 'figcaption', 'details',
    ];

    /** Block-level children that make an <a> unconvertible as a Markdown link. */
    private const BLOCK_CHILDREN = 'h1|h2|h3|h4|h5|h6|p|div|ul|ol|dl|table|section|article';

    private DOMDocument $doc;

    private DOMXPath $xpath;

    public function __construct(private readonly string $baseUrl)
    {
    }

    /**
     * @return array{meta: array{title: string, description: string, canonical: string, breadcrumb: string}, body: string}
     */
    public function run(string $html): array
    {
        $this->load($html);

        $meta = $this->readMeta();

        $this->removeSkippedAndHidden();
        $this->removeNoiseTags();
        $this->removeNavigation();
        $this->unwrapInPageAnchors();
        $this->absolutizeUrls();
        $this->unwrapBlockLinks();
        $this->turnFaqDisclosuresIntoHeadings();
        $this->flattenDefinitionLists();
        $this->renameBlockWrappers();

        return ['meta' => $meta, 'body' => $this->bodyHtml()];
    }

    private function load(string $html): void
    {
        $this->doc = new DOMDocument();

        // The XML prolog makes libxml read the markup as UTF-8 (₹, —, …);
        // the flags silence warnings about Alpine attributes like @click.
        $previous = libxml_use_internal_errors(true);
        $this->doc->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_COMPACT);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $this->xpath = new DOMXPath($this->doc);
    }

    /** Title, description and canonical from <head>; breadcrumb trail from the breadcrumb nav. */
    private function readMeta(): array
    {
        $crumbs = [];
        foreach ($this->query('//nav[@aria-label="Breadcrumb"]//li') as $li) {
            $crumbs[] = $this->text($li);
        }

        return [
            'title'       => $this->text($this->query('//head/title')->item(0)),
            'description' => $this->attr('//head/meta[@name="description"]', 'content'),
            'canonical'   => $this->attr('//head/link[@rel="canonical"]', 'href'),
            'breadcrumb'  => implode(' › ', array_filter($crumbs)),
        ];
    }

    /**
     * Drop data-agent-skip elements, aria-hidden decoration, and anything
     * hidden on first paint (Alpine x-show / x-cloak: form errors, empty
     * states, success messages) unless the view marked it data-agent-keep.
     */
    private function removeSkippedAndHidden(): void
    {
        $this->remove('//body//*[@data-agent-skip]');
        $this->remove('//body//*[@aria-hidden="true" or @aria-hidden=""]');
        $this->remove(
            '//body//*[(@x-cloak or contains(translate(@style, " ", ""), "display:none")) and not(@data-agent-keep)]'
        );
    }

    private function removeNoiseTags(): void
    {
        foreach (self::NOISE_TAGS as $tag) {
            $this->remove("//body//{$tag}");
        }
    }

    /** Site nav, table of contents and section tabs repeat what the headings already say. */
    private function removeNavigation(): void
    {
        $this->remove('//body//nav');
    }

    /** "#section" and "javascript:" links mean nothing outside the page: keep only their text. */
    private function unwrapInPageAnchors(): void
    {
        foreach ($this->query('//body//a[@href]') as $a) {
            $href = trim($a->getAttribute('href'));
            if ($href === '' || str_starts_with($href, '#') || str_starts_with($href, 'javascript:')) {
                $this->unwrap($a);
            }
        }
    }

    /** Agents read the twin out of context, so every link and image must be absolute. */
    private function absolutizeUrls(): void
    {
        foreach ($this->query('//body//a[@href]') as $a) {
            $a->setAttribute('href', $this->absolute($a->getAttribute('href')));
        }

        foreach ($this->query('//body//img') as $img) {
            if (trim($img->getAttribute('alt')) === '') {
                $img->parentNode?->removeChild($img);
                continue;
            }
            $img->setAttribute('src', $this->absolute($img->getAttribute('src')));
        }
    }

    /**
     * A card wrapped in <a> (heading + text + meta) cannot become a Markdown
     * link. Keep the card's content and move the link onto its first heading,
     * or onto a trailing "Open" link when the card has no heading.
     */
    private function unwrapBlockLinks(): void
    {
        $blockChild = implode(' or ', array_map(fn ($t) => "self::{$t}", explode('|', self::BLOCK_CHILDREN)));

        foreach ($this->query("//body//a[@href][.//*[{$blockChild}]]") as $a) {
            $href = $a->getAttribute('href');
            $card = $this->doc->createElement('div');

            while ($a->firstChild) {
                $card->appendChild($a->firstChild);
            }
            $a->parentNode?->replaceChild($card, $a);

            $heading = $this->query('.//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]', $card)->item(0);
            $link = $this->doc->createElement('a');
            $link->setAttribute('href', $href);

            if ($heading instanceof DOMElement) {
                while ($heading->firstChild) {
                    $link->appendChild($heading->firstChild);
                }
                $heading->appendChild($link);
            } else {
                $link->appendChild($this->doc->createTextNode('Open'));
                $paragraph = $this->doc->createElement('p');
                $paragraph->appendChild($link);
                $card->appendChild($paragraph);
            }
        }
    }

    /** <details><summary>Question</summary><p>Answer</p></details> -> "### Question" + answer. */
    private function turnFaqDisclosuresIntoHeadings(): void
    {
        foreach ($this->query('//body//summary') as $summary) {
            $this->rename($summary, 'h3');
        }
    }

    /** <dl> has no Markdown form: turn each dt/dd pair into "- **Term:** value". */
    private function flattenDefinitionLists(): void
    {
        foreach ($this->query('//body//dl') as $dl) {
            $list = $this->doc->createElement('ul');
            $item = null;

            foreach ($this->query('.//dt|.//dd', $dl) as $node) {
                if ($node->nodeName === 'dt') {
                    $item = $this->doc->createElement('li');
                    $term = $this->doc->createElement('strong');
                    $term->appendChild($this->doc->createTextNode($this->text($node) . ':'));
                    $item->appendChild($term);
                    $list->appendChild($item);
                    continue;
                }

                if ($item === null) {
                    $item = $this->doc->createElement('li');
                    $list->appendChild($item);
                }
                $item->appendChild($this->doc->createTextNode(' ' . $this->text($node)));
            }

            $dl->parentNode?->replaceChild($list, $dl);
        }
    }

    private function renameBlockWrappers(): void
    {
        foreach (self::BLOCK_WRAPPERS as $tag) {
            foreach ($this->query("//body//{$tag}") as $node) {
                $this->rename($node, 'div');
            }
        }
    }

    private function bodyHtml(): string
    {
        $body = $this->query('//body')->item(0);
        if (! $body) {
            return '';
        }

        $html = '';
        foreach ($body->childNodes as $child) {
            $html .= $this->doc->saveHTML($child);
        }

        return $html;
    }

    // ── DOM utilities ─────────────────────────────────────────────────────

    /** @return DOMNodeList<DOMNode> */
    private function query(string $expression, ?DOMNode $context = null): DOMNodeList
    {
        $result = $this->xpath->query($expression, $context);

        if ($result === false) {
            throw new LogicException("Invalid XPath expression: {$expression}");
        }

        return $result;
    }

    /** Remove every match. Iterates a snapshot, so removing a parent first is safe. */
    private function remove(string $expression): void
    {
        foreach (iterator_to_array($this->query($expression)) as $node) {
            $node->parentNode?->removeChild($node);
        }
    }

    private function unwrap(DOMNode $node): void
    {
        $parent = $node->parentNode;
        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }
        $parent->removeChild($node);
    }

    private function rename(DOMElement $node, string $tag): void
    {
        $replacement = $this->doc->createElement($tag);

        foreach (iterator_to_array($node->attributes ?? []) as $attribute) {
            if (in_array($attribute->nodeName, ['id', 'href', 'src', 'alt'], true)) {
                $replacement->setAttribute($attribute->nodeName, $attribute->nodeValue);
            }
        }
        while ($node->firstChild) {
            $replacement->appendChild($node->firstChild);
        }

        $node->parentNode?->replaceChild($replacement, $node);
    }

    private function text(?DOMNode $node): string
    {
        return $node ? trim(preg_replace('/\s+/u', ' ', $node->textContent)) : '';
    }

    private function attr(string $expression, string $attribute): string
    {
        $node = $this->query($expression)->item(0);

        return $node instanceof DOMElement ? trim($node->getAttribute($attribute)) : '';
    }

    private function absolute(string $url): string
    {
        $url = trim($url);

        if ($url === '' || preg_match('#^([a-z][a-z0-9+.-]*:|//)#i', $url)) {
            return $url;
        }

        return rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');
    }
}
