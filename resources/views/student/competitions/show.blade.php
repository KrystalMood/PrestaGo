@extends('components.shared.content')

@section('content')

@component('layouts.app', ['title' => $competition->name])

<div class="bg-white rounded-lg shadow-custom overflow-hidden">
    <!-- Page Header -->
    <div class="border-b border-gray-200 bg-white px-6 py-5">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $competition->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ $competition->organizer }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('student.competitions.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
        
        <!-- Status Badge -->
        <div class="mt-4 flex items-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($competition->status == 'open') bg-emerald-100 text-emerald-800
                @elseif($competition->status == 'upcoming') bg-blue-100 text-blue-800
                @else bg-red-100 text-red-800 @endif">
                {{ $competition->status_indonesian }}
            </span>
            
            <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($competition->level == 'international') bg-red-100 text-red-800
                @elseif($competition->level == 'national') bg-orange-100 text-orange-800
                @elseif($competition->level == 'provincial') bg-yellow-100 text-yellow-800
                @elseif($competition->level == 'regional') bg-green-100 text-green-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $competition->level_formatted }}
            </span>
            
            <span class="ml-3 text-sm text-gray-500">
                <span class="font-medium">Batas Akhir:</span> {{ $competition->registration_end ? \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') : 'Tidak ditentukan' }}
            </span>
        </div>
    </div>
    
    <!-- Breadcrumbs -->
    <div class="bg-white py-3 px-6 border-b border-gray-200">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('student.competitions.index') }}" class="text-gray-500 hover:text-indigo-600 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>Kompetisi</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-indigo-700 ml-1 md:ml-2 text-sm font-medium">{{ $competition->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Competition Info Cards -->
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Card 1: Competition Details -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <div class="flex items-center mb-3">
                    <div class="rounded-full bg-indigo-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700">Tingkat Kompetisi</h3>
                </div>
                <p class="text-sm text-gray-600">{{ $competition->level_formatted }}</p>
            </div>
            
            <!-- Card 2: Registration Period -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <div class="flex items-center mb-3">
                    <div class="rounded-full bg-blue-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700">Periode Pendaftaran</h3>
                </div>
                <p class="text-sm text-gray-600">
                    {{ $competition->registration_start ? \Carbon\Carbon::parse($competition->registration_start)->format('d M') : '' }} - {{ $competition->registration_end ? \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') : 'Tidak ditentukan' }}
                </p>
            </div>
            
            <!-- Card 3: Competition Date -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <div class="flex items-center mb-3">
                    <div class="rounded-full bg-purple-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700">Tanggal Kompetisi</h3>
                </div>
                <p class="text-sm text-gray-600">
                    {{ $competition->start_date ? \Carbon\Carbon::parse($competition->start_date)->format('d M') : '' }} - {{ $competition->end_date ? \Carbon\Carbon::parse($competition->end_date)->format('d M Y') : 'Tidak ditentukan' }}
                </p>
            </div>
            
            <!-- Card 4: Categories Count -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <div class="flex items-center mb-3">
                    <div class="rounded-full bg-emerald-100 p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700">Kategori</h3>
                </div>
                <p class="text-sm text-gray-600">
                    {{ $competition->subCompetitions ? $competition->subCompetitions->count() : 0 }} kategori
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="col-span-2">
                <div class="bg-white rounded-lg shadow-custom p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Deskripsi Kompetisi
                    </h2>
                    <div class="prose max-w-none text-gray-700">
                        {{ $competition->description }}
                    </div>
                    
                    @if($competition->registration_link)
                    <div class="mt-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                        <h3 class="text-lg font-semibold mb-2 text-indigo-800">Link Registrasi Eksternal</h3>
                        <a href="{{ $competition->registration_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            {{ $competition->registration_link }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        
        <div>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Informasi</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tingkat</h3>
                        <p class="mt-1">{{ $competition->level_formatted }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($competition->status == 'active') 
                                    bg-green-100 text-green-800
                                @elseif($competition->status == 'upcoming')
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                {{ $competition->status_indonesian }}
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Periode Pendaftaran</h3>
                        <p class="mt-1">
                            @if($competition->registration_start && $competition->registration_end)
                                {{ \Carbon\Carbon::parse($competition->registration_start)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') }}
                            @else
                                Tidak ditentukan
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tanggal Kompetisi</h3>
                        <p class="mt-1">
                            @if($competition->competition_date)
                                {{ \Carbon\Carbon::parse($competition->competition_date)->format('d M Y') }}
                            @else
                                Tidak ditentukan
                            @endif
                        </p>
                    </div>
                    
                    <!-- Skills section removed as skills are now managed at sub-competition level -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sub Competitions Section -->
    @if($competition->subCompetitions && $competition->subCompetitions->count() > 0)
    <div class="mt-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold flex items-center text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Kategori Kompetisi
                <span class="ml-2 bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $competition->subCompetitions->count() }}</span>
            </h2>
        </div>
        
        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <span class="font-semibold">Perhatian:</span> Pendaftaran dilakukan pada kategori lomba spesifik dan hanya tersedia selama periode pendaftaran yang ditentukan (dari tanggal mulai hingga tanggal selesai pendaftaran). Silakan pilih salah satu kategori lomba di bawah ini untuk melihat detail dan mendaftar.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-custom overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="categories-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Kategori
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Batas Akhir
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Pendaftaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($competition->subCompetitions as $subCompetition)
                        @php
                            // Skip completed sub-competitions
                            if($subCompetition->status == 'completed') continue;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors category-row" data-category-id="{{ $subCompetition->id }}">
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md font-medium">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $subCompetition->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $subCompetition->category->name ?? 'Tidak ditentukan' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $subCompetition->registration_end ? \Carbon\Carbon::parse($subCompetition->registration_end)->format('d M Y') : 'Tidak ditentukan' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $regStatus = 'Tidak ditentukan';
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    
                                    if ($subCompetition->registration_start && $subCompetition->registration_end) {
                                        $startDate = \Carbon\Carbon::parse($subCompetition->registration_start);
                                        $endDate = \Carbon\Carbon::parse($subCompetition->registration_end);
                                        
                                        if ($now->lt($startDate)) {
                                            $regStatus = 'Belum Dibuka';
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                        } elseif ($now->gt($endDate)) {
                                            $regStatus = 'Sudah Ditutup';
                                            $statusClass = 'bg-red-100 text-red-800';
                                        } else {
                                            $regStatus = 'Dibuka';
                                            $statusClass = 'bg-green-100 text-green-800';
                                        }
                                    }
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $regStatus }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    @php
                                        $isRegistered = false;
                                        if(auth()->check()) {
                                            $isRegistered = $subCompetition->participants()->where('user_id', auth()->user()->id)->exists();
                                        }
                                    @endphp
                                    
                                    @if($isRegistered)
                                        @php
                                            $participant = $subCompetition->participants()->where('user_id', auth()->user()->id)->first();
                                            $participantStatus = $participant ? $participant->status : 'pending';
                                            
                                            if ($participantStatus == 'registered') {
                                                $statusColor = 'green';
                                                $statusText = 'Terdaftar';
                                                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                                            } else {
                                                $statusColor = 'yellow';
                                                $statusText = 'Menunggu';
                                                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 text-xs rounded-md mr-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                {!! $statusIcon !!}
                                            </svg>
                                            {{ $statusText }}
                                        </span>
                                    @endif
                                    
                                    <a href="{{ route('student.competitions.sub.show', [$competition->id, $subCompetition->id]) }}" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-yellow-800">Belum ada kategori lomba!</h3>
                <p class="text-yellow-700 mt-1">Kompetisi ini belum memiliki kategori lomba yang dapat diikuti. Silakan periksa kembali nanti.</p>
            </div>
        </div>
    </div>
    @endif
</div>

@endcomponent
@endsection 