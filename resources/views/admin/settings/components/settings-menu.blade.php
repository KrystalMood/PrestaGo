<div class="flex flex-wrap mb-6 border-b border-gray-200">
    <div class="mr-2">
        <a href="{{ route('admin.settings.index') }}" class="inline-block px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.index') && !request()->routeIs('admin.settings.*.*') ? 'border-blue-500 text-blue-600 font-medium' : 'border-transparent hover:border-gray-300 text-gray-600 hover:text-gray-700' }}">
            <i class="fas fa-cog mr-1"></i> Ikhtisar
        </a>
    </div>
    <div class="mr-2">
        <a href="{{ route('admin.settings.general') }}" class="inline-block px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.general*') ? 'border-blue-500 text-blue-600 font-medium' : 'border-transparent hover:border-gray-300 text-gray-600 hover:text-gray-700' }}">
            <i class="fas fa-sliders-h mr-1"></i> Umum
        </a>
    </div>
    <div class="mr-2">
        <a href="{{ route('admin.settings.email') }}" class="inline-block px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.email*') ? 'border-blue-500 text-blue-600 font-medium' : 'border-transparent hover:border-gray-300 text-gray-600 hover:text-gray-700' }}">
            <i class="fas fa-envelope mr-1"></i> Email
        </a>
    </div>
    <div class="mr-2">
        <a href="{{ route('admin.settings.security') }}" class="inline-block px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.security*') ? 'border-blue-500 text-blue-600 font-medium' : 'border-transparent hover:border-gray-300 text-gray-600 hover:text-gray-700' }}">
            <i class="fas fa-shield-alt mr-1"></i> Keamanan
        </a>
    </div>
    <div class="mr-2">
        <a href="{{ route('admin.settings.display') }}" class="inline-block px-4 py-2 border-b-2 {{ request()->routeIs('admin.settings.display*') ? 'border-blue-500 text-blue-600 font-medium' : 'border-transparent hover:border-gray-300 text-gray-600 hover:text-gray-700' }}">
            <i class="fas fa-desktop mr-1"></i> Tampilan
        </a>
    </div>
</div> 