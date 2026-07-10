@props([
    'user',
    'size' => 44,
    'variant' => 'thumb',
    'class' => '',
    'alt' => null,
])

@php
    $hasPhoto = (bool) $user->profilePhotoUrl($variant);
    $src = $user->avatarUrl($variant);
    $label = $alt ?? $user->fullName().' profile photo';
@endphp

<span
  {{ $attributes->merge(['class' => 'bdgs-user-avatar '.$class]) }}
  style="--bdgs-avatar-size: {{ (int) $size }}px"
  @if (! $hasPhoto) title="{{ $user->fullName() }}" @endif
>
  <img
    src="{{ $src }}"
    alt="{{ $label }}"
    width="{{ (int) $size }}"
    height="{{ (int) $size }}"
    loading="lazy"
    decoding="async"
    @class(['bdgs-user-avatar__img', 'bdgs-user-avatar__img--placeholder' => ! $hasPhoto])
  >
</span>
