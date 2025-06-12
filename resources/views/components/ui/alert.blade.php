@props(['type' => 'info', 'message', 'autoDismiss' => true, 'dismissAfter' => 3000])

@php
    $classes = [
        'success' => 'bg-green-100 border border-green-400 text-green-700',
        'error' => 'bg-red-100 border border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-100 border border-blue-400 text-blue-700',
    ][$type];
    
    $id = 'alert-' . uniqid();
@endphp

<div id="{{ $id }}" class="{{ $classes }} px-4 py-3 rounded relative mb-2 alert-dismissible" role="alert">
    <span class="block sm:inline">{{ $message }}</span>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
        <svg class="fill-current h-6 w-6 text-gray-500 cursor-pointer close-alert" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <title>Close</title>
            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
        </svg>
    </span>
</div>

@if($autoDismiss)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alert = document.getElementById('{{ $id }}');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }
        }, {{ $dismissAfter }});
    });

    document.addEventListener('DOMContentLoaded', function() {
        const closeButton = document.querySelector('#{{ $id }} .close-alert');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                const alert = document.getElementById('{{ $id }}');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }
            });
        }
    });
</script>
@endif