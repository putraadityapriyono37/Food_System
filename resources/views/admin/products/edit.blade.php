@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
    {{-- KESALAHAN #2 DIPERBAIKI: Semua state disatukan di sini --}}
    <div class="p-6" x-data="{
        variants: {{ $product->variants->map->only(['id', 'size', 'price'])->toJson() }},
        category: '{{ old('category', $product->category) }}',
        isBestSeller: {{ old('is_best_seller', $product->is_best_seller) == 1 ? 'true' : 'false' }},
        removedVariants: []
    }">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Edit Produk: {{ $product->name }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <strong class="font-bold">Oops! Terjadi kesalahan validasi.</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Informasi Produk</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="name" class="block text-slate-700 font-semibold mb-2">Nama Produk</label>
                    <input type="text" id="name" name="name"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                        value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-slate-700 font-semibold mb-2">Kategori</label>
                    <select id="category" name="category"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                        x-model="category" @change="if (category === 'minuman') { variants = [] }" {{-- <-- TAMBAHKAN INI --}}
                        required>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label for="time_estimation" class="block text-slate-700 font-semibold mb-2">Estimasi Waktu (Menit)</label>
                <input type="number" id="time_estimation" name="time_estimation"
                    class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                    value="{{ old('time_estimation', $product->time_estimation ?? '') }}">
                @error('time_estimation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Jadikan Best Seller?</label>
                {{-- KESALAHAN #2 DIPERBAIKI: Hapus x-data dari sini --}}
                <div>
                    <input type="hidden" name="is_best_seller" :value="isBestSeller ? 1 : 0">
                    <button type="button" @click="isBestSeller = !isBestSeller"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                        :class="isBestSeller ? 'bg-orange-500' : 'bg-gray-200'">
                        <span aria-hidden="true"
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                            :class="{ 'translate-x-5': isBestSeller, 'translate-x-0': !isBestSeller }"></span>
                    </button>
                    <span x-text="isBestSeller ? 'Ya, Best Seller' : 'Bukan Best Seller'" class="ml-3 text-sm font-medium"
                        :class="isBestSeller ? 'text-orange-500' : 'text-gray-600'"></span>
                </div>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-700 font-semibold mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500" required>{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="mb-4" x-show="category === 'minuman'" x-cloak>
                <label for="price" class="block text-slate-700 font-semibold mb-2">Harga Utama</label>
                <input type="number" id="price" name="price" class="w-full border rounded-lg p-3"
                    value="{{ old('price', $product->price) }}">
                <p class="text-sm text-slate-500 mt-1">Harga ini digunakan untuk produk tanpa varian (Minuman).</p>
            </div>
            <div class="mb-4">
                <label for="rating" class="block text-slate-700 font-semibold mb-2">Rating Bintang (0.0 - 5.0)</label>
                <input type="number" id="rating" name="rating"
                    class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                    value="{{ old('rating', $product->rating) }}" step="0.1" min="0" max="5">
                <p class="text-sm text-slate-500 mt-1">Kosongkan jika tidak ada rating.</p>
                @error('rating')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="image" class="block text-slate-700 font-semibold mb-2">Ganti Gambar Produk</label>
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-32 h-32 object-contain rounded-lg border bg-slate-50 p-1 mb-2">
                @endif
                <input type="file" id="image" name="image"
                    class="w-full border-slate-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                <p class="text-sm text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
            </div>

            {{-- BAGIAN VARIAN DINAMIS --}}
            <div class="border-t pt-6 mt-6" x-if="category === 'makanan'">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Varian Produk</h3>
                <input type="hidden" name="removed_variants" :value="removedVariants.join(',')">
                <div class="space-y-4">
                    <template x-for="(variant, index) in variants" :key="variant.id || index">
                        <div class="flex items-center space-x-4 p-3 rounded-md bg-slate-50 border">
                            <input type="hidden" :name="`variants[${index}][id]`" x-model="variant.id">
                            <div class="flex-grow">
                                <label class="text-sm font-semibold text-slate-600">Ukuran</label>
                                <input type="text" :name="`variants[${index}][size]`" x-model="variant.size"
                                    class="w-full border-slate-300 rounded-lg p-2 mt-1 focus:border-orange-500 focus:ring-orange-500">
                            </div>
                            <div class="flex-grow">
                                <label class="text-sm font-semibold text-slate-600">Harga Varian</label>
                                <input type="number" :name="`variants[${index}][price]`" x-model="variant.price"
                                    class="w-full border-slate-300 rounded-lg p-2 mt-1 focus:border-orange-500 focus:ring-orange-500">
                            </div>
                            <button type="button"
                                @click="if(variant.id) removedVariants.push(variant.id); variants.splice(index, 1)"
                                class="p-2 text-red-500 hover:bg-red-100 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="variants.push({ id: null, size: '', price: '' })"
                    class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline focus:outline-none">
                    + Tambah Varian
                </button>
            </div>

            <div class="flex items-center space-x-4 mt-6 border-t pt-6">
                <button type="submit"
                    class="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-6 py-3 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all">Simpan
                    Perubahan</button>
                <a href="{{ route('admin.products.index') }}"
                    class="text-slate-600 hover:text-slate-800 font-medium">Batal</a>
            </div>
        </form>
    </div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
