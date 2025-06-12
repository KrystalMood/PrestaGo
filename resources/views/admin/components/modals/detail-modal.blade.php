@props([
    'modalId' => 'detail-modal',
    'title' => 'Detail',
    'titleIcon' => null,
    'gradientFrom' => 'indigo-600',
    'gradientTo' => 'blue-500'
])

<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 overflow-hidden">
        <div class="relative">
            <div class="bg-gradient-to-r from-{{ $gradientFrom }} to-{{ $gradientTo }} p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        @if($titleIcon)
                            {!! $titleIcon !!}
                        @endif
                        {{ $title }}
                    </h3>
                    <button id="close-{{ $modalId }}" class="text-white hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                @if(isset($photo))
                    <div class="flex justify-center -mb-12 mt-4">
                        {{ $photo }}
                    </div>
                @endif
            </div>
            
            <div class="p-6 @if(isset($photo)) pt-16 @endif bg-gray-50">
                {{ $slot }}

                @if(isset($footer))
                    <div class="flex justify-end space-x-3 mt-4">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('{{ $modalId }}');
        const closeButton = document.getElementById('close-{{ $modalId }}');
        
        if (modal && closeButton) {
            closeButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }
    });
</script> 