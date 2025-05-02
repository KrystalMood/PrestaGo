@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <h2 class="text-xl font-bold text-center text-gray-800 mb-3">Selamat Datang di PrestaGo</h2>
    <p class="text-center text-gray-500 mb-4 text-sm">Daftarkan diri Anda untuk mengakses portal prestasi mahasiswa Polinema</p>

    <form method="POST" action="{{ route('register') }}" class="w-full max-w-sm mx-auto">
        @csrf

        <x-ui.input 
            type="text" 
            name="name" 
            :value="old('name')" 
            label="Nama Lengkap"
            placeholder="Masukkan nama lengkap Anda"
            required
            autofocus
            autocomplete="name"
            :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'>
                <path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'
                    d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'>
                </path>
            </svg>'"
        />

        <div class="mt-3">
            <x-ui.input 
                type="email" 
                name="email" 
                :value="old('email')" 
                label="Email Institusi"
                placeholder="nama@polinema.ac.id"
                required
                autocomplete="username"
                :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'>
                    <path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'
                        d=\'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207\'>
                    </path>
                </svg>'"
            />
        </div>

        <div class="mt-3">
            <x-ui.input 
                type="password" 
                name="password" 
                label="Kata Sandi"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'>
                    <path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'
                        d=\'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z\'>
                    </path>
                </svg>'"
            />
        </div>

        <div class="mt-3">
            <x-ui.input 
                type="password" 
                name="password_confirmation" 
                label="Konfirmasi Kata Sandi"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'>
                    <path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'
                        d=\'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z\'>
                    </path>
                </svg>'"
            />
        </div>

        <div class="mt-4">
            <x-ui.button type="submit" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                    </path>
                </svg>
                Daftar
            </x-ui.button>
        </div>

        <div class="flex items-center justify-center mt-4">
            <span class="text-xs text-gray-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-brand-light hover:underline font-medium">
                    Masuk
                </a>
            </span>
        </div>
    </form>
@endsection
