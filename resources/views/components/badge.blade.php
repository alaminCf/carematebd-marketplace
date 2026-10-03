@props(['tone' => 'neutral'])

@php
$class = match($tone) {
    'success' => 'badge-success',
    'warning' => 'badge-warning',
    'info', 'primary' => 'badge-info',
    'danger' => 'badge-danger',
    default => 'badge-neutral',
};
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $class]) }}>
    {{ $slot }}
</span>
