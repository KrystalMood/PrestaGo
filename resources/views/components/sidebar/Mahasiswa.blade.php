<div class="py-2 mt-2 border-t border-gray-100">
    <span class="px-3 text-xs font-semibold text-gray-400 uppercase">Prestasi</span>
</div>

<li>
    <a href="{{ route('student.achievements.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('student.achievements.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span class="font-medium">Prestasi Saya</span>
    </a>
</li>

<li>
    <a href="{{ route('student.competitions.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('student.competitions.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <span class="font-medium">Daftar Lomba</span>
    </a>
</li>

<li>
    <a href="{{ route('student.feedback.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('student.feedback.*') ? 'bg-brand-light bg-opacity-10 text-brand' : 'hover:bg-gray-100 text-gray-700' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
        <span class="font-medium">Feedback Lomba</span>
    </a>
</li>


