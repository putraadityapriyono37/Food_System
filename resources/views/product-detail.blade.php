@extends('layouts.app')

@section('title', $product->name)

@section('content')
    {{-- Komponen Alpine.js untuk mengelola semua interaksi di halaman ini --}}
    <div class="font-sans" x-data="{
        quantity: 1,
        loading: false,
        variants: {{ $product->variants->isEmpty() ? '[]' : $product->variants->map(fn($v) => ['id' => $v->id, 'size' => $v->size, 'price' => $v->price])->toJson() }},
        selectedVariant: {{ $product->variants->first() ? json_encode($product->variants->first()->only(['id', 'size', 'price'])) : 'null' }},
    
        // State untuk mengetahui apakah opsi pesanan sudah diatur
        isOrderOptionSet: {{ $isOrderOptionSet ? 'true' : 'false' }},
    
        // Fungsi untuk submit form via AJAX
        addToCart() {
            // 1. Lakukan pengecekan terlebih dahulu
            if (!this.isOrderOptionSet) {
                // Kirim sinyal untuk menampilkan notifikasi pop-up
                window.dispatchEvent(new CustomEvent('show-notification', {
                    detail: { message: 'Pilih Tipe Pesanan & Meja di Menu Utama!', type: 'error' }
                }));
                // Arahkan pengguna kembali ke halaman utama setelah notifikasi muncul
                setTimeout(() => window.location.href = '{{ route('home') }}#order-options', 1500);
                return;
            }
    
            // 2. Jika valid, lanjutkan proses
            this.loading = true;
            let formData = new FormData(this.$refs.mainForm);
    
            fetch(this.$refs.mainForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Kirim sinyal ke header untuk update angka keranjang
                        window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                        // Kirim sinyal untuk menampilkan notifikasi pop-up sukses
                        window.dispatchEvent(new CustomEvent('show-notification', { detail: { message: data.message, type: 'success' } }));
                    } else {
                        window.dispatchEvent(new CustomEvent('show-notification', { detail: { message: data.message || 'Gagal menambahkan', type: 'error' } }));
                    }
                })
                .catch(error => {
                    window.dispatchEvent(new CustomEvent('show-notification', { detail: { message: 'Gagal menghubungi server.', type: 'error' } }));
                    console.error('Error:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }">
        {{-- Overlay Loading --}}
        <div x-show="loading" x-cloak class="fixed inset-0 bg-white/70 z-50 flex items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-orange-500 border-t-transparent"></div>
        </div>

        {{-- HEADER --}}
        @include('partials.header')

        {{-- Form membungkus semua konten agar tombol di mobile & desktop terhubung --}}
        <form x-ref="mainForm" action="{{ route('cart.add', $product->id) }}" method="POST" @submit.prevent="addToCart">
            @csrf
            <input type="number" name="quantity" x-model="quantity" class="hidden">
            <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">

            {{-- Padding bawah di mobile untuk memberi ruang bagi action bar --}}
            <div class="pb-40 lg:pb-0">
                {{-- Layout Grid Responsif --}}
                <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:max-w-6xl lg:mx-auto lg:py-12">

                    {{-- Kolom Kiri: Gambar --}}
                    <div
                        class="h-80 lg:h-auto lg:aspect-square bg-slate-100 flex items-center justify-center lg:rounded-2xl">
                        <img src="{{ !empty($product->image) && file_exists(public_path('storage/' . $product->image)) ? asset('storage/' . $product->image) : ($product->category == 'makanan' ? asset('images/icon-burger.png') : asset('images/icon-drinks.png')) }}"
                            alt="{{ $product->name }}" class="w-3/4 h-3/4 object-contain filter drop-shadow-xl">
                    </div>

                    {{-- Kolom Kanan: Info & Aksi --}}
                    <div class="p-6 lg:p-0 flex flex-col">

                        {{-- Bagian Info Teks --}}
                        <div class="grow">
                            <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mt-4 lg:mt-0">{{ $product->name }}</h2>
                            <p class="text-slate-500 text-base lg:text-lg mt-2 min-h-[4rem]">{{ $product->description }}</p>

                            <div class="flex items-center space-x-6 mt-4 text-slate-700">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-1 text-base font-semibold">4.8</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="ml-1 text-base font-semibold">{{ $product->time_estimation }} min</span>
                                </div>
                            </div>

                            @if ($product->variants->isNotEmpty())
                                <div class="mt-8">
                                    <h3 class="text-lg font-bold text-slate-800">SIZE:</h3>
                                    <div class="flex space-x-3 mt-2">
                                        <template x-for="variant in variants" :key="variant.id">
                                            <button type="button" @click="selectedVariant = variant"
                                                :class="{
                                                    'bg-orange-500 text-white shadow': selectedVariant &&
                                                        selectedVariant
                                                        .id === variant
                                                        .id,
                                                    'bg-slate-200 text-slate-700 hover:bg-slate-300': !
                                                        selectedVariant || selectedVariant.id !== variant.id
                                                }"
                                                class="px-5 py-2 rounded-lg font-bold transition-colors">
                                                <span x-text="variant.size"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi untuk Tampilan Desktop (disembunyikan di mobile) --}}
                        <div class="hidden lg:flex flex-col space-y-4 mt-8">
                            <div class="flex justify-between items-center">
                                <span
                                    x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selectedVariant ? selectedVariant.price * quantity : {{ $product->price ?? 0 }} * quantity)"
                                    class="text-3xl font-extrabold text-slate-900"></span>
                                <div class="flex items-center space-x-2">
                                    <button type="button" @click="if (quantity > 1) quantity--"
                                        class="bg-slate-200 rounded-lg p-3 hover:bg-slate-300 transition-colors"><svg
                                            class="h-6 w-6 text-slate-700" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                        </svg></button>
                                    <span class="text-2xl font-bold text-slate-800 w-12 text-center"
                                        x-text="quantity"></span>
                                    <button type="button" @click="quantity++"
                                        class="bg-slate-200 rounded-lg p-3 hover:bg-slate-300 transition-colors"><svg
                                            class="h-6 w-6 text-slate-700" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg></button>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow hover:bg-orange-600 transition-colors">Tambah
                                ke Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTION BAR BAWAH (HANYA TAMPIL DI MOBILE) --}}
            <div
                class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-sm border-t border-slate-200 p-4 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)]">
                <div class="w-full">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xl font-bold text-slate-900"
                            x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selectedVariant ? selectedVariant.price * quantity : {{ $product->price ?? 0 }} * quantity)"></span>
                        <div class="flex items-center space-x-2">
                            <button type="button" @click="if (quantity > 1) quantity--"
                                class="bg-slate-200 rounded-lg p-3 hover:bg-slate-300 transition-colors"><svg
                                    class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                </svg></button>
                            <span class="text-xl font-bold text-slate-800 w-10 text-center" x-text="quantity"></span>
                            <button type="button" @click="quantity++"
                                class="bg-slate-200 rounded-lg p-3 hover:bg-slate-300 transition-colors"><svg
                                    class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg></button>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-orange-500 text-white font-bold py-3 text-center rounded-lg shadow hover:bg-orange-600">
                        <span x-show="!loading">Tambah ke Keranjang</span>
                        <span x-show="loading">Memproses...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
