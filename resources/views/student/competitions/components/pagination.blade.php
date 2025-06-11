@props(['data' => collect()])

@if($data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->hasPages())
    <div class="mt-4 flex justify-between items-center">
        <div class="text-sm text-gray-700">
            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results
        </div>
        
        <div class="pagination-links">
            {{ $data->appends(request()->query())->links() }}
        </div>
    </div>
@endif 