@props([
    'type' => 'info', // success, danger, warning, info
    'dismissible' => true,
    'icon' => null
])

@php
$alertClass = 'alert alert-' . $type;
if ($dismissible) {
    $alertClass .= ' alert-dismissible fade show';
}

$defaultIcons = [
    'success' => 'check-circle',
    'danger' => 'exclamation-triangle',
    'warning' => 'exclamation-circle',
    'info' => 'info-circle'
];

$iconName = $icon ?? $defaultIcons[$type] ?? 'info-circle';
@endphp

<div {{ $attributes->merge(['class' => $alertClass]) }} role="alert">
    @if($icon || $iconName)
        <i class="fas fa-{{ $iconName }} me-2"></i>
    @endif
    
    {{ $slot }}
    
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
