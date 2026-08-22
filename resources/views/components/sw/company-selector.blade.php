@props(['label' => 'Company', 'slug', 'class' => ''])

<div x-data="companySelectorWidget('{{ $slug }}')" data-companies="{{ json_encode(config('sw.showcase_companies')) }}" class="{{ $class }}">
    <label class="mb-1.5 block text-[11px] font-bold tracking-widest text-muted-foreground uppercase">{{ $label }}</label>
    @include('partials.sw._company-dropdown')
</div>
