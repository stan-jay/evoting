@props(['status'])

@php
    $key = strtolower((string) $status);
    $map = [
        'active' => 'status-active',
        'pending' => 'status-pending',
        'closed' => 'status-closed',
        'declared' => 'status-declared',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'status-badge ' . ($map[$key] ?? 'status-closed')]) }}>
    {{ ucfirst($key) }}
</span>
