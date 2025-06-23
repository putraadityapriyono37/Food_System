@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Manajemen Pesanan</h1>
        <div class="bg-white p-4 rounded-lg shadow-md mb-6 relative z-10">
            <form action="{{ route('admin.orders.index') }}" method="GET"
                class="flex flex-col sm:flex-row gap-4 items-center">
                <input type="text" name="search" placeholder="Cari Kode Pesanan / Nama..." value="{{ request('search') }}"
                    class="w-full sm:flex-grow border-slate-300 rounded-lg p-2 focus:border-orange-500 focus:ring-orange-500">

                <div class="relative w-full sm:w-52">
                    <select name="status"
                        class="w-full border-slate-300 rounded-lg p-2 pr-8 appearance-none bg-white focus:border-orange-500 focus:ring-orange-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu_pembayaran" @if (request('status') == 'pending') selected @endif>Menunggu
                            Pembayaran</option>
                        <option value="diproses" @if (request('status') == 'paid') selected @endif>Diproses</option>
                        <option value="selesai" @if (request('status') == 'completed') selected @endif>Selesai</option>
                        <option value="dibatalkan" @if (request('status') == 'cancelled') selected @endif>Dibatalkan</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-700">
                    </div>
                </div>
                <button type="submit"
                    class="w-full sm:w-auto bg-orange-500 text-white font-bold px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors">Filter</button>
                <a href="{{ route('admin.orders.index') }}"
                    class="w-full sm:w-auto text-center text-slate-500 hover:text-slate-700 py-2">Reset</a>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-200">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Kode Pesanan</th>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Nama Pemesan</th>
                        <th class="p-3 text-right text-sm font-semibold text-slate-600">Total</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Status</th>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Waktu Pesan</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-b border-slate-100 hover:bg-orange-50 transition-colors">
                            <td class="p-3 font-mono text-slate-700 font-semibold">{{ $order->order_code }}</td>
                            <td class="p-3 text-slate-700">{{ $order->customer_name }}</td>
                            <td class="p-3 font-semibold text-right">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                {{-- PERBAIKAN WARNA BADGE --}}
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full capitalize
                                    {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status == 'paid' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-sm text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="text-orange-600 hover:underline font-semibold">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-slate-500">Tidak ada pesanan yang cocok dengan
                                filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
