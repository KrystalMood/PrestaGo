<!-- Show achievement Modal -->

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Detail Achievement</h3>
                <form method="dialog">
                    <button class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <div id="achievement-details" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2 flex items-center justify-center mb-4">
                    <div class="bg-indigo-100 rounded-full p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
                
                <!-- achievement Name -->
                <div class="col-span-2 text-center mb-2">
                    <h2 id="achievement-name" class="text-2xl font-bold text-gray-900"></h2>
                    <p id="achievement-level" class="text-gray-500"></p>
                </div>
                
                <!-- achievement Details -->
                <div class="col-span-2">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Left Column -->
                            <div>
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Judul Prestasi</h4>
                                    <p id="achievement-organizer" class="mt-1 text-base font-medium text-gray-900">{{ $achievement->title }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">nama kompetisi</h4>
                                    <p id="achievement-category" class="mt-1 text-base font-medium text-gray-900">{{ $achievement->competition_name }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Kompetisi Terkait</h4>
                                    <div class="mt-1">
                                        <span id="achievement-status" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">{{ $achievement->competition->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div>
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Jenis Prestasi</h4>
                                    <p id="achievement-dates" class="mt-1 text-base font-medium text-gray-900">{{ $achievement->type }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Tingkat Prestasi</h4>
                                    <p id="achievement-dates" class="mt-1 text-base font-medium text-gray-900">{{ $achievement->level }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Date</h4>
                                    <p id="achievement-registration" class="mt-1 text-base font-medium text-gray-900">{{ $achievement->date ? $achievement->date->format('d M Y') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-base font-medium text-gray-700 mb-2">Deskripsi</h4>
                    <div id="achievement-description" class="bg-gray-50 p-4 rounded-lg text-gray-700">{{ $achievement->description }}</div>
                </div>
                
                <div class="col-span-2 mt-4">
                    <h4 class="text-base font-medium text-gray-700 mb-2">Bukti</h4>
                    <div id="achievement-description" class="bg-gray-50 p-4 rounded-lg text-gray-700">
                        <ul class="list-disc pl-5">
                            @foreach ($achievement->attachments as $attachment)
                                @if (in_array(strtolower($attachment->file_type), ['jpg', 'jpeg', 'png']))
                                    <!-- Tampilkan gambar -->
                                    <li class="mb-2">
                                        <img src="{{ Storage::url($attachment->file_path) }}" alt="Bukti Prestasi - {{ $attachment->file_name }}" class="max-w-full h-auto mb-2">
                                    </li>
                                @elseif (strtolower($attachment->file_type) === 'pdf')
                                    <!-- Tampilkan link untuk PDF -->
                                    <li class="mb-2">
                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat PDF: {{ $attachment->file_name }}</a>
                                    </li>
                                @else
                                    <p clxass="text-gray-500">Tipe file tidak didukung: {{ $attachment->file_name }} ({{ $attachment->file_type }})</p>
                                @endif
                            @endforeach
                        </ul>
                            @if ($achievement->attachments->isEmpty())
                                <p>Tidak ada bukti yang tersedia.</p>
                            @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 text-right">
            <form method="dialog" class="inline-flex">
                <button class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Tutup
                </button>
            </form>
            <button type="button" id="edit-from-show" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="openPopup('{{ route('Mahasiswa.achievements.edit',$achievement->id)  }}')">
                Edit Kompetisi
            </button>
