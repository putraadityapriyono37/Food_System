@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_code)

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Pesanan</h1>
                <p class="font-mono text-slate-500">#{{ $order->order_code }}</p>
            </div>
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center text-slate-600 hover:text-orange-600 font-semibold">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar Pesanan
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-4">Ringkasan</h2>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-slate-500">Nama Pemesan</p>
                        <p class="font-semibold text-lg">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Metode Pembayaran</p>
                        <p class="font-semibold text-lg capitalize">{{ $order->payment_method }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Waktu Pesan</p>
                        <p class="font-semibold text-lg">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-slate-800 mt-6 mb-2 border-t pt-4">Item yang Dipesan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-3 text-left text-sm font-semibold text-slate-600">Produk</th>
                                <th class="p-3 text-center text-sm font-semibold text-slate-600">Jumlah</th>
                                <th class="p-3 text-right text-sm font-semibold text-slate-600">Harga Satuan</th>
                                <th class="p-3 text-right text-sm font-semibold text-slate-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                @php
                                    $details = json_decode($item->item_details);
                                @endphp
                                <tr class="border-b">
                                    <td class="p-3">
                                        <p class="font-semibold">{{ $details->name ?? 'Nama Tidak Tersedia' }}</p>

                                        @if (isset($details->products))
                                            <ul class="text-xs text-slate-500 list-disc list-inside mt-1">
                                                @foreach ($details->products as $subProduct)
                                                    {{-- FIX: Tampilkan $subProduct langsung karena ia adalah string (nama produk) --}}
                                                    <li>{{ $subProduct }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">x {{ $item->quantity }}</td>
                                    <td class="p-3 text-right">Rp{{ number_format($item->price_per_item, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-right font-semibold">
                                        Rp{{ number_format($item->price_per_item * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50">
                            @php
                                $subtotal = $order->items->sum(function ($item) {
                                    return $item->price_per_item * $item->quantity;
                                });
                                $ppn = $subtotal * 0.11;
                            @endphp

                            <tr class="font-semibold">
                                <td colspan="3" class="p-3 text-right text-slate-600">Subtotal</td>
                                <td class="p-3 text-right text-slate-800">Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="font-semibold">
                                <td colspan="3" class="p-3 text-right text-slate-600">PPN (11%)</td>
                                <td class="p-3 text-right text-slate-800">Rp{{ number_format($ppn, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="font-extrabold bg-slate-100">
                                <td colspan="3" class="p-3 text-right text-slate-800">Total Akhir</td>
                                <td class="p-3 text-right text-xl text-orange-600">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md self-start">
                <h2 class="text-xl font-bold text-slate-800 mb-4">Status Pesanan</h2>
                <p
                    class="text-center text-lg font-bold p-2 rounded-full mb-6 capitalize
                {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $order->status == 'paid' ? 'bg-cyan-100 text-cyan-800' : '' }}
                {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </p>

                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label for="status" class="block text-slate-700 font-semibold mb-2">Ubah Status Menjadi:</label>
                    <select name="status" id="status"
                        class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500">
                        <option value="pending" @if ($order->status == 'pending') selected @endif>Menunggu
                            Pembayaran</option>
                        <option value="paid" @if ($order->status == 'paid') selected @endif>Diproses</option>
                        <option value="completed" @if ($order->status == 'completed') selected @endif>Selesai</option>
                        <option value="cancelled" @if ($order->status == 'cancelled') selected @endif>Dibatalkan</option>
                    </select>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-3 rounded-lg mt-4 hover:shadow-lg transition-all">Update
                        Status</button>
                </form>
            </div>
        </div>
    </div>
@endsection
