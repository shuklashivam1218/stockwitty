@props(['head', 'rows'])

<div class="mt-6 overflow-x-auto rounded-2xl border border-border shadow-soft">
    <table class="w-full min-w-[640px] border-collapse text-left text-sm">
        <thead>
            <tr class="bg-green-50">
                @foreach ($head as $h)
                    <th class="px-4 py-3 text-xs font-bold tracking-wide text-primary uppercase">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $r)
                <tr class="border-t border-border bg-card">
                    @foreach ($r as $j => $cell)
                        <td class="px-4 py-3 align-top {{ $j === 0 ? 'font-semibold text-foreground' : 'text-muted-foreground' }}">{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
