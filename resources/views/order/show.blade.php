@extends('layouts.app')

{{-- Menentukan judul halaman secara dinamis --}}
@if (session('payment_success'))
    @section('title', 'Pembayaran Berhasil')
@else
    @section('title', 'Pesanan Dibuat')
@endif


@section('content')
    <div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-md w-full space-y-8 text-center">

            {{-- 1. Ikon Checkmark Sesuai Desain --}}
            <div>
                <div
                    class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gradient-to-br from-orange-100 to-amber-200">
                    <svg class="h-16 w-16 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                {{-- 2. Judul dan Sub-judul Dinamis --}}
                <h2 class="mt-6 text-3xl font-extrabold text-slate-900">
                    @if (session('payment_success'))
                        Pembayaran Berhasil!
                    @else
                        Pesanan Berhasil Dibuat!
                    @endif
                </h2>
                <p class="mt-2 text-base text-slate-600">
                    Terima kasih, <span class="font-semibold">{{ $order->customer_name }}</span>. Pesanan Anda telah kami
                    terima.
                </p>
            </div>

            {{-- 3. Kotak Detail Pesanan --}}
            <div class="bg-white shadow-md rounded-xl p-6 text-left space-y-4">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-slate-500">Total Pembayaran</p>
                    <p class="text-xl font-bold text-slate-800">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
                <div class="border-t border-slate-200"></div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-slate-500">Tanggal</p>
                    <p class="text-sm text-slate-700">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-slate-500">Metode Pembayaran</p>
                    <p class="text-sm font-semibold text-slate-700">
                        @if ($order->payment_method === 'epayment')
                            E-Payment
                        @elseif($order->payment_method === 'ewallet')
                            E-Wallet
                        @elseif($order->payment_method === 'cashier')
                            Bayar di Kasir
                        @else
                            {{ ucfirst($order->payment_method) }}
                        @endif
                    </p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-slate-500">ID Pesanan</p>
                    <p class="text-sm font-mono text-orange-600 font-bold">{{ $order->order_code }}</p>
                </div>
            </div>

            {{-- 4. Tombol Aksi --}}
            <div>
                @if ($order->payment_method == 'cashier' && $order->status != 'paid')
                    <p class="text-center text-sm text-slate-500 mb-4">Silakan tunjukkan ID Pesanan di atas saat melakukan
                        pembayaran di kasir.</p>
                @endif

                <a href="{{ route('home') }}"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    Kembali ke Menu Utama
                </a>
            </div>

        </div>
    </div>
@endsection
