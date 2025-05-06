@props(['data' => collect()])

@if($data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->hasPages())
<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-b-lg shadow-custom">
    <div class="flex-1 flex justify-between sm:hidden">
        @if($data->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50">
                Sebelumnya
            </span>
        @else
            <a href="{{ $data->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Sebelumnya
            </a>
        @endif
        
        @if($data->hasMorePages())
            <a href="{{ $data->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Berikutnya
            </a>
        @else
            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50">
                Berikutnya
            </span>
        @endif
    </div>
    
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700">
                Menampilkan
                <span class="font-medium">{{ $data->firstItem() ?? 0 }}</span>
                sampai
                <span class="font-medium">{{ $data->lastItem() ?? 0 }}</span>
                dari
                <span class="font-medium">{{ $data->total() }}</span>
                data
            </p>
        </div>
        
        <div>
            {{ $data->links() }}
        </div>
    </div>
</div>
@endif 