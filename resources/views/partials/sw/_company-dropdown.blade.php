{{-- Assumes an enclosing Alpine scope exposing: open, search, filteredCompanies, company, selectCompany() --}}
<div class="relative" @click.outside="open = false">
    <button type="button" @click="open = !open" :aria-expanded="open" aria-label="Select a company"
            class="flex w-full min-w-0 items-center justify-between gap-3 rounded-xl border border-border bg-card px-3 py-2.5 text-left text-sm font-bold text-foreground shadow-soft transition-colors hover:border-primary/50 sm:w-72">
        <span class="flex min-w-0 items-center gap-2.5">
            <span class="grid size-7 shrink-0 place-items-center rounded-lg bg-primary text-[10px] font-bold text-primary-foreground" x-text="company.initials"></span>
            <span class="truncate" x-text="company.name"></span>
        </span>
        <x-sw.icon name="chevrons-up-down" class="size-4 shrink-0 text-muted-foreground" />
    </button>

    <div x-show="open" x-transition class="absolute z-40 mt-2 w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-xl border border-border bg-card shadow-soft" style="display: none;">
        <div class="flex items-center gap-2 border-b border-border px-3">
            <x-sw.icon name="search" class="size-4 shrink-0 text-muted-foreground" />
            <input type="text" x-model="search" placeholder="Search companies…"
                   class="h-10 w-full border-0 bg-transparent px-0 text-sm outline-none" />
        </div>
        <ul class="max-h-72 overflow-y-auto p-1">
            <template x-for="c in filteredCompanies" :key="c.slug">
                <li>
                    <button type="button" @click="selectCompany(c.slug)"
                            class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-left hover:bg-green-50">
                        <span class="grid size-6 shrink-0 place-items-center rounded-md bg-green-100 text-[9px] font-bold text-primary" x-text="c.initials"></span>
                        <span class="flex-1 truncate text-sm font-semibold" x-text="c.name"></span>
                        <span class="text-xs font-bold text-muted-foreground" x-text="'₹' + c.price.toLocaleString('en-IN')"></span>
                        <x-sw.icon name="check" class="size-4 text-primary" x-show="c.slug === slug" style="display: none;" />
                    </button>
                </li>
            </template>
            <li x-show="filteredCompanies.length === 0" class="px-3 py-4 text-center text-sm text-muted-foreground" style="display: none;">
                No company found.
            </li>
        </ul>
    </div>
</div>
