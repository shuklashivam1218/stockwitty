<p class="mt-8 flex items-start gap-2 rounded-xl border border-border bg-green-50 px-4 py-3 text-xs text-muted-foreground">
    <x-sw.icon name="info" class="mt-0.5 size-4 shrink-0 text-primary" />
    <span>{{ $slot->isEmpty() ? 'Figures shown are illustrative demo data for layout purposes. Verify all prices, rates and returns before you invest.' : $slot }}</span>
</p>
