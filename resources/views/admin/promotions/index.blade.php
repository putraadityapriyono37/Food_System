@extends('layouts.admin')
@section('title', 'Manajemen Promo')

@section('content')
    <div class="p-6">
        {{-- Header Halaman --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Promo</h1>
            <a href="{{ route('admin.promotions.create') }}"
                class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all">
                <svg class="h-5 w-5 -ml-1 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Promo Baru</span>
            </a>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Promo --}}
        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Banner</th>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Judul Promo</th>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Isi Paket</th>
                        <th class="p-3 text-right text-sm font-semibold text-slate-600">Harga Paket</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Status</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promo)
                        @php
                            // Memastikan promo_data adalah objek untuk diakses
                            $promoData = is_string($promo->promo_data)
                                ? json_decode($promo->promo_data)
                                : $promo->promo_data;
                        @endphp
                        <tr class="border-b hover:bg-orange-50 transition-colors">
                            <td class="p-3">
                                <img src="{{ asset('storage/' . $promo->image_path) }}" alt="{{ $promo->title }}"
                                    class="h-12 w-20 object-cover rounded-md border p-1 bg-white">
                            </td>
                            <td class="p-3 font-semibold text-slate-700">
                                {{ $promo->title }}
                            </td>
                            <td class="p-3 text-slate-600">
                                {{-- FIX: Gunakan accessor 'products' (jamak) dan tampilkan sebagai daftar --}}
                                @if ($promo->products->isNotEmpty())
                                    {{-- Menampilkan nama produk dipisahkan dengan koma --}}
                                    {{ implode(', ', $promo->products->pluck('name')->toArray()) }}
                                @else
                                    <span class="text-red-500">Produk tidak ditemukan</span>
                                @endif
                            </td>
                            <td class="p-3 font-semibold text-right text-orange-600">
                                {{-- FIX: Menggunakan 'package_price' dari data promo --}}
                                Rp{{ number_format($promoData->package_price ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $promo->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $promo->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="flex items-center justify-center space-x-4">
                                    <a href="{{ route('admin.promotions.edit', $promo) }}"
                                        class="text-orange-600 hover:underline font-semibold">Edit</a>
                                    <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus promo ini?');">
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
                            <td colspan="6" class="p-4 text-center text-slate-500">Belum ada promo yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $promotions->links() }}
        </div>
    </div>
@endsection
