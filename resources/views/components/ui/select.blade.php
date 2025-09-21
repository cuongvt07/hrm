@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'required' => false,
    'error' => null,
    'help' => null,
    'placeholder' => 'Chọn...'
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
    
    <select 
        name="{{ $name }}" 
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'form-select' . ($error ? ' border-red-500' : '')
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
    
    @if($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>
