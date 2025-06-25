@extends('layouts.app', ['title' => 'Admin Login'])

@section('content')
    <div class="min-h-screen lg:grid lg:grid-cols-2">
        {{-- Kolom Kiri: Gambar Latar --}}
        <div class="hidden lg:block relative h-full">
            <img class="absolute inset-0 h-full w-full object-cover" src="{{ asset('images/burger-drink.jpg') }}"
                alt="Burger and Drink Background">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-12">
                <h2 class="text-4xl font-extrabold text-white">Kelola Restoran Anda.</h2>
                <p class="mt-2 text-white/80 text-lg">Masuk untuk mengatur menu, melihat pesanan, dan memantau bisnis Anda.
                </p>
            </div>
        </div>

        {{-- Kolom Kanan: Form Login --}}
        <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
            <div class="w-full max-w-md space-y-8">
                <div>
                    <img class="mx-auto h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="FoodApp Logo">
                    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                        Admin Dashboard Login
                    </h2>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                        <p class="font-bold">Login Gagal</p>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <form class="mt-8 space-y-6" action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    {{-- Input fields container --}}
                    <div>
                        {{-- Input Username --}}
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input id="username" name="username" type="text" autocomplete="username" required
                                class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-3 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm"
                                placeholder="Username" value="{{ old('username') }}">
                        </div>

                        {{-- FIX: Input Password dengan Tombol Show/Hide --}}
                        <div class="mt-6" x-data="{ show: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                {{-- Input type di-binding ke state 'show' --}}
                                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                    class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-3 pr-10 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm"
                                    placeholder="Password">

                                {{-- Tombol untuk toggle password --}}
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-500 hover:text-orange-500">
                                    {{-- Ikon mata terbuka (familiar) --}}
                                    <template x-if="show">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </template>
                                    {{-- Ikon mata tertutup (familiar) --}}
                                    <template x-if="!show">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative flex w-full justify-center rounded-md border border-transparent bg-orange-500 py-3 px-4 text-sm font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-orange-300 group-hover:text-orange-200"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
