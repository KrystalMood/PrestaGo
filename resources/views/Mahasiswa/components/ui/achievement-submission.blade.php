@props(['name' => '', 'class' => '', 'avatar' => '', 'achievement' => '', 'date' => '', 'status' => ''])

<div class="flex items-center space-x-4 py-3 border-b border-gray-100 last:border-0">
    <div class="flex-shrink-0">
        <img class="h-10 w-10 rounded-full" src="{{ $avatar }}" alt="{{ $name }}">
    </div>
    
    <div class="min-w-0 flex-1">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-gray-900 truncate">
                {{ $name }}
                <span class="text-xs text-gray-500 ml-1">{{ $class }}</span>
            </p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                {{ $status === 'Menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                   ($status === 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                {{ $status }}
            </span>
        </div>
        
        <div class="mt-1">
            <p class="text-sm text-gray-700 truncate">{{ $achievement }}</p>
            <p class="text-xs text-gray-500 mt-1">Diajukan: {{ $date }}</p>
        </div>
        
        <div class="mt-2 flex space-x-2">
            <a href="#" class="inline-flex items-center px-2 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Detail
            </a>
            <a href="#" class="inline-flex items-center px-2 py-1 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Verifikasi
            </a>
        </div>
    </div>
</div> 