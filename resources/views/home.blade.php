@extends('layouts.app')
@section('title', 'Food - Menu')
@php
    date_default_timezone_set('Asia/Jakarta');
    $hour = date('H');
    if ($hour < 12) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }
    // Delay untuk animasi logo: instan jika ada filter/search, atau lebih cepat untuk load pertama
    $delay = request()->has('category') || request()->has('q') ? 0 : 800;

    $marqueeTexts = [
        'SENSASI BARU SETIAP GIGITAN',
        'PROMO SPESIAL HARI INI',
        'KELEZATAN YANG BIKIN KANGEN',
        'CITA RASA TAK TERTANDINGI',
    ];
@endphp
@section('content')
    <div x-data="{
        searchOpen: false,
        showContent: false,
        modalOpen: false,
        selectedProduct: null,
        modalQuantity: 1,
        modalSelectedVariant: null,
        loading: false,
        cartCount: {{ count((array) session('cart')) }},
        recentSearches: [],
        isOrderOptionSet: {{ $isOrderOptionSet ? 'true' : 'false' }},
    
        // Logika notifikasi sekarang berada di sini
        notification: { show: false, message: '', type: 'success' },
        showNotification(message, type = 'success') {
            this.notification.message = message;
            this.notification.type = type;
            this.notification.show = true;
            setTimeout(() => { this.notification.show = false }, 4000);
        },
    
        // Fungsi pengecekan memanggil notifikasi lokal
        checkAndFollowLink(event) {
            if (!this.isOrderOptionSet) {
                event.preventDefault();
                this.showNotification('Pilih Tipe Pesanan Terlebih Dahulu', 'error');
                this.searchOpen = false; // Tutup overlay pencarian
                document.getElementById('order-options-anchor').scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                this.loading = true;
                window.location.href = event.currentTarget.href;
            }
        },
        checkAndOpenModal(product) {
            if (!this.isOrderOptionSet) {
                this.showNotification('Pilih Tipe Pesanan & Meja Dahulu!', 'error');
                this.searchOpen = false; // Tutup overlay pencarian
                document.getElementById('order-options-anchor').scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }
            this.openModalWithProduct(product);
        },
        init() {
            this.recentSearches = JSON.parse(localStorage.getItem('recentSearches') || '[]');
            setTimeout(() => this.showContent = true, {{ $delay }});
    
            // ✅ Tambahan listener notifikasi
            window.addEventListener('show-notification', (e) => {
                if (this.showNotification) {
                    this.showNotification(e.detail.message, e.detail.type || 'success');
                } else {
                    console.warn('showNotification is not defined.');
                }
            });
        },
    
        // Fungsi untuk menambah kata kunci baru ke riwayat
        addSearchTerm(term) {
            if (!term || term.trim() === '') return;
            // Cek apakah kata kunci sudah ada dalam riwayat
            const index = this.recentSearches.findIndex(t => t.toLowerCase() === term.toLowerCase());
            if (index !== -1) {
                // Jika sudah ada, hapus dulu agar bisa dipindah ke depan
                this.recentSearches.splice(index, 1);
            }
            // Tambahkan ke paling depan
            this.recentSearches.unshift(term);
            // Batasi hanya 5 item terakhir
            this.recentSearches = this.recentSearches.slice(0, 5);
            // Simpan ke LocalStorage
            localStorage.setItem('recentSearches', JSON.stringify(this.recentSearches));
        },
        // Fungsi untuk menghapus satu item dari riwayat
        removeSearchTerm(index) {
            this.recentSearches.splice(index, 1);
            localStorage.setItem('recentSearches', JSON.stringify(this.recentSearches));
        },
        // Fungsi untuk membuka modal dengan data yang aman
        openModalWithProduct(product) {
            this.selectedProduct = product;
            this.modalQuantity = 1;
            this.modalSelectedVariant = null;
            if (product.variants && product.variants.length > 0) {
                this.modalSelectedVariant = product.variants[0];
            }
            this.modalOpen = true;
            this.searchOpen = false; // Pastikan overlay pencarian tertutup saat modal terbuka
        },
        addToCart() {
            this.loading = true;
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('quantity', this.modalQuantity);
            if (this.modalSelectedVariant) {
                formData.append('variant_id', this.modalSelectedVariant.id);
            }
            fetch(`/keranjang/tambah/${this.selectedProduct.id}`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // 1. KIRIM SINYAL UNTUK UPDATE ANGKA KERANJANG
                        window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                        // 2. KIRIM SINYAL UNTUK TAMPILKAN NOTIFIKASI
                        window.dispatchEvent(new CustomEvent('show-notification', { detail: { message: data.message, type: 'success' } }));
                        this.modalOpen = false;
                    } else {
                        window.dispatchEvent(new CustomEvent('show-notification', { detail: { message: data.message || 'Error', type: 'error' } }));
                    }
                })
                .catch(err => {
                    window.dispatchEvent(new CustomEvent('show-notification', { detail: { message: 'Gagal menghubungi server.', type: 'error' } }));
                    console.error(err);
                })
                .finally(() => { this.loading = false; });
        },
        // Fungsi untuk memformat harga
        formatPrice(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        }
    }" x-init="setTimeout(() => showContent = true, {{ $delay }})">
        {{-- Komponen Notifikasi Toast diletakkan di sini --}}
        <div x-show="notification.show" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform translate-x-full opacity-0"
            x-transition:enter-end="transform translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-x-0 opacity-100"
            x-transition:leave-end="transform translate-x-full opacity-0" class="fixed top-24 right-4 sm:right-6 w-auto z-50">
            <div class="max-w-xs sm:max-w-sm p-0 rounded-lg shadow-lg border"
                :class="{
                    'bg-green-100 border-green-400 text-green-700': notification.type === 'success',
                    'bg-red-100 border-red-400 text-red-700': notification.type === 'error',
                    'bg-blue-100 border-blue-400 text-blue-700': notification.type === 'info'
                }">
                <div class="p-3 flex items-center">
                    <div class="mr-3 flex-shrink-0">
                        <template x-if="notification.type === 'success'"><svg class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg></template>
                        <template x-if="notification.type === 'error'"><svg class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg></template>
                    </div>
                    <p x-text="notification.message" class="font-semibold text-sm"></p>
                </div>
            </div>
        </div>
        {{-- Loading Spinner Global --}}
        <div x-show="loading" x-cloak
            class="fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="flex flex-col items-center space-y-4">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-orange-500 border-t-transparent"></div>
                <p class="text-slate-600 font-medium">Memuat...</p>
            </div>
        </div>
        {{-- Konten akan muncul setelah delay --}}
        <div x-show="showContent" x-transition:enter="transition-all duration-500 ease-out"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0" class="font-sans">
            {{-- HEADER (termasuk flash di bawah ikon keranjang) --}}
            @include('partials.header')
            <div class="container mx-auto px-6 py-8">
                {{-- GREETING & SEARCH TRIGGER --}}
                <section class="mb-12">
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-8 border border-orange-100">
                        <h1 class="text-3xl font-light text-slate-600 tracking-wide">Hai FooDers,</h1>
                        <h2 class="text-4xl font-extrabold text-slate-800 mb-6">{{ $greeting }}!</h2>
                        <div class="relative cursor-pointer group" @click="searchOpen = true">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 z-10">
                                <svg class="h-6 w-6 text-slate-400 group-hover:text-orange-500 transition-colors"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <div
                                class="w-full text-slate-500 bg-white rounded-full py-4 pl-12 pr-4 border border-slate-200 text-left shadow-sm hover:shadow-md hover:border-orange-300 transition-all duration-200 group-hover:bg-orange-50">
                                Cari makanan/minuman favoritmu...
                            </div>
                        </div>
                    </div>
                </section>
                <div id="order-options-anchor" class="relative -top-24"></div>
                {{-- Ganti total section "Tipe Pesanan Anda" dengan ini --}}
                <section id="order-options" class="mb-12">
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h3 class="text-xl font-bold text-slate-800 mb-4">Tipe Pesanan Anda</h3>

                        {{-- Tombol sekarang adalah link biasa yang menyertakan parameter di URL --}}
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('home', ['order_type' => 'dine_in']) }}"
                                class="p-4 rounded-lg font-semibold transition-colors text-center {{ session('order_type', 'dine_in') == 'dine_in' ? 'bg-orange-500 text-white ring-2 ring-orange-300' : 'bg-slate-100 text-slate-600' }}">
                                Makan di Tempat
                            </a>
                            <a href="{{ route('home', ['order_type' => 'take_away']) }}"
                                class="p-4 rounded-lg font-semibold transition-colors text-center {{ session('order_type') == 'take_away' ? 'bg-orange-500 text-white ring-2 ring-orange-300' : 'bg-slate-100 text-slate-600' }}">
                                Bawa Pulang
                            </a>
                        </div>

                        {{-- Form ini akan submit dengan method GET saat meja dipilih --}}
                        @if (session('order_type', 'dine_in') == 'dine_in')
                            <div class="mt-4">
                                <form action="{{ route('home') }}" method="GET">
                                    <input type="hidden" name="order_type" value="dine_in">
                                    <label for="table_id" class="block font-semibold text-slate-700">Pilih Meja</label>
                                    {{-- 'onchange="this.form.submit()"' akan otomatis reload halaman saat meja dipilih --}}
                                    <select name="table_id" id="table_id" onchange="this.form.submit()"
                                        class="w-full mt-2 p-3 border-slate-300 rounded-lg bg-white focus:border-orange-500 focus:ring-orange-500">
                                        <option value="">-- Meja yang Tersedia --</option>
                                        @foreach ($availableTables as $table)
                                            <option value="{{ $table->id }}"
                                                @if (session('table_id') == $table->id) selected @endif>
                                                {{ $table->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($availableTables->isEmpty())
                                        <p class="text-sm text-red-500 mt-1">Maaf, tidak ada meja yang tersedia saat ini.
                                        </p>
                                    @endif
                                </form>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <section class="mb-12">
                {{-- Wadah luar yang memotong (overflow-hidden) dan memiliki background --}}
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 py-3 flex overflow-hidden">
                    {{-- Blok Teks 1 (Yang akan dianimasikan) --}}
                    <div class="marquee-track flex-shrink-0 flex items-center">
                        @foreach ($marqueeTexts as $text)
                            <div class="flex items-center flex-shrink-0 mx-8">
                                <span class="text-2xl md:text-3xl font-extrabold text-white uppercase tracking-wider">
                                    {{ $text }}
                                </span>
                                <svg class="w-8 h-8 mx-6 text-amber-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783-.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                            </div>
                        @endforeach
                    </div>
                    {{-- Blok Teks 2 (Duplikat persis untuk efek loop tanpa henti) --}}
                    <div class="marquee-track flex-shrink-0 flex items-center" aria-hidden="true">
                        @foreach ($marqueeTexts as $text)
                            <div class="flex items-center flex-shrink-0 mx-8">
                                <span class="text-2xl md:text-3xl font-extrabold text-white uppercase tracking-wider">
                                    {{ $text }}
                                </span>
                                <svg class="w-8 h-8 mx-6 text-amber-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783-.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            {{-- Ganti seluruh section promo Anda dengan ini --}}

            <div class="container mx-auto px-6 py-8">
                @if ($promotions->isNotEmpty())
                    <section class="mb-12">
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4 px-4 md:px-0">Promo Spesial Untukmu
                        </h2>
                        <div class="relative">
                            <div class="flex space-x-4 overflow-x-auto pb-4 no-scrollbar pl-4 md:pl-0">
                                @foreach ($promotions as $promo)
                                    <div class="flex-shrink-0 w-80 md:w-96 rounded-2xl shadow-lg overflow-hidden group">
                                        {{-- FIX: Menggunakan tag <a> untuk mengarah ke halaman detail --}}
                                        <a href="{{ route('promo.show', $promo) }}" class="block w-full text-left"
                                            @click="checkAndFollowLink($event)">
                                            <div class="relative rounded-t-2xl overflow-hidden">
                                                <img src="{{ asset('storage/' . $promo->image_path) }}"
                                                    alt="{{ $promo->title }}"
                                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">

                                                {{-- Overlay dan Judul di atas gambar --}}
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
                                                </div>
                                                <div class="absolute bottom-0 left-0 p-4">
                                                    <h3 class="text-white text-xl font-bold">{{ $promo->title }}</h3>
                                                </div>
                                            </div>
                                            <div class="p-4 bg-white">
                                                <div class="text-right mt-2">
                                                    @php
                                                        $promoData = is_string($promo->promo_data)
                                                            ? json_decode($promo->promo_data)
                                                            : $promo->promo_data;
                                                    @endphp
                                                    <span class="text-lg font-extrabold text-orange-600">
                                                        Rp{{ number_format($promoData->package_price ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                {{-- KATEGORI --}}
                <section class="mb-12">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Semua Kategori</h3>
                    </div>
                    <div class="flex space-x-4 overflow-x-auto pb-4 -mx-6 px-6 scrollbar-hide">
                        <a href="{{ route('home') }}"
                            class="flex-shrink-0 flex items-center pl-4 pr-5 py-3 rounded-full shadow-sm transition-all duration-200 hover:scale-105
                              {{ $activeCategory == 'all' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg' : 'bg-white text-slate-700 hover:bg-orange-100 hover:shadow-md' }}">
                            <img src="{{ asset('images/icon-all.png') }}" alt="Semua" class="h-6 w-6 mr-2">
                            <span class="font-bold">Semua</span>
                        </a>
                        <a href="{{ route('home', ['category' => 'makanan']) }}"
                            class="flex-shrink-0 flex items-center pl-4 pr-5 py-3 rounded-full shadow-sm transition-all duration-200 hover:scale-105
                              {{ $activeCategory == 'makanan' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg' : 'bg-white text-slate-700 hover:bg-orange-100 hover:shadow-md' }}">
                            <img src="{{ asset('images/icon-burger.png') }}" alt="Makanan" class="h-6 w-6 mr-2">
                            <span class="font-bold">Makanan</span>
                        </a>
                        <a href="{{ route('home', ['category' => 'minuman']) }}"
                            class="flex-shrink-0 flex items-center pl-4 pr-5 py-3 rounded-full shadow-sm transition-all duration-200 hover:scale-105
                              {{ $activeCategory == 'minuman' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg' : 'bg-white text-slate-700 hover:bg-orange-100 hover:shadow-md' }}">
                            <img src="{{ asset('images/icon-drinks.png') }}" alt="Minuman" class="h-6 w-6 mr-2">
                            <span class="font-bold">Minuman</span>
                        </a>
                    </div>
                </section>
                {{-- Paling Laris --}}
                @if ($bestSellers->isNotEmpty() && !request()->has('q'))
                    <div class="mb-12">
                        <div class="flex items-center mb-6">
                            <h3 class="text-2xl font-bold text-slate-800 mr-3">Paling Laris</h3>
                            <span
                                class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold animate-pulse">HOT</span>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-3 md:gap-8">
                            @foreach ($bestSellers as $product)
                                @include('partials.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Produk Utama / Hasil Pencarian --}}
                <section>
                    <h3 class="text-2xl font-bold text-slate-800 mb-6">{{ $sectionTitle }}</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-3 md:gap-8">
                        @forelse ($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @empty
                            <div class="col-span-full text-center py-16">
                                <div class="bg-slate-50 rounded-2xl p-12 max-w-md mx-auto">
                                    <svg class="mx-auto h-16 w-16 text-slate-300" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                    <h3 class="mt-4 text-xl font-semibold text-slate-800">Produk Tidak Ditemukan</h3>
                                    <p class="mt-2 text-base text-slate-500">Oops! Kami tidak dapat menemukan produk yang
                                        Anda cari.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('home') }}"
                                            class="inline-flex items-center rounded-full bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-lg transition-all duration-200 hover:scale-105">
                                            Kembali ke Menu Utama
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
        {{-- Modal Tambah ke Keranjang - REDESIGNED --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
            <div @click.outside="modalOpen = false" x-show="modalOpen"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <template x-if="selectedProduct">
                    <form @submit.prevent="addToCart()">
                        @csrf
                        <input type="hidden" name="quantity" :value="modalQuantity">
                        <input type="hidden" name="variant_id" :value="modalSelectedVariant?.id || ''">
                        {{-- Header Modal --}}
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white relative">
                            <button type="button" @click="modalOpen = false"
                                class="absolute top-4 right-4 p-1 rounded-full hover:bg-white/20 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <h3 class="text-xl font-bold text-center pr-8" x-text="selectedProduct.name"></h3>
                        </div>
                        {{-- Content Modal --}}
                        <div class="p-6 space-y-6">
                            {{-- Variant Selector --}}
                            <div x-show="selectedProduct.variants.length > 0">
                                <h4 class="text-base font-bold text-slate-800 mb-3">PILIH UKURAN:</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <template x-for="variant in selectedProduct.variants" :key="variant.id">
                                        <button type="button" @click="modalSelectedVariant = variant"
                                            :class="modalSelectedVariant?.id === variant.id ?
                                                'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg border-2 border-orange-500' :
                                                'bg-slate-50 text-slate-700 border-2 border-slate-200 hover:border-orange-300 hover:bg-orange-50'"
                                            class="px-4 py-3 rounded-lg font-bold transition-all duration-200 text-sm hover:scale-105">
                                            <span x-text="variant.size"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            {{-- Quantity & Price --}}
                            <div class="flex justify-between items-center bg-slate-50 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <button type="button" @click="modalQuantity = Math.max(1, modalQuantity - 1)"
                                        class="bg-white rounded-lg p-3 hover:bg-slate-100 transition-colors shadow-sm border border-slate-200">
                                        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <span
                                        class="text-2xl font-bold text-slate-800 w-12 text-center bg-white px-3 py-2 rounded-lg border border-slate-200"
                                        x-text="modalQuantity"></span>
                                    <button type="button" @click="modalQuantity++"
                                        class="bg-white rounded-lg p-3 hover:bg-slate-100 transition-colors shadow-sm border border-slate-200">
                                        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500 mb-1">Total Harga</p>
                                    <span
                                        x-text="
                                        selectedProduct.variants.length > 0
                                            ? (modalSelectedVariant ? formatPrice(modalSelectedVariant.price * modalQuantity) : 'Pilih ukuran')
                                            : formatPrice(selectedProduct.price * modalQuantity)
                                        "
                                        class="text-xl font-extrabold text-slate-900"></span>
                                    {{-- Teks PPN yang ditambahkan --}}
                                    <p class="text-xs text-slate-400 mt-1">*belum termasuk PPN</p>
                                </div>
                            </div>
                            {{-- Submit Button --}}
                            <button type="submit" :disabled="selectedProduct.variants.length > 0 && !modalSelectedVariant"
                                :class="selectedProduct.variants.length > 0 && !modalSelectedVariant ?
                                    'bg-slate-300 cursor-not-allowed' :
                                    'bg-gradient-to-r from-orange-500 to-orange-600 hover:shadow-lg hover:scale-105'"
                                class="w-full text-white font-bold py-4 rounded-lg shadow transition-all duration-200 disabled:transform-none">
                                <span x-show="!loading">Tambah ke Keranjang</span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
        {{-- Overlay Pencarian --}}
        <div x-show="searchOpen" x-cloak x-transition:enter="transition-all duration-300 ease-out"
            x-transition:enter-start="opacity-0 transform translate-y-full"
            x-transition:enter-end="opacity-100 transform translate-y-0" @keydown.escape.window="searchOpen = false"
            class="fixed inset-0 bg-white z-50">
            <div class="container mx-auto h-full flex flex-col" x-data="{ query: '{{ request()->query('q') }}' }">
                <header
                    class="flex items-center px-4 py-4 border-b border-slate-200 flex-shrink-0 bg-gradient-to-r from-orange-50 to-amber-50">
                    <h1 class="text-xl font-bold text-slate-800 grow">Pencarian</h1>
                    <button @click="searchOpen = false" class="p-2 rounded-full hover:bg-white/60 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </header>
                <div class="p-6 overflow-y-auto">
                    <form x-ref="searchForm" action="{{ route('home') }}" method="GET" class="relative"
                        @submit.prevent="addSearchTerm(query); loading = true; $nextTick(() => $refs.searchForm.submit())">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-6 w-6 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input type="text" name="q" x-model="query" x-ref="searchInput"
                            x-init="$watch('searchOpen', v => v && $nextTick(() => $refs.searchInput.focus()))"
                            class="w-full bg-white rounded-full py-4 pl-12 pr-10 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm"
                            placeholder="Ketik lalu tekan Enter..." autocomplete="off">
                        <button type="button" x-show="query.length > 0" @click="query = ''; $refs.searchInput.focus()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                    <section class="mt-8" x-show="recentSearches.length > 0">
                        <h3 class="text-xl font-bold text-slate-800 mb-4">Pencarian Terakhir</h3>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(term, index) in recentSearches" :key="index">
                                <div
                                    class="group bg-slate-100 rounded-full flex items-center transition-colors hover:bg-orange-50 border border-slate-200">
                                    {{-- Bagian Link Teks Pencarian --}}
                                    <a :href="`{{ route('home') }}?q=${term}`" @click="loading = true"
                                        class="text-slate-700 pl-4 pr-3 py-2 text-sm font-semibold group-hover:text-orange-700 truncate">
                                        <span x-text="term"></span>
                                    </a>
                                    {{-- Bagian Tombol Hapus (X) --}}
                                    <button @click.prevent="removeSearchTerm(index)"
                                        class="flex-shrink-0 pr-3 pl-1 text-slate-400 group-hover:text-orange-600 font-bold text-lg"
                                        title="Hapus">
                                        &times;
                                    </button>
                                </div>
                            </template>
                        </div>
                    </section>
                    <section class="mt-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-4">Rekomendasi Untuk Anda</h3>
                        <div class="space-y-2">
                            @foreach ($popularProducts as $product)
                                <div
                                    class="flex items-center p-3 transition-colors duration-200 rounded-xl hover:bg-slate-100">
                                    <a href="{{ route('product.show', $product->slug) }}"
                                        @click.prevent="checkAndFollowLink($event)" class="flex-shrink-0">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/icon-all.png') }}"
                                            alt="{{ $product->name }}"
                                            class="w-20 h-20 object-contain rounded-lg border bg-white p-1 mr-4">
                                    </a>
                                    <div class="flex-grow">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            @click.prevent="checkAndFollowLink($event)">
                                            <h4 class="font-bold text-slate-800 transition-colors">
                                                {{ $product->name }}</h4>
                                        </a>
                                        <div class="flex items-center text-sm text-gray-500 mt-1">
                                            @if ($product->rating)
                                                <div class="flex items-baseline">
                                                    <svg class="h-4 w-4 text-amber-400 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-slate-600 font-bold">{{ $product->rating }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button"
                                            @click.stop.prevent="checkAndOpenModal({{ $product->toJson() }})"
                                            class="bg-orange-500 text-white rounded-full h-10 w-10 flex items-center justify-center transform transition hover:bg-orange-600 hover:scale-110 shadow-sm">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .marquee-track {
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }
    </style>
@endsection
