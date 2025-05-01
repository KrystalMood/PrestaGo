@props(['id' => uniqid('toast-')])

<div 
    id="{{ $id }}"
    x-data="{
        show: false,
        type: '{{ $type ?? 'info' }}',
        message: '{{ $message ?? '' }}',
        init() {
            if (this.message) {
                setTimeout(() => { this.show = true; }, 100);
                setTimeout(() => { this.show = false; }, {{ $duration ?? 5000 }});
            }
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform translate-y-10 opacity-0"
    x-transition:enter-end="transform translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="transform translate-y-0 opacity-100"
    x-transition:leave-end="transform translate-y-10 opacity-0"
    class="hidden"
>
    {{ $slot }}
</div>

@once
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            window.toast.success("{{ session('success') }}");
        @endif
        
        @if (session('error'))
            window.toast.error("{{ session('error') }}");
        @endif
        
        @if (session('info'))
            window.toast.info("{{ session('info') }}");
        @endif
        
        @if (session('warning'))
            window.toast.warning("{{ session('warning') }}");
        @endif
    });
</script>
@endonce