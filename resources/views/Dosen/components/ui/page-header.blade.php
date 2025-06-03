@props(['subtitle' => null, 'actionText' => null, 'actionUrl' => null])

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <div>
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($actionText && $actionUrl)
        <div class="mt-4 sm:mt-0">
            <x-admin.buttons.add-button 
                :route="$actionUrl" 
                :text="$actionText" 
                icon="plus"
                color="indigo"
            />
        </div>
    @endif
</div> 