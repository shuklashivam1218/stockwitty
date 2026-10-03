{{-- A plain data table that exists only in the Markdown twin. Use it where
     humans get the same data through JavaScript (a chart, an Alpine list),
     so AI agents still receive the numbers.

     <x-sw.agent-data-table
         caption="NSE India price history"
         :head="['Period', 'From', 'To']"
         :rows="[['1M', '₹1,900', '₹1,960'], ...]"
         note="Indicative dealer levels." />

     Cells are escaped text; a cell given as ['text' => ..., 'href' => ...] becomes a link. --}}
@props(['caption' => null, 'head', 'rows', 'note' => null])

@agentOnly
    @if (count($rows))
        @if ($caption)
            <p><strong>{{ $caption }}</strong></p>
        @endif
        <table>
            <thead>
                <tr>
                    @foreach ($head as $h)
                        <th>{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($row as $cell)
                            <td>
                                @if (is_array($cell))
                                    <a href="{{ $cell['href'] }}">{{ $cell['text'] }}</a>
                                @else
                                    {{ $cell }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($note)
            <p>{{ $note }}</p>
        @endif
    @endif
@endagentOnly
