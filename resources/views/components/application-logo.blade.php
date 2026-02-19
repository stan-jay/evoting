@props(['size' => 40])

<img
    src="{{ asset('assets/sjs-logo-white.png') }}"
    alt="{{ config('app.name', 'E-Voting') }} logo"
    width="{{ (int) $size }}"
    height="{{ (int) $size }}"
    style="width: {{ (int) $size }}px; height: {{ (int) $size }}px;"
    {{ $attributes->merge([
        'class' => 'inline-block shrink-0 rounded-full object-cover',
    ]) }}
>
