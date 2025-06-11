@props([
    'name',
    'id' => null,
    'label' => '',
    'checked' => false,
    'required' => false,
    'helperText' => '',
    'hasError' => false,
    'errorMessage' => ''
])

<div class="form-group mb-4">
    <div class="flex items-center justify-between">
        @if($label)
            <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700">
                {{ $label }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif
        
        <label for="{{ $id ?? $name }}" class="relative inline-flex items-center cursor-pointer">
            <input 
                type="checkbox" 
                id="{{ $id ?? $name }}" 
                name="{{ $name }}" 
                value="1"
                @if($checked) checked @endif
                class="sr-only peer"
                {{ $attributes }}
            >
            <div class="toggle-visual w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
        </label>
    </div>
    
    @if($helperText)
        <p class="mt-1.5 text-sm text-gray-500">{{ $helperText }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div> 