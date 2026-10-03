// "Human view / AI agent" toggle (resources/views/components/sw/agent-view.blade.php).
// The agent view fetches the page's live Markdown twin, the same response AI
// crawlers get, and lists what an agent learns from it. Styling stays in the
// Blade file: Tailwind only scans views, not this file.

const AGENT_HASH = '#agent';

function schemaTypes() {
    const types = new Set();

    document.querySelectorAll('script[type="application/ld+json"]').forEach((el) => {
        try {
            const data = JSON.parse(el.textContent);
            const entities = data['@graph'] ?? [data];
            entities.forEach((entity) => [].concat(entity['@type'] ?? []).forEach((t) => types.add(t)));
        } catch {
            // A malformed block is simply not listed.
        }
    });

    return [...types];
}

function canonicalFromLinkHeader(header) {
    const match = /<([^>]+)>;\s*rel="canonical"/.exec(header || '');
    return match ? match[1] : '';
}

export function agentView({ markdownUrl, contentSignal, allowedCrawlers }) {
    return {
        markdownUrl,
        agent: false,
        loading: false,
        error: '',
        markdown: '',
        copied: false,
        response: { status: 0, contentType: '', canonical: '' },

        init() {
            if (window.location.hash === AGENT_HASH) this.setAgent(true);
        },

        setAgent(on) {
            this.agent = on;
            document.documentElement.style.overflow = on ? 'hidden' : '';

            const url = on ? AGENT_HASH : window.location.pathname + window.location.search;
            window.history.replaceState(null, '', url);

            if (on && !this.markdown && !this.loading) this.load();
        },

        async load() {
            this.loading = true;
            this.error = '';

            try {
                const res = await fetch(this.markdownUrl, { headers: { Accept: 'text/markdown' } });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                this.markdown = await res.text();
                this.response = {
                    status: res.status,
                    contentType: res.headers.get('Content-Type') || '',
                    canonical: canonicalFromLinkHeader(res.headers.get('Link')),
                };
            } catch (e) {
                this.error = `Could not load the Markdown twin (${e.message}). Try again in a moment.`;
            } finally {
                this.loading = false;
            }
        },

        async copy() {
            await navigator.clipboard.writeText(this.markdown);
            this.copied = true;
            setTimeout(() => (this.copied = false), 2000);
        },

        get meta() {
            const bytes = new TextEncoder().encode(this.markdown).length;
            const words = this.markdown.split(/\s+/).filter(Boolean).length;
            const types = schemaTypes();

            return [
                ['request', `GET ${this.markdownUrl} · accept: text/markdown`],
                [
                    'response',
                    this.markdown
                        ? `${this.response.status} · ${this.response.contentType.split(';')[0]} · ~${bytes.toLocaleString('en-IN')} bytes · ${words.toLocaleString('en-IN')} words`
                        : '—',
                ],
                ['canonical', this.response.canonical || window.location.origin + window.location.pathname],
                ['content-signal', contentSignal],
                ['schema (JSON-LD)', types.length ? types.join(', ') : 'none'],
                ['robots.txt', `${allowedCrawlers.join(', ')} allowed`],
                ['llms.txt', `${window.location.origin}/llms.txt`],
                ['sitemap', `${window.location.origin}/sitemap.xml`],
            ];
        },
    };
}
