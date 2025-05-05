@props(['title'])

<div class="flex items-center mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-brand hover:text-brand-dark mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>
    <h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
</div> 