@extends('layouts.admin')
@section('title', 'Manajemen Produk')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Produk</h1>
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all">
                {{-- Ikon Plus --}}
                <svg class="h-5 w-5 -ml-1 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Produk</span>
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Gambar</th>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Nama</th>
                        <th class="p-3 text-right text-sm font-semibold text-slate-600">Harga Dasar</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Kategori</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        {{-- Hover pada baris tabel sekarang berwarna oranye muda --}}
                        <tr class="border-b hover:bg-orange-50 transition-colors">
                            <td class="p-3">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="h-12 w-12 object-contain rounded-md border p-1 bg-white">
                                @else
                                    <span class="text-xs text-slate-400">No Image</span>
                                @endif
                            </td>
                            <td class="p-3 font-semibold text-slate-700">
                                <span>{{ $product->name }}</span>
                                @if ($product->is_best_seller)
                                    <span
                                        class="ml-2 text-xs font-semibold bg-amber-200 text-amber-800 px-2 py-1 rounded-full">Best
                                        Seller</span>
                                @endif
                            </td>
                            <td class="p-3 font-semibold text-right">Rp{{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center capitalize">{{ $product->category }}</td>
                            <td class="p-3">
                                {{-- Kita bungkus dengan div flexbox untuk menyejajarkan --}}
                                <div class="flex items-center justify-center space-x-4">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="text-orange-600 hover:underline font-semibold">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:underline font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-slate-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection
