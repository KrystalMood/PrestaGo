@props(['user'])

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h3 class="text-base font-semibold text-gray-800">Informasi Pengguna</h3>
    </div>
    
    <div class="divide-y divide-gray-200">
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">ID Pengguna</p>
                <p class="font-medium">{{ $user->id }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Dibuat Pada</p>
                <p class="font-medium">{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Diperbarui Pada</p>
                <p class="font-medium">{{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-' }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Login Terakhir</p>
                <p class="font-medium">{{ isset($user->last_login_at) ? $user->last_login_at->format('d M Y, H:i') : 'Belum pernah login' }}</p>
            </div>
        </div>
    </div>
</div> 