@props([
    'name',
    'id' => null,
    'label' => '',
    'required' => false,
    'accept' => null,
    'helperText' => '',
    'hasError' => false,
    'errorMessage' => '',
    'preview' => null,
    'previewAlt' => ''
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
    
    @if($preview)
        <div class="flex items-center mb-3">
            <img src="{{ $preview }}" alt="{{ $previewAlt }}" class="h-12 w-12 rounded-full object-cover mr-2" loading="lazy">
            <span class="text-sm text-gray-600">Gambar saat ini</span>
        </div>
    @endif
    
    <div class="mt-1 flex items-center">
        <label for="{{ $id ?? $name }}" class="cursor-pointer">
            <div class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                <span>Pilih File</span>
                <input 
                    type="file"
                    id="{{ $id ?? $name }}"
                    name="{{ $name }}"
                    @if($accept) accept="{{ $accept }}" @endif
                    @if($required) required @endif
                    class="sr-only"
                    {{ $attributes }}
                >
            </div>
            <span id="{{ $id ?? $name }}-filename" class="ml-2 text-sm text-gray-500">Belum ada file dipilih</span>
        </label>
    </div>
    
    @if($helperText)
        <p class="mt-1.5 text-sm text-gray-500">{{ $helperText }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>

<script>
    document.getElementById('{{ $id ?? $name }}').addEventListener('change', function(e) {
        const filename = e.target.files[0] ? e.target.files[0].name : 'Belum ada file dipilih';
        document.getElementById('{{ $id ?? $name }}-filename').textContent = filename;
    });
</script> 