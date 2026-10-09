{{-- Public-site paginator ($paginator->links('partials.sw.pagination')).
     Numbered pills from sm up; on phones just Prev · "Page x of y" · Next. --}}
@if ($paginator->hasPages())
    @php
        $pill   = 'inline-flex h-11 min-w-11 items-center justify-center rounded-xl border px-3 text-sm font-bold transition-all';
        $idle   = 'border-border bg-card text-muted-foreground shadow-soft hover:-translate-y-0.5 hover:border-primary/50 hover:text-primary';
        $edge   = 'inline-flex h-11 items-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all';
        $off    = 'cursor-not-allowed border-border bg-muted text-muted-foreground/50';
    @endphp

    <nav aria-label="Blog pages" class="mt-14 flex items-center justify-between gap-3 sm:justify-center">
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="{{ $edge }} {{ $off }}">
                <x-sw.icon name="arrow-left" class="size-4" /> <span class="hidden sm:inline">Previous</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" data-blog-nav rel="prev" class="{{ $edge }} {{ $idle }}">
                <x-sw.icon name="arrow-left" class="size-4" /> <span class="hidden sm:inline">Previous</span>
            </a>
        @endif

        <ol class="hidden items-center gap-2 sm:flex">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-hidden="true" class="px-1 text-sm font-bold tracking-widest text-muted-foreground">…</li>
                @else
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="{{ $pill }} border-primary bg-primary text-primary-foreground shadow-glow">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" data-blog-nav aria-label="Page {{ $page }}" class="{{ $pill }} {{ $idle }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ol>

        <p class="text-sm font-semibold text-muted-foreground sm:hidden">
            Page <span class="font-bold text-foreground">{{ $paginator->currentPage() }}</span> of {{ $paginator->lastPage() }}
        </p>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" data-blog-nav rel="next" class="{{ $edge }} {{ $idle }}">
                <span class="hidden sm:inline">Next</span> <x-sw.icon name="arrow-right" class="size-4" />
            </a>
        @else
            <span aria-disabled="true" class="{{ $edge }} {{ $off }}">
                <span class="hidden sm:inline">Next</span> <x-sw.icon name="arrow-right" class="size-4" />
            </span>
        @endif
    </nav>

@endif
