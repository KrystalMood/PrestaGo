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
    'previewAlt' => '',
    'multiple' => false
])

@php
    $inputId = $id ?? str_replace(['[',']'], ['_',''], $name);
@endphp

<div class="form-group mb-4">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-2">
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
    
    <!-- Preview container for all selected files -->
    <div id="{{ $inputId }}-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-3"></div>
    
    <div class="max-w-xl">
        <label
            class="flex justify-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none">
            <span class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span class="font-medium text-gray-600">
                    Drop files to Attach, or
                    <span class="text-blue-600 underline">browse</span>
                </span>
            </span>
            <input 
                type="file"
                id="{{ $inputId }}"
                name="{{ $name }}"
                @if($accept) accept="{{ $accept }}" @endif
                @if($required) required @endif
                @if($multiple) multiple @endif
                class="hidden"
                {{ $attributes }}
            >
        </label>
    </div>

    <p class="mt-2 text-sm text-gray-500" id="{{ $inputId }}-filename-container">
        <span id="{{ $inputId }}-filename">Belum ada file dipilih</span>
    </p>
    
    @if($helperText)
        <p class="mt-1.5 text-sm text-gray-500">{{ $helperText }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>

<script>
    (function() {
        // Wait for the document to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeUploader);
        } else {
            initializeUploader();
        }
        
        function initializeUploader() {
            const inputId = '{{ $inputId }}';
            const input = document.getElementById(inputId);
            const previewContainer = document.getElementById(`${inputId}-preview-container`);
            const filenameText = document.getElementById(`${inputId}-filename`);
            
            if (!input || !previewContainer || !filenameText) {
                console.error(`Missing elements for file uploader ${inputId}`);
                return;
            }
            
            // Create the global removal function
            window[`removeFile_${inputId}`] = function(index) {
                // Create a new FileList with the file removed
                const files = Array.from(input.files);
                files.splice(index, 1);
                
                // Update file count display
                if (files.length === 0) {
                    filenameText.textContent = 'Belum ada file dipilih';
                } else {
                    filenameText.textContent = files.length > 1 
                        ? `${files.length} files terpilih` 
                        : files[0].name;
                }
                
                // Clear the input and recreate it to reset files
                const parent = input.parentNode;
                const newInput = input.cloneNode(true);
                
                // Create a DataTransfer object and append files
                if (files.length > 0) {
                    try {
                        const dt = new DataTransfer();
                        files.forEach(file => dt.items.add(file));
                        newInput.files = dt.files;
                    } catch (e) {
                        console.error('Error creating DataTransfer', e);
                    }
                }
                
                // Replace the input
                parent.replaceChild(newInput, input);
                
                // Add the event listener to the new input
                newInput.addEventListener('change', handleFileSelection);
                
                // Update the previews
                updatePreviews(files);
            };
            
            // Add event listener
            input.addEventListener('change', handleFileSelection);
            
            function handleFileSelection() {
                const files = Array.from(input.files);
                
                // Update the filename display
                if (files.length === 0) {
                    filenameText.textContent = 'Belum ada file dipilih';
                } else {
                    filenameText.textContent = files.length > 1 
                        ? `${files.length} files terpilih` 
                        : files[0].name;
                }
                
                // Update previews
                updatePreviews(files);
            }
            
            function updatePreviews(files) {
                // Clear existing previews
                previewContainer.innerHTML = '';
                
                // Create previews for each file
                files.forEach((file, index) => {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative';
                    
                    if (file.type.startsWith('image/')) {
                        // For images
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewDiv.innerHTML = `
                                <div class="relative group">
                                    <img src="${e.target.result}" alt="${file.name}" class="h-24 w-full object-cover rounded">
                                    <div class="absolute top-0 right-0 p-1">
                                        <button type="button" class="bg-red-500 text-white rounded-full p-1" 
                                            onclick="removeFile_${inputId}(${index})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // For non-image files
                        previewDiv.innerHTML = `
                            <div class="relative bg-gray-100 p-3 rounded h-24 flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-xs text-gray-500 truncate w-full text-center">${file.name}</span>
                                <div class="absolute top-0 right-0 p-1">
                                    <button type="button" class="bg-red-500 text-white rounded-full p-1" 
                                        onclick="removeFile_${inputId}(${index})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `;
                    }
                    
                    previewContainer.appendChild(previewDiv);
                });
            }
        }
    })();
</script>