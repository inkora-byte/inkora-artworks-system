@props(['status', 'color' => 'secondary'])

<span {{ $attributes->merge(['class' => "badge bg-{$color}-subtle text-{$color}-emphasis border border-{$color}-subtle"]) }}>
    {{ $status }}
</span>
