@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    {{-- FIX: Logika Alpine.js di-upgrade untuk auto-save --}}
    <div class="font-sans" x-data="{
        customer_name: '{{ old('customer_name', session('customer_name', '')) }}',
        paymentModalOpen: false,
        debounce: null,
    
        // Fungsi init akan dijalankan saat komponen dimuat
        init() {
            // $watch akan memantau perubahan pada properti 'customer_name'
            this.$watch('customer_name', (value) => {
                // Hapus timer sebelumnya agar tidak menumpuk
                clearTimeout(this.debounce);
    
                // Atur timer baru. Fungsi akan dijalankan setelah 500ms pengguna berhenti mengetik.
                this.debounce = setTimeout(() => {
                    fetch('{{ route('cart.setCustomerName') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ customer_name: value })
                    });
                }, 500);
            });
        }
    }" x-init="init()">
        @include('partials.header', ['backUrl' => route('home')])

        <div class="container mx-auto px-4 sm:px-6 py-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-6">Keranjang Anda</h1>

            @if (count($cartItems) > 0)
                {{-- Daftar Item --}}
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-8">
                    <div class="space-y-6">
                        {{-- Ganti seluruh blok @foreach Anda dengan ini --}}
                        @foreach ($cartItems as $id => $item)
                            <div class="flex items-center space-x-4">
                                {{-- Gambar --}}
                                <div
                                    class="w-20 h-20 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['name'] }}"
                                        class="w-full h-full object-contain p-1">
                                </div>

                                {{-- Nama & Harga --}}
                                <div class="flex-grow">
                                    <p class="font-bold text-lg text-slate-800">{{ $item['name'] }}</p>

                                    @if (isset($item['is_bundle']) && $item['is_bundle'])
                                        <p class="text-orange-600 font-bold">
                                            Rp{{ number_format($item['price'], 0, ',', '.') }}
                                        </p>
                                        {{-- Daftar produk di dalam paket --}}
                                        <ul class="text-xs text-slate-500 list-disc list-inside mt-1">
                                            {{-- FIX: $subItem sekarang adalah string, jadi tampilkan langsung --}}
                                            @foreach ($item['items'] as $subItem)
                                                <li>{{ $subItem }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-slate-500">
                                            Rp{{ number_format($item['price'], 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Logika Tombol Kuantitas --}}
                                @if (isset($item['is_bundle']) && $item['is_bundle'])
                                    {{-- JIKA BUNDEL: Tampilkan kuantitas statis "1 Paket" dan hanya tombol hapus --}}
                                    <div class="flex items-center space-x-4 ml-auto">
                                        <span class="w-24 text-center font-bold text-lg text-slate-700">1 Paket</span>
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit"
                                                class="text-slate-400 hover:text-red-500 p-2 transition-colors">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    {{-- JIKA BUKAN BUNDEL: Tampilkan kontrol kuantitas seperti biasa --}}
                                    <div class="flex items-center space-x-2 ml-auto">
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                            <button type="submit"
                                                class="bg-slate-200 rounded-full w-8 h-8 flex items-center justify-center hover:bg-slate-300"
                                                {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                                </svg>
                                            </button>
                                        </form>
                                        <span class="w-10 text-center font-bold text-lg">{{ $item['quantity'] }}</span>
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                            <button type="submit"
                                                class="bg-slate-200 rounded-full w-8 h-8 flex items-center justify-center hover:bg-slate-300">
                                                <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6v12m6-6H6" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="m-0 pl-2">
                                            @csrf
                                            <button type="submit"
                                                class="text-slate-400 hover:text-red-500 p-2 transition-colors">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            @if (!$loop->last)
                                <hr class="my-4">
                            @endif
                        @endforeach

                    </div>
                </div>

                {{-- Hitung subtotal --}}
                @php
                    $subtotal = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
                @endphp

                {{-- Form Checkout --}}
                <div x-data="{ paymentModalOpen: false }">
                    <form id="main-cart-form" action="{{ route('order.store') }}" method="POST">
                        @csrf
                        {{-- Nama Pemesan --}}
                        <div class="mb-6">
                            <label for="customer_name" class="block text-lg font-bold text-slate-800 mb-2">Nama
                                Pemesan</label>
                            <input x-model="customer_name" type="text" id="customer_name" name="customer_name"
                                class="w-full bg-white rounded-lg py-3 px-4 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Masukkan nama Anda..." required>
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rincian Harga --}}
                        <div class="mt-4 pt-4 border-t-2 border-dashed">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-lg text-slate-600">Subtotal</span>
                                <span class="text-lg font-semibold text-slate-800">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg text-slate-600">PPN (11%)</span>
                                <span class="text-lg font-semibold text-slate-800">
                                    Rp{{ number_format($subtotal * 0.11, 0, ',', '.') }}
                                </span>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-slate-800">Total</span>
                                <span class="text-2xl font-extrabold text-slate-900">
                                    Rp{{ number_format($subtotal * 1.11, 0, ',', '.') }}
                                </span>
                            </div>

                            <button type="button" @click.prevent="paymentModalOpen = true"
                                class="mt-6 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-4 rounded-xl shadow-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-200">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Pilih Metode Pembayaran
                            </button>
                        </div>

                        {{-- MODAL PEMILIHAN METODE PEMBAYARAN (UI DIPERBAIKI) --}}
                        <div x-show="paymentModalOpen" x-cloak
                            class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">

                            <div @click.outside="paymentModalOpen = false" x-show="paymentModalOpen"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">

                                {{-- Header Modal --}}
                                <div class="bg-orange-500 p-4 text-white">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-bold">Pilih Metode Pembayaran</h3>
                                        <button type="button" @click="paymentModalOpen = false"
                                            class="p-1 rounded-full hover:bg-white/20 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Content Modal --}}
                                <div class="p-4 space-y-3">
                                    {{-- Opsi Bayar di Kasir --}}
                                    <button type="submit" form="main-cart-form" name="payment_method" value="cashier"
                                        class="w-full group bg-white border border-slate-200 rounded-lg p-4 hover:border-orange-500 hover:bg-orange-50 transition-all">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 bg-blue-500 rounded-lg mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <p class="font-bold text-slate-800">Bayar di Kasir</p>
                                                <p class="text-sm text-slate-500">Tunjukkan ID Pesanan di kasir</p>
                                            </div>
                                            <div class="text-orange-500">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </button>

                                    {{-- Opsi E-Wallet (QRIS) --}}
                                    <button type="submit" form="main-cart-form" name="payment_method" value="ewallet"
                                        class="w-full group bg-white border border-slate-200 rounded-lg p-4 hover:border-orange-500 hover:bg-orange-50 transition-all">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 bg-green-500 rounded-lg mr-3">
                                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                    <path
                                                        d="M3 11h8V3H3v8zm2-6h4v4H5V5zM3 21h8v-8H3v8zm2-6h4v4H5v-4zM13 3v8h8V3h-8zm6 6h-4V5h4v4zM19 13h2v2h-2zM13 13h2v2h-2zM15 15h2v2h-2zM13 17h2v2h-2zM15 19h2v2h-2zM17 17h2v2h-2zM17 13h2v2h-2zM19 15h2v2h-2zM19 19h2v2h-2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <p class="font-bold text-slate-800">E-Wallet (QRIS)</p>
                                                <p class="text-sm text-slate-500">GoPay, OVO, Dana, ShopeePay</p>
                                            </div>
                                            <div class="text-orange-500">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </button>

                                    {{-- Opsi E-Payment (Bank/Kartu) --}}
                                    <button type="submit" form="main-cart-form" name="payment_method" value="epayment"
                                        class="w-full group bg-white border border-slate-200 rounded-lg p-4 hover:border-orange-500 hover:bg-orange-50 transition-all">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 bg-purple-500 rounded-lg mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <p class="font-bold text-slate-800">E-Payment (Bank/Kartu)</p>
                                                <p class="text-sm text-slate-500">Virtual Account & Kartu Kredit/Debit</p>
                                            </div>
                                            <div class="text-orange-500">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                {{-- Keranjang Kosong --}}
                <div class="text-center py-20">
                    <svg class="mx-auto h-24 w-24 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .962-.343 1.087-.835l1.824-6.842a.5.5 0 00-.468-.664H6.5" />
                    </svg>
                    <h3 class="mt-2 text-xl font-semibold text-slate-800">Keranjang Anda Kosong</h3>
                    <p class="mt-1 text-base text-slate-500">Ayo, pilih menu favoritmu sekarang!</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-orange-600">
                            Kembali ke Menu
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Tambahkan CSS untuk animasi tambahan --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
