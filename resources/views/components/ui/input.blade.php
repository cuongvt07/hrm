@props([
    'type' => 'text',
    'name' => '',
    'label' => '',
    'required' => false,
    'error' => null,
    'help' => null
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'form-input' . ($error ? ' border-red-500' : '')
        ]) }}
    >
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
    
    @if($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>
