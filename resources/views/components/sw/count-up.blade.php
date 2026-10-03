@props(['to', 'decimals' => 0, 'prefix' => '', 'suffix' => '', 'duration' => 1200])

{{-- Humans see 0 that animates up on scroll (reveal.js). The Markdown twin
     has no JavaScript, so it gets the final value straight away. --}}
@php
    $initial = \App\Support\AgentView\AgentRequest::isRendering() ? number_format((float) $to, (int) $decimals) : '0';
@endphp
<span data-countup data-to="{{ $to }}" data-decimals="{{ $decimals }}" data-prefix="{{ $prefix }}"
      data-suffix="{{ $suffix }}" data-duration="{{ $duration }}" {{ $attributes }}>{{ $prefix }}{{ $initial }}{{ $suffix }}</span>
