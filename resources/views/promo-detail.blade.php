@extends('layouts.app')

@section('title', $promotion->title)

@section('content')
    {{-- x-data mengelola state untuk loading & notifikasi --}}
    <div class="font-sans" x-data="{
        loading: false,
        notification: { show: false, message: '', success: true },
        showNotification(message, success = true) {
            this.notification.message = message;
            this.notification.success = success;
            this.notification.show = true;
            setTimeout(() => { this.notification.show = false }, 4000); // Sembunyikan setelah 4 detik
        }
    }">

        {{-- Notifikasi Toast (Sama seperti di homepage Anda) --}}
        <div x-show="notification.show" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform translate-x-full opacity-0"
            x-transition:enter-end="transform translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-x-0 opacity-100"
            x-transition:leave-end="transform translate-x-full opacity-0" class="fixed top-24 right-6 text-sm p-0 z-50">
            <div class="p-3 rounded-lg shadow-lg border"
                :class="notification.success ? 'bg-green-100 border-green-400 text-green-700' :
                    'bg-red-100 border-red-400 text-red-700'">
                <p x-text="notification.message"></p>
            </div>
        </div>

        {{-- Overlay Loading --}}
        <div x-show="loading" x-cloak class="fixed inset-0 bg-white/70 z-50 flex items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-orange-500 border-t-transparent"></div>
        </div>

        @include('partials.header')

        @php
            $promoData = is_string($promotion->promo_data)
                ? json_decode($promotion->promo_data)
                : $promotion->promo_data;
            $packagePrice = $promoData->package_price ?? 0;
            $originalTotalPrice = $promotion->products->sum('price');
        @endphp

        {{-- Form sekarang dikontrol oleh Alpine.js --}}
        <form action="{{ route('cart.addBundle', $promotion) }}" method="POST"
            @submit.prevent="
            loading = true;
            fetch($el.action, { method: 'POST', body: new FormData($el) })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                        showNotification(data.message, true); 
                    } else {
                        showNotification(data.message || 'Gagal menambahkan paket.', false);
                    }
                })
                .catch(err => {
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', false);
                    console.error(err);
                })
                .finally(() => loading = false)
          ">
            @csrf
            <div class="pb-40 lg:pb-0">
                <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:max-w-6xl lg:mx-auto lg:py-12">
                    {{-- Kolom Kiri: Gambar --}}
                    <div
                        class="h-80 lg:h-auto lg:aspect-square bg-slate-100 flex items-center justify-center lg:rounded-2xl">
                        <img src="{{ asset('storage/' . $promotion->image_path) }}" alt="{{ $promotion->title }}"
                            class="w-full h-full object-contain p-4">
                    </div>
                    {{-- Kolom Kanan: Info & Aksi --}}
                    <div class="p-6 lg:p-0 flex flex-col">
                        <div class="grow">
                            <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mt-4 lg:mt-0">{{ $promotion->title }}
                            </h2>
                            <p class="text-slate-500 text-base lg:text-lg mt-2">{{ $promotion->description }}</p>
                            <div class="mt-8">
                                <h3 class="text-lg font-bold text-slate-800">Isi Paket Ini:</h3>
                                <div class="mt-2 space-y-3">
                                    @foreach ($promotion->products as $product)
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border">
                                            <div class="flex items-center space-x-4">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-12 h-12 object-contain rounded-md border p-1 bg-white">
                                                <p class="font-semibold text-slate-700">{{ $product->name }}</p>
                                            </div>
                                            <p class="text-sm text-slate-500">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- Tombol Aksi Desktop --}}
                        <div class="hidden lg:flex flex-col space-y-4 mt-8 pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-slate-500">Harga Paket</p>
                                    <span
                                        class="text-3xl font-extrabold text-orange-600">Rp{{ number_format($packagePrice, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500 line-through">
                                        Rp{{ number_format($originalTotalPrice, 0, ',', '.') }}</p>
                                    <span class="text-green-600 font-semibold">Anda Hemat!</span>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow hover:bg-orange-600 transition-colors">Tambah
                                ke Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Action Bar Mobile --}}
            <div
                class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-sm border-t p-4 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)]">
                <div class="w-full">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-sm text-slate-500">Harga Paket</p>
                            <span
                                class="text-xl font-bold text-slate-900">Rp{{ number_format($packagePrice, 0, ',', '.') }}</span>
                        </div>
                        <span
                            class="text-base text-slate-500 line-through">Rp{{ number_format($originalTotalPrice, 0, ',', '.') }}</span>
                    </div>
                    <button type="submit"
                        class="w-full bg-orange-500 text-white font-bold py-3 text-center rounded-lg shadow hover:bg-orange-600">Tambah
                        ke Keranjang</button>
                </div>
            </div>
        </form>
    </div>
@endsection
