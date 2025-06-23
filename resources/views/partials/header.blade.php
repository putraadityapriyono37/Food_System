{{-- File: resources/views/partials/header.blade.php --}}

@php
    $isTrueHomepage = Route::is('home') && !request()->has('category') && !request()->has('q');
    $isDanger = session('danger') ? true : false;
    $bgColor = $isDanger ? 'bg-red-100' : 'bg-green-100';
    $borderColor = $isDanger ? 'border-red-400' : 'border-green-400';
    $textColor = $isDanger ? 'text-red-700' : 'text-green-700';
    $message = session('danger') ?? session('success');
@endphp

<header class="bg-slate-50/80 backdrop-blur-lg sticky top-0 z-30">
    @if ($isTrueHomepage)
        {{-- Homepage: ikon keranjang di kanan --}}
        {{-- Kita juga perlu menambahkan listener di sini jika ingin update dari homepage --}}
        <div class="container mx-auto flex justify-end items-center px-6" style="height:80px;" x-data="{ cartCount: {{ count((array) session('cart')) }} }"
            @cart-updated.window="cartCount = $event.detail.count">
            <a href="{{ route('cart.index') }}" class="relative transform hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-800" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                {{-- Menggunakan x-show dan x-text agar dinamis --}}
                <template x-if="cartCount > 0">
                    <span x-show="cartCount > 0" x-text="cartCount" x-cloak
                        class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                    </span>
                </template>
            </a>

            {{-- Flash message (tidak ada perubahan) --}}
            @if ($message)
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                    class="absolute top-full right-6 mt-2 max-w-xs p-0">
                    <div
                        class="p-3 rounded-lg shadow-lg border {{ $bgColor }} {{ $borderColor }} {{ $textColor }}">
                        <p class="text-sm">{{ $message }}</p>
                    </div>
                </div>
            @endif
        </div>
    @else
        {{-- Halaman lain: back, logo, dan ikon keranjang --}}
        {{-- FIX: Event listener ditambahkan di sini --}}
        <div class="container mx-auto flex justify-between items-center px-4 py-4 border-b border-slate-200"
            x-data="{ cartCount: {{ count((array) session('cart')) }} }" @cart-updated.window="cartCount = $event.detail.count">

            {{-- Tombol kembali --}}
            <a href="{{ $backUrl ?? route('home') }}" class="p-2 rounded-full hover:bg-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-700" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            {{-- Logo --}}
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Food Logo" class="h-8">
            </a>

            {{-- Ikon keranjang --}}
            <a href="{{ route('cart.index') }}" class="relative p-2 transform hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-800" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                {{-- Menggunakan x-show dan x-text agar dinamis --}}
                <template x-if="cartCount > 0">
                    <span x-show="cartCount > 0" x-text="cartCount" x-cloak
                        class="absolute top-0 right-0 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                    </span>
                </template>
            </a>

            {{-- Flash message (tidak ada perubahan) --}}
            @if ($message)
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="absolute top-full right-4 mt-2 max-w-xs p-0">
                    <div
                        class="p-3 rounded-lg shadow-lg border {{ $bgColor }} {{ $borderColor }} {{ $textColor }}">
                        <p class="text-sm">{{ $message }}</p>
                    </div>
                </div>
            @endif
        </div>
    @endif
</header>

@if (session('order_type'))
    <div class="bg-orange-500 text-white text-sm font-semibold py-2">
        <div class="container mx-auto px-4 text-center">
            <span>
                Tipe Pesanan:
                <span class="font-bold capitalize">
                    {{ str_replace('_', ' ', session('order_type')) }}
                    @if (session('table_id') && session('order_type') == 'dine_in')
                        - Meja {{ App\Models\Table::find(session('table_id'))->name ?? '' }}
                    @endif
                </span>
            </span>
            <a href="{{ route('home') }}#order-options"
                class="ml-4 font-bold underline hover:text-orange-200">(Ubah)</a>
        </div>
    </div>
@endif
