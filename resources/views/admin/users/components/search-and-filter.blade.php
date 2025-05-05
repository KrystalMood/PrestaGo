@props(['roles' => []])

<div class="bg-white rounded-lg shadow-custom p-6 mb-6">
    <div class="flex flex-col md:flex-row justify-between gap-4">
        <div class="flex-1">
            <form method="GET" action="" class="flex gap-2">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email pengguna..." class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white font-medium rounded-lg flex items-center">
                    <span>Cari</span>
                </button>
            </form>
        </div>
        
        <div class="flex gap-2">
            <div class="relative">
                <select name="role" id="role-filter" class="appearance-none bg-white border border-gray-300 rounded-lg py-2 pl-3 pr-10 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
                    <option value="">Semua Peran</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->level_kode }}" {{ request('role') == $role->level_kode ? 'selected' : '' }}>
                            {{ $role->level_nama }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            
            <a href="" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white font-medium rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </div>
</div>

<script>
    document.getElementById('role-filter').addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('role', this.value);
        } else {
            url.searchParams.delete('role');
        }
        window.location.href = url.toString();
    });
</script>
