@extends('layouts.auth')

@section('content')
    <h2 class="text-xl font-bold text-center text-gray-800 mb-3">Lupa Kata Sandi</h2>
    <p class="text-center text-gray-500 mb-4 text-sm">Masukkan email institusi Anda untuk menerima tautan pengaturan ulang kata sandi</p>

    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="" class="w-full max-w-sm mx-auto">
        @csrf

        <x-ui.input 
            type="email" 
            name="email" 
            :value="old('email')" 
            label="Email Institusi"
            placeholder="nama@polinema.ac.id"
            required
            autofocus
            autocomplete="email"
            :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'>
                <path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'
                    d=\'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207\'>
                </path>
            </svg>'"
        />

        <div class="mt-4">
            <x-ui.button type="submit" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8">
                    </path>
                </svg>
                Kirim Tautan Reset Kata Sandi
            </x-ui.button>
        </div>

        <div class="flex items-center justify-center mt-4">
            <a class="text-xs text-brand-light hover:underline" href="{{ route('login') }}">
                {{ __('Kembali ke halaman login') }}
            </a>
        </div>
    </form>
@endsection
