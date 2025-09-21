@props([
    'type' => 'primary', // primary, secondary, success, danger, warning, info, light, dark
    'size' => 'md', // sm, md, lg
    'pill' => false,
    'class' => ''
])

@php
$badgeClass = 'badge bg-' . $type;
if ($size !== 'md') {
    $badgeClass .= ' fs-' . $size;
}
if ($pill) {
    $badgeClass .= ' rounded-pill';
}
$badgeClass .= ' ' . $class;
@endphp

<span {{ $attributes->merge(['class' => $badgeClass]) }}>
    {{ $slot }}
</span>
