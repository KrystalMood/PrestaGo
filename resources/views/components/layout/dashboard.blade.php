<x-layout.app :title="$title ?? 'Dashboard'" bodyClass="font-sans antialiased bg-gray-100 min-h-screen">
    <div class="navbar bg-white shadow-md">
        <div class="flex-1">
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl">{{ config('app.name', 'Laravel') }}</a>
        </div>
        <div class="flex-none">
            <x-ui.navbar-dropdown />
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        {{ $slot }}
    </div>
</x-layout.app>