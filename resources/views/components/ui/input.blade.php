@props(['type' => 'text', 'name', 'id' => null, 'value' => '', 'label' => '', 'placeholder' => '', 'icon' => null])

<div class="form-control">
    @if($label)
        <label for="{{ $id ?? $name }}" class="label">
            <span class="label-text font-medium text-gray-700">{{ $label }}</span>
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                {!! $icon !!}
            </span>
        @endif
        
        <input 
            id="{{ $id ?? $name }}" 
            class="input input-bordered w-full {{ $icon ? 'pl-10' : '' }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes }}
        />
    </div>
    
    @error($name)
        <div class="label">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </div>
    @enderror
</div>