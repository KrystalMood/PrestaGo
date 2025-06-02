@extends('components.shared.content')

@section('content')

@component('layouts.app', ['title' => $subCompetition->name])

@php
    // Set theme color based on participation status
    $themeColor = 'indigo'; // default
    
    if (Auth::check() && $isParticipating) {
        $participant = $subCompetition->participants()->where('user_id', Auth::user()->id)->first();
        $participantStatus = $participant ? $participant->status : 'pending';
        
        if ($participantStatus == 'registered') {
            $themeColor = 'green';
        } else if ($participantStatus == 'pending') {
            $themeColor = 'yellow';
        }
    }
@endphp

<div class="bg-white rounded-lg shadow-custom overflow-hidden">
    <!-- Page Header -->
    <div class="border-b border-gray-200 bg-white px-6 py-5">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $subCompetition->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Bagian dari: {{ $competition->name }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('student.competitions.show', $competition->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                
                @if(!$isParticipating)
                    @php
                        $now = \Carbon\Carbon::now();
                        $canRegister = false;
                        
                        if ($subCompetition->registration_start && $subCompetition->registration_end) {
                            $startDate = \Carbon\Carbon::parse($subCompetition->registration_start);
                            $endDate = \Carbon\Carbon::parse($subCompetition->registration_end);
                            
                            if ($now->gte($startDate) && $now->lte($endDate)) {
                                $canRegister = true;
                            }
                        }
                    @endphp
                    
                    @if($canRegister)
                        <a href="{{ route('student.competitions.sub.apply', [$competition->id, $subCompetition->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-{{ $themeColor }}-600 hover:bg-{{ $themeColor }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $themeColor }}-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Daftar Sekarang
                        </a>
                    @endif
                @endif
            </div>
        </div>
        
        <!-- Status Badge -->
        <div class="mt-4 flex items-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($subCompetition->status == 'active') bg-emerald-100 text-emerald-800
                @elseif($subCompetition->status == 'upcoming') bg-blue-100 text-blue-800
                @else bg-red-100 text-red-800 @endif">
                {{ $subCompetition->status_indonesian }}
            </span>
            
            @if($subCompetition->category)
            <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                {{ $subCompetition->category->name }}
            </span>
            @endif
            
            <span class="ml-3 text-sm text-gray-500">
                <span class="font-medium">Batas Akhir:</span> {{ $subCompetition->registration_end ? \Carbon\Carbon::parse($subCompetition->registration_end)->format('d M Y') : 'Tidak ditentukan' }}
            </span>
        </div>
    </div>
    
    <!-- Breadcrumbs -->
    <div class="bg-white py-3 px-6 border-b border-gray-200">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('student.competitions.index') }}" class="text-gray-500 hover:text-{{ $themeColor }}-600 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="hidden md:inline">Kompetisi</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('student.competitions.show', $competition->id) }}" class="ml-1 md:ml-2 text-sm font-medium text-gray-500 hover:text-{{ $themeColor }}-600">
                            {{ $competition->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-{{ $themeColor }}-700 ml-1 md:ml-2 text-sm font-medium">{{ $subCompetition->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <!-- Registration Action -->
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        @if(!$isParticipating)
            @php
                $now = \Carbon\Carbon::now();
                $message = '';
                
                if ($subCompetition->registration_start && $subCompetition->registration_end) {
                    $startDate = \Carbon\Carbon::parse($subCompetition->registration_start);
                    $endDate = \Carbon\Carbon::parse($subCompetition->registration_end);
                    
                    if ($now->lt($startDate)) {
                        $message = 'Pendaftaran belum dibuka. Pendaftaran dimulai pada ' . $startDate->format('d M Y') . '.';
                        $alertType = 'yellow';
                    } elseif ($now->gt($endDate)) {
                        $message = 'Pendaftaran sudah ditutup pada ' . $endDate->format('d M Y') . '.';
                        $alertType = 'red';
                    } else {
                        $message = 'Pendaftaran Terbuka: Anda dapat mendaftar pada kategori kompetisi ini hingga ' . $endDate->format('d M Y') . '.';
                        $alertType = 'blue';
                    }
                } else {
                    $message = 'Periode pendaftaran belum ditentukan. Silahkan hubungi penyelenggara.';
                    $alertType = 'yellow';
                }
            @endphp
            
            <div class="p-4 bg-{{ $alertType }}-50 border-l-4 border-{{ $alertType }}-500 mb-2">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-{{ $alertType }}-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-{{ $alertType }}-700">
                            {{ $message }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            @php
                // Get participant status
                $participant = $subCompetition->participants()->where('user_id', Auth::user()->id)->first();
                $participantStatus = $participant ? $participant->status : 'pending';
                
                if ($participantStatus == 'registered') {
                    $statusMessage = 'Anda Telah Terdaftar: Anda sudah terdaftar pada kategori kompetisi ini.';
                    $statusBg = 'green';
                    $statusIcon = 'check';
                } else {
                    $statusMessage = 'Menunggu Verifikasi: Pendaftaran Anda sedang dalam proses verifikasi.';
                    $statusBg = 'yellow';
                    $statusIcon = 'clock';
                }
            @endphp
            
            <div class="p-4 bg-{{ $statusBg }}-50 border-l-4 border-{{ $statusBg }}-500 mb-2">
                <div class="flex">
                    <div class="flex-shrink-0">
                        @if($statusIcon == 'check')
                        <svg class="h-5 w-5 text-{{ $statusBg }}-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @else
                        <svg class="h-5 w-5 text-{{ $statusBg }}-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-{{ $statusBg }}-700">
                            <span class="font-semibold">{{ $participantStatus == 'registered' ? 'Anda Telah Terdaftar:' : 'Menunggu Verifikasi:' }}</span> 
                            {{ $participantStatus == 'registered' ? 'Anda sudah terdaftar pada kategori kompetisi ini. Pantau status pendaftaran Anda secara berkala.' : 'Pendaftaran Anda sedang dalam proses verifikasi oleh panitia.' }}
                        </p>
                    </div>
                </div>
            </div>
            
            @if($participantStatus == 'registered' || $participantStatus == 'pending')
                <div class="mt-4 p-4 bg-white border border-gray-200 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Detail Pendaftaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Nama Tim:</p>
                            <p class="font-medium">{{ $participant->team_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Dosen Pembimbing:</p>
                            <p class="font-medium">{{ $participant->mentor ? $participant->mentor->name : 'Belum ditentukan' }}</p>
                        </div>
                    </div>
                    
                    @if(!empty($participant->team_members))
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Anggota Tim</h4>
                            <div class="overflow-hidden border border-gray-200 rounded-md">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach(json_decode($participant->team_members) as $member)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-xs">{{ $member->name }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-xs">{{ $member->nim ?? 'NIM tidak tersedia' }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-xs">{{ $member->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </div>

    <!-- Sub-Competition Details -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Main Content -->
            <div class="col-span-2">
                <!-- Description -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4 flex items-center text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-{{ $themeColor }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tentang Kategori Lomba
                    </h2>
                    <div class="prose max-w-none text-gray-700">
                        <p>{{ $subCompetition->description }}</p>
                    </div>
                </div>
                
                <!-- Requirements -->
                @if($subCompetition->requirements)
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4 flex items-center text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-{{ $themeColor }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Persyaratan
                    </h2>
                    <div class="prose max-w-none text-gray-700">
                        <ul class="list-disc pl-5 space-y-2">
                            @php
                                $requirementsList = preg_split('/\r\n|\r|\n/', $subCompetition->requirements);
                            @endphp
                            
                            @foreach($requirementsList as $requirement)
                                @if(trim($requirement) !== '')
                                    <li class="text-gray-700">{{ trim($requirement) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                
                <!-- Timeline -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Timeline
                        </h2>
                    </div>
                    <div class="p-5">
                        @if($subCompetition->timeline)
                            <div class="prose max-w-none">
                                {!! $subCompetition->timeline !!}
                            </div>
                        @else
                            <p class="text-gray-500 italic">Tidak ada informasi timeline tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info Card -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Kompetisi
                        </h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <!-- Status -->
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                            <p class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($subCompetition->status == 'active') bg-emerald-100 text-emerald-800
                                    @elseif($subCompetition->status == 'upcoming') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $subCompetition->status_indonesian }}
                                </span>
                            </p>
                        </div>
                        
                        <!-- Registration Period -->
                        @if($subCompetition->registration_start && $subCompetition->registration_end)
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Periode Pendaftaran</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($subCompetition->registration_start)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($subCompetition->registration_end)->format('d M Y') }}
                            </p>
                            @php
                                $now = \Carbon\Carbon::now();
                                $startDate = \Carbon\Carbon::parse($subCompetition->registration_start);
                                $endDate = \Carbon\Carbon::parse($subCompetition->registration_end);
                                
                                if ($now->lt($startDate)) {
                                    $registrationStatus = 'Belum Dibuka';
                                    $statusColor = 'blue';
                                } elseif ($now->gt($endDate)) {
                                    $registrationStatus = 'Sudah Ditutup';
                                    $statusColor = 'red';
                                } else {
                                    $registrationStatus = 'Sedang Berlangsung';
                                    $statusColor = 'green';
                                }
                            @endphp
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                    {{ $registrationStatus }}
                                </span>
                            </p>
                        </div>
                        @else
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Periode Pendaftaran</p>
                            <p class="text-sm text-gray-600 italic">Belum ditentukan</p>
                        </div>
                        @endif
                        
                        <!-- Batas Akhir -->
                        @if($subCompetition->registration_end)
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Batas Akhir Pendaftaran</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($subCompetition->registration_end)->format('d M Y') }}
                            </p>
                        </div>
                        @endif
                        
                        <!-- Category -->
                        @if($subCompetition->category)
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Kategori</p>
                            <p class="text-sm font-semibold text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-{{ $themeColor }}-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $subCompetition->category->name }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Registration Link -->
                @if($subCompetition->registration_link)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Link Pendaftaran Eksternal
                        </h2>
                    </div>
                    <div class="p-5">
                        <a href="{{ $subCompetition->registration_link }}" target="_blank" class="text-{{ $themeColor }}-600 hover:text-{{ $themeColor }}-800 underline flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Buka Link Pendaftaran
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Required Skills -->
                @if($subCompetition->skills && $subCompetition->skills->count() > 0)
                <div class="bg-white border border-{{ $themeColor }}-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-{{ $themeColor }}-50 px-5 py-3">
                        <h2 class="text-base font-semibold text-{{ $themeColor }}-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-{{ $themeColor }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            Skill yang Dibutuhkan
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            @foreach($subCompetition->skills as $skill)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-{{ $themeColor }}-100 text-{{ $themeColor }}-500 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $skill->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $skill->category }}</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $themeColor }}-100 text-{{ $themeColor }}-800">
                                        Level {{ $skill->pivot->importance_level }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endcomponent
@endsection 