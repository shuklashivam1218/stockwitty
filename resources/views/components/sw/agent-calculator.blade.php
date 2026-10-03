{{-- Worked example of an on-page calculator, for the Markdown twin only.
     Compute the numbers with App\Support\AgentView\CalculatorFormulas so they
     match the JavaScript calculator humans use.

     <x-sw.agent-calculator
         title="SIP calculator — worked example"
         formula="FV = P × [((1 + i)^n − 1) / i] × (1 + i)"
         :example="['Monthly investment' => '₹10,000', ..., 'Total value' => '₹23,23,391']"
         note="Estimates only. Returns are not guaranteed." /> --}}
@props(['title', 'formula', 'example', 'note' => null])

@agentOnly
    <h3>{{ $title }}</h3>
    <p>Formula: <code>{{ $formula }}</code></p>
    <x-sw.agent-data-table :head="['Worked example', 'Value']"
                           :rows="collect($example)->map(fn ($value, $label) => [$label, $value])->values()->all()"
                           :note="$note" />
@endagentOnly
