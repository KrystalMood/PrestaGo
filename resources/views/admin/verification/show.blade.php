@component('layouts.admin', ['title' => 'Detail Prestasi'])
    <div class="bg-white rounded-lg shadow-custom p-6">
        <div class="mb-6">
            @include('admin.components.ui.page-header', [
                'title' => 'Detail Prestasi',
                'subtitle' => 'Halaman ini menampilkan detail prestasi mahasiswa dan memungkinkan Anda melakukan verifikasi.',
                'backUrl' => route('admin.verification.index'),
            ])
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Achievement Information -->
            <div class="lg:col-span-2">
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $achievement->title }}</h2>
                    <div class="flex items-center mb-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($achievement->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                            @if($achievement->status == 'verified') bg-green-100 text-green-800 @endif
                            @if($achievement->status == 'rejected') bg-red-100 text-red-800 @endif
                        ">
                            @if($achievement->status == 'pending') Menunggu Verifikasi @endif
                            @if($achievement->status == 'verified') Disetujui @endif
                            @if($achievement->status == 'rejected') Ditolak @endif
                        </span>
                        <span class="text-sm text-gray-500 ml-4">
                            {{ \Carbon\Carbon::parse($achievement->date)->format('d F Y') }}
                        </span>
                    </div>
                    
                    <div class="prose max-w-none mb-4">
                        <p>{{ $achievement->description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Kompetisi</p>
                            <p class="font-medium">{{ $achievement->competition_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tingkat</p>
                            <p class="font-medium capitalize">{{ $achievement->level }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Kategori</p>
                            <p class="font-medium capitalize">{{ $achievement->type }}</p>
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Lampiran</h3>
                    <div class="space-y-2">
                        @forelse($achievement->attachments as $attachment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <x-icons.document class="h-5 w-5 text-blue-500 mr-2" />
                                    <span>{{ $attachment->file_name }}</span>
                                    <span class="text-xs text-gray-500 ml-2">({{ round($attachment->file_size / 1024) }} KB)</span>
                                </div>
                                <a href="{{ route('admin.verification.download', $attachment->attachment_id) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors duration-150 ease-in-out">
                                    <x-icons.download class="h-5 w-5" />
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Tidak ada lampiran</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Student Info & Actions -->
            <div>
                <!-- Student Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Mahasiswa</h3>
                    <div class="flex items-center mb-4">
                        @if($achievement->user->photo)
                            <img src="{{ asset('storage/' . $achievement->user->photo) }}" 
                                 class="h-12 w-12 rounded-full object-cover mr-3" alt="{{ $achievement->user->name }}">
                        @else
                            <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                <span class="text-xl font-semibold text-gray-600">
                                    {{ substr($achievement->user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold">{{ $achievement->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $achievement->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Verification Form -->
                @if($achievement->status == 'pending')
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Verifikasi Prestasi</h3>
                        
                        <div class="space-y-4">
                            <!-- Approve -->
                            <form action="{{ route('admin.verification.approve', $achievement->achievement_id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <x-icons.check class="h-5 w-5 mr-2" />
                                    Setujui Prestasi
                                </button>
                            </form>

                            <!-- Reject -->
                            <form action="{{ route('admin.verification.reject', $achievement->achievement_id) }}" method="POST" class="w-full">
                                @csrf
                                <div class="mb-3">
                                    <label for="rejected_reason" class="block text-sm font-medium text-gray-700 mb-1">
                                        Alasan Penolakan
                                    </label>
                                    <textarea id="rejected_reason" name="rejected_reason" rows="3" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Berikan alasan mengapa prestasi ini ditolak"></textarea>
                                </div>
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <x-icons.x class="h-5 w-5 mr-2" />
                                    Tolak Prestasi
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Verifikasi</h3>
                        <div class="text-sm">
                            <p>Status: 
                                <span class="font-medium 
                                    @if($achievement->status == 'verified') text-green-600 @endif
                                    @if($achievement->status == 'rejected') text-red-600 @endif
                                ">
                                    @if($achievement->status == 'verified') Disetujui @endif
                                    @if($achievement->status == 'rejected') Ditolak @endif
                                </span>
                            </p>
                            <p class="mt-1">Diverifikasi oleh: {{ $achievement->verifier->name }}</p>
                            <p class="mt-1">Tanggal verifikasi: {{ \Carbon\Carbon::parse($achievement->verified_at)->format('d F Y H:i') }}</p>
                            
                            @if($achievement->status == 'rejected')
                                <div class="mt-3">
                                    <p class="font-medium text-gray-700">Alasan Penolakan:</p>
                                    <p class="text-gray-600 mt-1">{{ $achievement->rejected_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endcomponent 