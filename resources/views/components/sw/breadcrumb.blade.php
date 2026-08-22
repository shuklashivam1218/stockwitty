@props(['items'])

<nav aria-label="Breadcrumb" class="border-b border-border bg-green-50">
    <ol class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-2 gap-y-1 px-4 py-3 text-xs sm:px-6 lg:px-8 sm:text-sm">
        @foreach ($items as $i => $c)
            <li class="flex items-center gap-2">
                @if ($i > 0)
                    <x-sw.icon name="chevron-right" class="size-3.5 text-green-200" />
                @endif
                @if ($i === count($items) - 1 || empty($c['href']))
                    <span aria-current="page" class="font-semibold text-foreground">{{ $c['label'] }}</span>
                @else
                    <a href="{{ $c['href'] }}" class="font-medium text-muted-foreground underline-offset-4 transition-colors hover:text-primary hover:underline">
                        {{ $c['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
