@props([
    'type' => 'primary',
    'size' => 'md',
    'icon' => null,
    'disabled' => false,
    'variant' => 'solid' // solid, outline, ghost
])

@php
$buttonClass = 'btn';
$buttonClass .= $variant === 'outline' ? ' btn-outline-' . $type : ' btn-' . $type;
$buttonClass .= $variant === 'ghost' ? ' btn-link text-' . $type : '';

if ($size !== 'md') {
    $buttonClass .= ' btn-' . $size;
}
if ($disabled) {
    $buttonClass .= ' opacity-50 cursor-not-allowed';
}
@endphp

<button {{ $attributes->merge([
    'class' => $buttonClass,
    'disabled' => $disabled
]) }}>
    @if($icon)
        <i class="fas fa-{{ $icon }} {{ $slot->isNotEmpty() ? 'me-2' : '' }}"></i>
    @endif
    {{ $slot }}
</button>
