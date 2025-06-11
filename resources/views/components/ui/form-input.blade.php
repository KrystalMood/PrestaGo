@props([
    'type' => 'text',
    'name',
    'id' => null,
    'value' => '',
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'helperText' => '',
    'hasError' => false,
    'errorMessage' => ''
])

<div class="form-group mb-4">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-brand focus:border-brand' . ($hasError ? ' border-red-500 focus:ring-red-500 focus:border-red-500' : '')]) }}
    />
    
    @if($helperText)
        <p class="mt-1.5 text-sm text-gray-500">{{ $helperText }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div> 