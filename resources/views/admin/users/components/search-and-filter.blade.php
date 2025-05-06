@props(['roles' => []])

<div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4 w-full md:w-2/3">
        <!-- Search -->
        <div class="w-full">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="form-control">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="input input-bordered w-full pl-10" placeholder="Cari pengguna berdasarkan nama atau email...">
                    </div>
                </div>
            </form>
        </div>

        <!-- Filter -->
        <div class="w-full md:w-1/3">
            <form action="{{ route('admin.users.index') }}" method="GET" id="filter-form">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="form-control">
                    <select name="role" id="role" class="select select-bordered w-full" onchange="document.getElementById('filter-form').submit()">
                        <option value="">Semua Peran</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->level_kode }}" {{ request('role') == $role->level_kode ? 'selected' : '' }}>
                                {{ $role->level_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Button -->
    <div class="w-full md:w-auto flex justify-end">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Pengguna
        </a>
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
