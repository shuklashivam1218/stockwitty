@php
$swLinks = [
    ['label' => 'Home', 'href' => '/'],
    ['label' => 'Unlisted Shares', 'href' => '/unlisted-shares/'],
    ['label' => 'Why Witty', 'href' => '/why-witty/'],
    ['label' => 'Screener', 'href' => '/screener/'],
    ['label' => 'Blog', 'href' => '/blog/'],
    ['label' => 'News', 'href' => '/news/'],
    ['label' => 'Contact', 'href' => '/#footer'],
];
$authUid = session('uid');
$hasPrivilege = $authUid ? !empty(\App\Helpers\Privilege::get()) : false;
@endphp

<header x-data="navBar()"
        :class="scrolled ? 'border-b border-border/70 bg-background/75 backdrop-blur-xl shadow-soft' : 'border-b border-transparent bg-transparent'"
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300">

    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="/" class="text-xl text-primary font-bold tracking-tight">
            Stock<span class="text-mint">Witty</span>
        </a>

        <ul class="hidden items-center gap-1 lg:flex">
            <li>
                <a href="/" class="relative rounded-md px-3 py-2 text-sm font-semibold text-foreground/80 transition-colors hover:bg-muted hover:text-primary">
                    Home
                </a>
            </li>

            <li class="relative" @mouseenter="mega = true" @mouseleave="mega = false" @keydown.escape="mega = false">
                <button type="button" :aria-expanded="mega" @click="mega = true"
                        class="inline-flex items-center gap-1 rounded-md px-3 py-2 text-sm font-semibold text-foreground/80 transition-colors hover:bg-muted hover:text-primary">
                    Products
                    <x-sw.icon name="chevron-down" class="size-4 transition-transform" x-bind:class="mega ? 'rotate-180' : ''" />
                </button>
                <div x-show="mega" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                     class="absolute left-0 top-full w-[38rem] rounded-2xl border border-border bg-card p-3 shadow-soft" style="display: none;">
                    <div class="grid grid-cols-2 gap-1">
                        @foreach (config('sw.products') as $p)
                            <a href="{{ $p['href'] }}" @click="mega = false"
                               class="flex items-start gap-3 rounded-xl p-3 transition-colors hover:bg-green-50">
                                <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-muted text-primary">
                                    <x-sw.icon :name="$p['icon']" class="size-4" />
                                </span>
                                <span>
                                    <span class="block text-sm font-bold text-foreground">{{ $p['title'] }}</span>
                                    <span class="block text-xs text-muted-foreground">{{ $p['desc'] }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>

            @foreach (array_slice($swLinks, 1) as $l)
                <li>
                    <a href="{{ $l['href'] }}" class="relative rounded-md px-3 py-2 text-sm font-semibold text-foreground/80 transition-colors hover:bg-muted hover:text-primary">
                        {{ $l['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="hidden items-center gap-3 lg:flex">
            @if ($authUid)
                <div class="relative" @click.outside="userMenu = false">
                    <button type="button" @click="userMenu = !userMenu" :aria-expanded="userMenu"
                            class="flex items-center gap-2 rounded-lg border border-border px-3 py-2 text-sm font-semibold text-foreground/80 transition-colors hover:border-primary hover:text-primary">
                        <span class="grid size-6 place-items-center rounded-full bg-primary text-xs font-bold text-primary-foreground">
                            {{ strtoupper(substr(session('name', '?'), 0, 1)) }}
                        </span>
                        {{ session('name') }}
                        <x-sw.icon name="chevron-down" class="size-4 transition-transform" x-bind:class="userMenu ? 'rotate-180' : ''" />
                    </button>
                    <div x-show="userMenu" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         class="absolute right-0 top-full mt-2 w-52 rounded-xl border border-border bg-card p-2 shadow-soft" style="display: none;">
                        @if ($hasPrivilege)
                            <a href="{{ route('admin.index') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-primary">
                                <x-sw.icon name="layers" class="size-4" /> Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-primary">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/login/" class="rounded-lg border border-primary/40 px-4 py-2 text-sm font-semibold text-primary transition-all hover:border-primary hover:bg-muted">
                    Sign In
                </a>
                <a href="/signup/" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition-all hover:scale-[1.03] hover:bg-green-700">
                    Get Started
                </a>
            @endif
        </div>

        <button type="button" :aria-label="open ? 'Close menu' : 'Open menu'" @click="open = !open"
                class="rounded-lg border border-border p-2 text-primary lg:hidden">
            <x-sw.icon name="x" class="size-5" x-show="open" style="display: none;" />
            <x-sw.icon name="menu" class="size-5" x-show="!open" />
        </button>
    </nav>

    <div x-show="open" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="max-h-[80vh] overflow-y-auto border-t border-border bg-background/95 backdrop-blur-xl lg:hidden" style="display: none;">
        <ul class="space-y-1 px-4 py-4">
            <li>
                <a href="/" @click="open = false" class="block rounded-md px-3 py-2.5 text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-primary">
                    Home
                </a>
            </li>
            <li>
                <button type="button" :aria-expanded="mobileProducts" @click="mobileProducts = !mobileProducts"
                        class="flex w-full items-center justify-between rounded-md px-3 py-2.5 text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-primary">
                    Products
                    <x-sw.icon name="chevron-down" class="size-4 transition-transform" x-bind:class="mobileProducts ? 'rotate-180' : ''" />
                </button>
                <ul x-show="mobileProducts" x-transition class="overflow-hidden pl-3" style="display: none;">
                    @foreach (config('sw.products') as $p)
                        <li>
                            <a href="{{ $p['href'] }}" @click="open = false"
                               class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm font-semibold text-muted-foreground hover:text-primary">
                                <x-sw.icon :name="$p['icon']" class="size-4 text-primary" />
                                {{ $p['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            @foreach (array_slice($swLinks, 1) as $l)
                <li>
                    <a href="{{ $l['href'] }}" @click="open = false" class="block rounded-md px-3 py-2.5 text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-primary">
                        {{ $l['label'] }}
                    </a>
                </li>
            @endforeach
            <li class="pt-2">
                @if ($authUid)
                    <div class="rounded-lg border border-border p-3">
                        <p class="flex items-center gap-2 text-sm font-bold text-foreground">
                            <span class="grid size-7 place-items-center rounded-full bg-primary text-xs font-bold text-primary-foreground">
                                {{ strtoupper(substr(session('name', '?'), 0, 1)) }}
                            </span>
                            {{ session('name') }}
                        </p>
                        <div class="mt-3 flex flex-col gap-2">
                            @if ($hasPrivilege)
                                <a href="{{ route('admin.index') }}" @click="open = false" class="rounded-lg bg-primary px-4 py-2.5 text-center text-sm font-semibold text-primary-foreground">
                                    Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-lg border border-primary/40 px-4 py-2.5 text-center text-sm font-semibold text-primary">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex gap-3">
                        <a href="/login/" @click="open = false" class="flex-1 rounded-lg border border-primary/40 px-4 py-2.5 text-center text-sm font-semibold text-primary">
                            Sign In
                        </a>
                        <a href="/signup/" @click="open = false" class="flex-1 rounded-lg bg-primary px-4 py-2.5 text-center text-sm font-semibold text-primary-foreground">
                            Get Started
                        </a>
                    </div>
                @endif
            </li>
        </ul>
    </div>
</header>
