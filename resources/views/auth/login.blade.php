@extends('layouts.auth')

@section('content')
    <h2 class="text-xl font-bold text-center text-gray-800 mb-3">Selamat Datang di PrestaGo</h2>
    <p class="text-center text-gray-500 mb-4 text-sm">Masukkan kredensial Anda untuk mengakses portal prestasi mahasiswa Polinema</p>

    <form method="POST" action="{{ url('/login') }}" class="w-full max-w-sm mx-auto">
        @csrf

        <x-ui.input 
            type="email" 
            name="email" 
            :value="old('email')" 
            label="Email Institusi"
            placeholder="nama@polinema.ac.id"
            required
            autofocus
            autocomplete="username"
            :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'>
                <path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'
                    d=\'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207\'>
                </path>
            </svg>'"
        />

        <div class="mt-3">
            <x-ui.input 
                type="password" 
                name="password" 
                label="Kata Sandi"
                placeholder="••••••••"
                required
                autocomplete="current-password"
                :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'>
                    <path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'
                        d=\'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z\'>
                    </path>
                </svg>'"
            />
        </div>

        <div class="form-control mt-3">
            <label class="cursor-pointer label justify-start py-1">
            <input id="remember_me" type="checkbox" name="remember"
                class="checkbox checkbox-xs checkbox-primary mr-2 rounded-md" />
            <span class="label-text text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="mt-4">
            <x-ui.button type="submit" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
                Masuk
            </x-ui.button>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between mt-4 space-y-2 sm:space-y-0">
            <a class="text-xs text-brand-light hover:underline" href="">
                {{ __('Lupa Kata Sandi?') }}
            </a>

            @if (Route::has('register'))
                <span class="text-xs text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-brand-light hover:underline font-medium">
                        Daftar
                    </a>
                </span>
            @endif
        </div>
    </form>
@endsection
