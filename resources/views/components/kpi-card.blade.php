@props(['label', 'value', 'icon' => null, 'color' => 'primary'])

<div {{ $attributes->merge(['class' => 'card kpi-card border-0 shadow-sm h-100']) }}>
    <div class="card-body d-flex align-items-center">
        @if ($icon)
            <div class="kpi-icon bg-{{ $color }}-subtle text-{{ $color }} me-3">
                <i class="fa-solid {{ $icon }}"></i>
            </div>
        @endif
        <div>
            <div class="text-muted small text-uppercase">{{ $label }}</div>
            <div class="fs-4 fw-bold">{{ $value }}</div>
        </div>
    </div>
</div>
