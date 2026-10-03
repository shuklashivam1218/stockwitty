{{-- "Human view / AI agent" toggle, rendered once by layouts/sw.blade.php on
     every page that has a Markdown twin. "AI agent" fetches the live twin
     (the exact Markdown crawlers get) and shows it with its metadata.
     Behaviour lives in resources/js/sw/agent-view.js; #agent in the URL
     opens the agent view directly, so it can be shared. --}}
@props(['markdownUrl'])

@php
    $options = [
        'markdownUrl'     => $markdownUrl,
        'contentSignal'   => config('agent_view.content_signal'),
        'allowedCrawlers' => config('agent_view.allowed_crawlers'),
    ];
@endphp

<div x-data="agentView(@js($options))" @keydown.escape.window="agent && setAgent(false)">
    <div role="group" aria-label="Page view"
         class="fixed bottom-4 left-4 z-[60] inline-flex items-center gap-0.5 rounded-full border border-border/70 bg-background/85 p-0.5 shadow-soft backdrop-blur-xl sm:bottom-6 sm:left-6">
        <button type="button" @click="setAgent(false)" :aria-pressed="!agent"
                :class="!agent ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground'"
                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold transition-colors">
            <x-sw.icon name="user" class="size-3.5" />
            Human view
        </button>
        <button type="button" @click="setAgent(true)" :aria-pressed="agent"
                :class="agent ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground'"
                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold transition-colors">
            <x-sw.icon name="bot" class="size-3.5" />
            AI agent
        </button>
    </div>

    <div x-show="agent" x-cloak role="dialog" aria-modal="true" aria-label="AI agent view of this page"
         class="fixed inset-0 z-[55] overflow-y-auto bg-background">
        <div class="mx-auto max-w-7xl px-4 pt-8 pb-24 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
                <x-sw.icon name="bot" class="size-4 text-mint" />
                <span class="font-semibold text-foreground">AI agent view</span>
                <span class="min-w-0">— exactly what crawlers and AI agents receive from this page</span>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
                <div class="min-w-0 overflow-hidden rounded-2xl border border-border/70 bg-card shadow-soft">
                    <div class="flex items-center gap-2 border-b border-border/60 bg-secondary/60 px-4 py-2.5">
                        <span class="size-2.5 shrink-0 rounded-full bg-destructive/60"></span>
                        <span class="size-2.5 shrink-0 rounded-full bg-amber-400/70"></span>
                        <span class="size-2.5 shrink-0 rounded-full bg-mint/70"></span>
                        <span class="ml-2 min-w-0 truncate font-mono text-xs text-muted-foreground" x-text="'GET ' + markdownUrl + ' → text/markdown'"></span>
                        <button type="button" @click="copy()" x-show="markdown"
                                class="ml-auto inline-flex shrink-0 items-center gap-1 rounded-lg border border-border bg-card px-2.5 py-1 text-[11px] font-semibold text-muted-foreground hover:text-primary">
                            <x-sw.icon name="copy" class="size-3.5" />
                            <span x-text="copied ? 'Copied' : 'Copy'"></span>
                        </button>
                    </div>

                    <p x-show="loading" class="p-6 font-mono text-sm text-muted-foreground">Fetching the Markdown twin…</p>
                    <p x-show="error" x-cloak class="p-6 text-sm font-semibold text-destructive" x-text="error"></p>
                    <pre x-show="markdown" x-cloak x-text="markdown"
                         class="p-5 font-mono text-[12px] leading-relaxed whitespace-pre-wrap text-foreground [overflow-wrap:anywhere] sm:p-6 sm:text-[13px]"></pre>
                </div>

                <aside class="h-fit rounded-2xl border border-border/70 bg-card p-5 shadow-soft lg:sticky lg:top-8">
                    <h2 class="flex items-center gap-2 text-sm font-bold text-foreground">
                        <x-sw.icon name="bot" class="size-4 text-mint" />
                        What AI agents get here
                    </h2>
                    <dl class="mt-4 space-y-3">
                        <template x-for="row in meta" :key="row[0]">
                            <div class="text-xs">
                                <dt class="font-mono font-semibold text-primary" x-text="row[0]"></dt>
                                <dd class="mt-0.5 font-mono leading-relaxed text-muted-foreground [overflow-wrap:anywhere]" x-text="row[1]"></dd>
                            </div>
                        </template>
                    </dl>
                    <a :href="markdownUrl" target="_blank" rel="noopener"
                       class="mt-5 inline-flex items-center gap-1.5 text-xs font-bold text-primary underline-offset-4 hover:underline">
                        Open the raw Markdown <x-sw.icon name="external-link" class="size-3.5" />
                    </a>
                    <p class="mt-4 rounded-xl border border-dashed border-mint/60 bg-green-50 p-3 text-[11px] leading-relaxed text-muted-foreground">
                        This is the live Markdown twin, generated from this page's own content. Crawlers get it at
                        the .md URL above, or at this page's URL when they send <span class="font-mono">Accept: text/markdown</span>.
                    </p>
                </aside>
            </div>
        </div>
    </div>
</div>
