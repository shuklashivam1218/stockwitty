<?php

namespace App\Support\AgentView;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Small, stateless helpers for the AI agent view: URL mapping between a page
 * and its Markdown twin, content negotiation, and the "am I rendering for an
 * agent right now?" flag that Blade reads through @agentOnly.
 *
 * URL convention (matches the site's trailing-slash routes):
 *   /                              <->  /index.md
 *   /blog/                         <->  /blog.md
 *   /blog/tax-on-unlisted-shares/  <->  /blog/tax-on-unlisted-shares.md
 */
final class AgentRequest
{
    /** Request attribute set while a page is being rendered for its Markdown twin. */
    public const RENDER_ATTRIBUTE = 'agent_view.rendering';

    private const MARKDOWN_SUFFIX = '.md';

    private const HOMEPAGE_TWIN = '/index.md';

    public static function isMarkdownPath(Request $request): bool
    {
        return str_ends_with($request->getPathInfo(), self::MARKDOWN_SUFFIX);
    }

    /** "/blog/x.md" -> "/blog/x/", "/index.md" -> "/". */
    public static function htmlPathFor(string $markdownPath): string
    {
        if ($markdownPath === self::HOMEPAGE_TWIN) {
            return '/';
        }

        return Str::beforeLast($markdownPath, self::MARKDOWN_SUFFIX) . '/';
    }

    /** "/blog/x/" -> "/blog/x.md", "/" -> "/index.md". */
    public static function markdownPathFor(string $htmlPath): string
    {
        $trimmed = rtrim($htmlPath, '/');

        return $trimmed === '' ? self::HOMEPAGE_TWIN : $trimmed . self::MARKDOWN_SUFFIX;
    }

    /**
     * True only when the client explicitly ranks Markdown above HTML.
     * Browsers send "text/html,...,*\/*" and so always get HTML.
     */
    public static function prefersMarkdown(Request $request): bool
    {
        $accept = (string) $request->header('Accept', '');

        if (! str_contains($accept, 'text/markdown')) {
            return false;
        }

        return $request->prefers(['text/markdown', 'text/html']) === 'text/markdown';
    }

    /**
     * A fresh, cookie-less GET for the HTML page behind a twin. Cookie-less on
     * purpose: every agent must get the same public, logged-out page, and the
     * cached twin must never contain anything from one visitor's session.
     */
    public static function htmlRequestFor(Request $request, string $htmlPath): Request
    {
        $htmlRequest = Request::create(
            $request->getSchemeAndHttpHost() . $htmlPath,
            'GET',
            server: $request->server->all(),
        );

        $htmlRequest->headers->set('Accept', 'text/html');
        $htmlRequest->headers->remove('Cookie');
        $htmlRequest->attributes->set(self::RENDER_ATTRIBUTE, true);

        return $htmlRequest;
    }

    /** Whether the route this request resolves to is listed in config('agent_view.routes'). */
    public static function isEligible(Request $request): bool
    {
        return self::isEligibleRoute(self::matchRoute($request));
    }

    public static function isEligibleRoute(?Route $route): bool
    {
        $name = $route?->getName();

        return $name !== null && Str::is(config('agent_view.routes', []), $name);
    }

    /** True while the current request is rendering a page for its Markdown twin. */
    public static function isRendering(): bool
    {
        return app()->bound('request')
            && request()->attributes->get(self::RENDER_ATTRIBUTE) === true;
    }

    /**
     * The twin URL for the page currently being rendered, or null when the
     * current route has no twin (login, admin...). Used by the layout for the
     * <link rel="alternate"> tag and the toggle.
     */
    public static function currentMarkdownUrl(): ?string
    {
        if (! app()->bound('request') || ! self::isEligibleRoute(request()->route())) {
            return null;
        }

        return self::markdownPathFor(request()->getPathInfo());
    }

    private static function matchRoute(Request $request): ?Route
    {
        try {
            return app('router')->getRoutes()->match($request);
        } catch (HttpException) {
            return null;
        }
    }
}
