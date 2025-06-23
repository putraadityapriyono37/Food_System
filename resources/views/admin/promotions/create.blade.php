@extends('layouts.admin')
@section('title', 'Buat Paket Promo Baru')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Buat Paket Promo Baru</h1>

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

        <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Informasi Paket Promo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label for="title" class="block text-slate-700 font-semibold mb-2">Nama Paket Promo</label>
                    <input type="text" id="title" name="title"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                        value="{{ old('title') }}" required>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-slate-700 font-semibold mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500" required>{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-slate-700 font-semibold mb-2">Pilih Produk untuk Paket</label>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 max-h-60 overflow-y-auto p-4 border rounded-lg">
                        @foreach ($products as $product)
                            <div>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                        class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                                        {{ is_array(old('product_ids')) && in_array($product->id, old('product_ids')) ? 'checked' : '' }}>
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
                        value="{{ old('package_price') }}" required>
                </div>

                <div>
                    <label for="image" class="block text-slate-700 font-semibold mb-2">Gambar Banner</label>
                    <input type="file" id="image" name="image"
                        class="w-full border-slate-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                        required>
                </div>

                <input type="hidden" name="is_active" value="1">
            </div>

            <div class="flex items-center space-x-4 mt-6 border-t pt-6">
                <button type="submit"
                    class="bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-6 py-3 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all">
                    Simpan Paket
                </button>
                <a href="{{ route('admin.promotions.index') }}"
                    class="text-slate-600 hover:text-slate-800 font-medium">Batal</a>
            </div>
        </form>
    </div>
@endsection
