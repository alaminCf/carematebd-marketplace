@props(['value', 'label', 'icon' => null, 'subtext' => null])

<div class="stat-card">
    @if ($icon)
        <div class="stat-icon">
            {!! $icon !!}
        </div>
    @endif
    <div>
        <div class="stat-value">{{ $value }}</div>
        <div class="stat-label">{{ $label }}</div>
        @if ($subtext)
            <div style="font-size: 0.75rem; color: #10b981; font-weight: 600; margin-top: 0.2rem;">{{ $subtext }}</div>
        @endif
    </div>
</div>
