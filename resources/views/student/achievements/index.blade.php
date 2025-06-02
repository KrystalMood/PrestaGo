@extends('components.shared.content')

@section('content')

@component('layouts.mahasiswa', ['title' => 'Prestasi Saya'])
<div class="bg-white rounded-lg shadow-custom p-6">
    <div class="mb-6">
        @include('student.achievements.components.page-header', [
            'title' => 'Prestasi Saya',
            'subtitle' => 'Kelola dan pantau prestasi yang telah Anda raih',
            'actionText' => 'Tambah Prestasi',
            'actionIcon' => 'plus',
            'useModal' => true,
            'modalId' => 'create-achievement-modal'
        ])
    </div>
    
    @php
        $stats = [
            [
                'title' => 'Total Prestasi',
                'value' => $totalAchievements ?? 0,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'trend' => 'neutral'
            ],
            [
                'title' => 'Prestasi Terverifikasi',
                'value' => $verifiedAchievements ?? 0,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'trend' => 'neutral'
            ],
            [
                'title' => 'Menunggu Verifikasi',
                'value' => $pendingAchievements ?? 0,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'trend' => 'neutral'
            ]
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        @foreach ($stats as $stat)
            <div class="bg-white rounded-lg shadow-custom overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            {!! $stat['icon'] !!}
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ $stat['title'] }}</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">{{ $stat['value'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($pendingAchievements > 0)
    <div class="p-4 bg-amber-50 border-l-4 border-amber-500 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-amber-700">
                    <span class="font-semibold">Perhatian:</span> Anda memiliki {{ $pendingAchievements }} prestasi yang sedang menunggu verifikasi. Admin akan segera memverifikasi prestasi yang telah Anda ajukan.
                </p>
            </div>
        </div>
    </div>
    @endif

    @if (session('status'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div id="achievements-table-container">
        @component('student.achievements.components.tables')
        @slot('achievements', $achievements ?? collect())
        @endcomponent
    </div>

    <div id="pagination-container">
        @component('admin.components.tables.pagination', ['data' => $achievements ?? collect()])
        @endcomponent
    </div>
</div>

<!-- Include modals -->
@include('student.achievements.components.create-achievement-modal')
@include('student.achievements.components.show-achievement-modal')
@include('student.achievements.components.edit-achievement-modal')
@include('student.achievements.components.delete-achievement-modal')

<!-- JavaScript Variables and Setup -->
<script>
    window.achievementRoutes = {
        index: "{{ route('student.achievements.index') }}"
    };
    window.csrfToken = "{{ csrf_token() }}";
</script>

<!-- Include JavaScript for AJAX functionality -->
@php
    try {
        $manifestExists = file_exists(public_path('build/manifest.json'));
        $jsFileExists = file_exists(resource_path('js/student/achievements.js'));
    } catch (\Exception $e) {
        $manifestExists = false;
        $jsFileExists = false;
    }
@endphp

@if($manifestExists && $jsFileExists)
    @vite(['resources/js/student/achievements.js'])
@else
    <!-- Fallback for non-Vite environments or when file is missing -->
    <script src="{{ asset('js/student/achievements.js') }}"></script>
@endif
@endcomponent

@endsection
