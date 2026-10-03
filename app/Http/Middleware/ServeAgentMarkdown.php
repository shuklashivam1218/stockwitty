<?php

namespace App\Http\Middleware;

use App\Support\AgentView\AgentRequest;
use App\Support\AgentView\PageToMarkdown;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves every public page's Markdown twin to AI crawlers and agents.
 *
 *   GET /blog/x.md                        -> Markdown twin of /blog/x/
 *   GET /blog/x/  (Accept: text/markdown) -> same Markdown, same URL
 *   GET /blog/x/  (a browser)             -> normal HTML, plus a Link header
 *                                            advertising the twin
 *
 * The twin is made by rendering the real page (cookie-less, as a logged-out
 * visitor) and converting its HTML, so humans and agents always get the same
 * content. User-Agent is never inspected: switching content per bot is
 * cloaking, which search engines penalise.
 *
 * Registered as global middleware (bootstrap/app.php) because a /x.md path
 * has to be mapped to /x/ before the router matches it.
 */
class ServeAgentMarkdown
{
    public function __construct(private readonly PageToMarkdown $pageToMarkdown)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodSafe()) {
            return $next($request);
        }

        if (AgentRequest::isMarkdownPath($request)) {
            return $this->serveTwin($request, $next);
        }

        if (AgentRequest::prefersMarkdown($request) && AgentRequest::isEligible($request)) {
            $request->attributes->set(AgentRequest::RENDER_ATTRIBUTE, true);

            return $this->renderMarkdown($request, $request->getPathInfo(), $next);
        }

        return $this->advertiseTwin($request, $next($request));
    }

    /** /blog/x.md: render /blog/x/ and convert it, or answer in Markdown why we can't. */
    private function serveTwin(Request $request, Closure $next): Response
    {
        $htmlPath    = AgentRequest::htmlPathFor($request->getPathInfo());
        $htmlRequest = AgentRequest::htmlRequestFor($request, $htmlPath);

        if (! AgentRequest::isEligible($htmlRequest)) {
            return $this->unavailable(404);
        }

        $response = $this->renderMarkdown($htmlRequest, $htmlPath, $next);

        // The page itself failed (unknown company slug, DB down...): keep its
        // status code, but never hand an agent an HTML error page at a .md URL.
        return $this->isMarkdown($response) ? $response : $this->unavailable($response->getStatusCode());
    }

    /**
     * Render the page (or reuse the cached twin) and wrap it as a Markdown
     * response. A page that did not render as 200 HTML (redirect, 404, error)
     * is returned untouched.
     */
    private function renderMarkdown(Request $htmlRequest, string $htmlPath, Closure $next): Response
    {
        $cacheKey = 'agent_view.twin:' . $htmlPath;
        $markdown = Cache::get($cacheKey);

        if ($markdown === null) {
            $page = $next($htmlRequest);

            if (! $this->isHtmlPage($page)) {
                return $page;
            }

            $markdown = $this->pageToMarkdown->convert($page->getContent(), $this->siteUrl());
            Cache::put($cacheKey, $markdown, now()->addMinutes(config('agent_view.cache_minutes', 15)));
        }

        return $this->markdownResponse($markdown, $htmlPath);
    }

    private function markdownResponse(string $markdown, string $htmlPath): Response
    {
        $canonical = $this->siteUrl() . $htmlPath;

        return response($markdown, 200, [
            'Content-Type'   => 'text/markdown; charset=UTF-8',
            'Vary'           => 'Accept',
            // Search engines should credit (and index) the HTML page, not the twin.
            'Link'           => "<{$canonical}>; rel=\"canonical\"",
            'Content-Signal' => config('agent_view.content_signal'),
            'Cache-Control'  => 'public, max-age=' . (60 * config('agent_view.cache_minutes', 15)),
            'X-Robots-Tag'   => 'index, follow',
        ]);
    }

    /** On a normal HTML page with a twin, tell clients where the Markdown lives. */
    private function advertiseTwin(Request $request, Response $response): Response
    {
        if (! $this->isHtmlPage($response) || ! AgentRequest::isEligibleRoute($request->route())) {
            return $response;
        }

        $twin = AgentRequest::markdownPathFor($request->getPathInfo());

        $response->headers->set('Link', "<{$this->siteUrl()}{$twin}>; rel=\"alternate\"; type=\"text/markdown\"", false);
        $response->headers->set('Vary', 'Accept', false);

        return $response;
    }

    private function isHtmlPage(Response $response): bool
    {
        return $response->getStatusCode() === 200
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }

    private function isMarkdown(Response $response): bool
    {
        return str_starts_with((string) $response->headers->get('Content-Type'), 'text/markdown');
    }

    private function unavailable(int $status): Response
    {
        $serverError = $status >= 500;
        $message = $serverError
            ? "# Temporarily unavailable\n\nPlease try again later.\n"
            : "# Not found\n\nNo Markdown version exists for this URL.\n";

        return response($message, $serverError ? 503 : 404, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
        ]);
    }

    private function siteUrl(): string
    {
        return rtrim(config('app.url'), '/');
    }
}
