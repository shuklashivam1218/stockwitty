@props(['to', 'decimals' => 0, 'prefix' => '', 'suffix' => '', 'duration' => 1200])

<span data-countup data-to="{{ $to }}" data-decimals="{{ $decimals }}" data-prefix="{{ $prefix }}"
      data-suffix="{{ $suffix }}" data-duration="{{ $duration }}" {{ $attributes }}>{{ $prefix }}0{{ $suffix }}</span>
