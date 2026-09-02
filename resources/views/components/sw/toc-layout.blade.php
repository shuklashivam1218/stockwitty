@props(['items'])
@php $ids = array_column($items, 'id'); @endphp

<div class="mx-auto w-full max-w-[1160px] px-4 sm:px-6 lg:flex lg:justify-center lg:gap-12" x-data="tocSpy(@js($ids))">
    <div class="min-w-0 lg:max-w-[780px] lg:flex-1">
        <div class="sticky top-16 z-30 -mx-4 border-b border-border bg-background/90 px-4 py-3 backdrop-blur sm:-mx-6 sm:px-6 lg:hidden">
            <p class="text-[0.7rem] font-bold tracking-[0.14em] text-primary uppercase">On this page</p>
            <select @change="scrollToSection($event.target.value)" :value="active"
                    aria-label="Jump to section"
                    class="mt-2 h-11 w-full rounded-xl border border-border bg-card px-3 text-sm font-semibold text-foreground outline-none">
                @foreach ($items as $item)
                    <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                @endforeach
            </select>
        </div>

        {{ $slot }}
    </div>

    <aside class="hidden shrink-0 lg:block lg:w-[248px]">
        <nav aria-label="Table of contents" class="sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto">
            <p class="text-[0.7rem] font-bold tracking-[0.14em] text-primary uppercase">On this page</p>
            <ol class="mt-4 border-l border-green-100">
                @foreach ($items as $item)
                    <li class="relative">
                        <span aria-hidden class="absolute top-0 -left-px h-full w-[2px] rounded-full transition-colors"
                              :class="active === '{{ $item['id'] }}' ? 'bg-primary' : 'bg-transparent'"></span>
                        <a href="#{{ $item['id'] }}" @click.prevent="scrollToSection('{{ $item['id'] }}')"
                           :aria-current="active === '{{ $item['id'] }}' ? 'true' : null"
                           :class="active === '{{ $item['id'] }}' ? 'font-bold text-foreground' : 'font-medium text-muted-foreground hover:text-primary'"
                           class="block rounded-r-md py-1.5 pl-4 text-sm leading-snug transition-colors">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </aside>
</div>
