@props(['submissions' => []])

<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Pengajuan Prestasi Terbaru</h3>
        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Prestasi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-8 h-8 rounded-full">
                                    <img src="{{ $submission['avatar'] }}" alt="Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-medium">{{ $submission['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $submission['class'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $submission['achievement'] }}</td>
                    <td>{{ $submission['date'] }}</td>
                    <td>
                        <div class="badge badge-{{ $submission['status_color'] }} gap-1">
                            {{ $submission['status'] }}
                        </div>
                    </td>
                    <td>
                        @if($submission['status'] === 'Pending')
                            <div class="flex gap-1">
                                <button class="btn btn-xs btn-success">Setujui</button>
                                <button class="btn btn-xs btn-error">Tolak</button>
                            </div>
                        @else
                            <button class="btn btn-xs btn-ghost">Lihat</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-center mt-4">
        <div class="join">
            <button class="join-item btn btn-sm btn-ghost">«</button>
            <button class="join-item btn btn-sm btn-ghost bg-indigo-100">1</button>
            <button class="join-item btn btn-sm btn-ghost">2</button>
            <button class="join-item btn btn-sm btn-ghost">3</button>
            <button class="join-item btn btn-sm btn-ghost">»</button>
        </div>
    </div>
</div>