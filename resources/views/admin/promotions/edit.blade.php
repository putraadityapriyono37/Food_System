@extends('layouts.admin')
@section('title', 'Edit Paket Promo')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Edit Paket Promo: {{ $promotion->title }}</h1>

        @php
            $promoData = is_string($promotion->promo_data)
                ? json_decode($promotion->promo_data)
                : $promotion->promo_data;
            $selectedProductIds = old('product_ids', $promoData->product_ids ?? []);
        @endphp

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Informasi Paket Promo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label for="title" class="block text-slate-700 font-semibold mb-2">Nama Paket Promo</label>
                    <input type="text" id="title" name="title"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                        value="{{ old('title', $promotion->title) }}" required>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-slate-700 font-semibold mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500" required>{{ old('description', $promotion->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-2">Pilih Produk untuk Paket</label>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 max-h-60 overflow-y-auto p-4 border rounded-lg">
                        @foreach ($products as $product)
                            <div>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                        class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                                        {{ in_array($product->id, $selectedProductIds) ? 'checked' : '' }}>
                                    {{-- UPDATE: Menampilkan harga asli produk --}}
                                    <span class="text-sm">
                                        {{ $product->name }}
                                        <span
                                            class="text-xs text-slate-500">(Rp{{ number_format($product->price, 0, ',', '.') }})</span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="package_price" class="block text-slate-700 font-semibold mb-2">Harga Paket (Rp)</label>
                    <input type="number" id="package_price" name="package_price"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                        value="{{ old('package_price', $promoData->package_price ?? '') }}" required>
                </div>

                <div>
                    <label for="image" class="block text-slate-700 font-semibold mb-2">Ganti Gambar Banner
                        (Opsional)</label>
                    <img src="{{ asset('storage/' . $promotion->image_path) }}" alt="{{ $promotion->title }}"
                        class="h-20 w-36 object-cover rounded-md border p-1 bg-white mb-2">
                    <input type="file" id="image" name="image"
                        class="w-full border-slate-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                </div>

                <input type="hidden" name="is_active" value="{{ $promotion->is_active }}">
            </div>

            <div class="flex items-center space-x-4 mt-6 border-t pt-6">
                <button type="submit"
                    class="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-6 py-3 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.promotions.index') }}"
                    class="text-slate-600 hover:text-slate-800 font-medium">Batal</a>
            </div>
        </form>
    </div>
@endsection
