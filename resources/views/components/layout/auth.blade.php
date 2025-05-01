<x-layout.app :title="$title ?? 'Authentication'" bodyClass="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-gray-50 to-gray-100">
    <div class="w-full sm:max-w-4xl mt-6 mb-6 bg-white shadow-custom overflow-hidden sm:rounded-lg grid grid-cols-1 md:grid-cols-2">
        <div class="p-6 md:p-10 bg-gray-100 flex flex-col justify-center border-r border-gray-200">
            <div class="flex items-center mb-8">
                <svg class="w-12 h-12 mr-3 text-brand" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-2xl font-bold text-gray-800">DashWind</span>
            </div>
            <div class="mb-8 text-center">
                <img src="/images/auth-illustration.svg" alt="Admin Dashboard Illustration" class="inline-block max-w-xs mx-auto rounded-lg shadow-sm">
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Admin Dashboard Starter Kit</h2>
            <ul class="text-sm text-gray-600 space-y-3">
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 mr-3">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </span>
                    Light/dark mode toggle
                </li>
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 mr-3">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </span>
                    Redux toolkit and other utility libraries configured
                </li>
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 mr-3">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </span>
                    User-friendly documentation
                </li>
                <li class="flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 mr-3">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </span>
                    Daisy UI components, Tailwind CSS support
                </li>
            </ul>
        </div>

        <div class="p-6 md:p-10 bg-white flex items-center justify-center">
            <div class="w-full">
                {{ $slot }}
            </div>
        </div>
    </div>
    <div class="mt-2 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} DashWind. All rights reserved.
    </div>
</x-layout.app>