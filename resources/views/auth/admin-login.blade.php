@extends('layouts.app')
@section('title', 'Admin Login')

@section('content')
    <div class="min-h-screen lg:grid lg:grid-cols-2">
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
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="username" class="sr-only">Username</label>
                            <input id="username" name="username" type="text" autocomplete="username" required
                                class="relative block w-full appearance-none rounded-t-md border border-gray-300 px-3 py-3 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm"
                                placeholder="Username" value="{{ old('username') }}">
                        </div>
                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="relative block w-full appearance-none rounded-b-md border border-gray-300 px-3 py-3 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-orange-500 focus:outline-none focus:ring-orange-500 sm:text-sm"
                                placeholder="Password">
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
