<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <title>@yield('title', 'Food & Drink')</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50">

    @php
        // Logika untuk menentukan apakah splash screen perlu ditampilkan
        $isTrueHomepage = Route::is('home') && !request()->has('category') && !request()->has('q');
        $showSplash = $isTrueHomepage && !session()->has('has_seen_splash');
        if ($showSplash) {
            session(['has_seen_splash' => true]);
        }
    @endphp

    {{-- Splash Screen (hanya di homepage) --}}
    @if ($isTrueHomepage)
        <div x-data="{ splashing: {{ $showSplash ? 'true' : 'false' }} }" x-init="setTimeout(() => splashing = false, 2000)">
            <div x-show="splashing" x-cloak x-transition:leave="transition-opacity ease-in-out duration-1000"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-50 z-50">
            </div>
            <div x-cloak
                :class="splashing ? 'z-[60] fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2' :
                    'z-40 fixed top-6 left-6 h-8'"
                class="transition-all duration-[1200ms] ease-[cubic-bezier(0.25,1,0.5,1)]">
                <img src="{{ asset('images/logo.png') }}" alt="Food Logo" :class="splashing ? 'h-20' : 'h-full'"
                    class="transition-all duration-1000 ease-in-out">
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- FIX: Script dipindahkan ke akhir <body> untuk menghindari error inisialisasi --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

</html>
