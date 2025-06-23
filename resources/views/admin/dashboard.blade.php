@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Card Pendapatan Hari Ini --}}
            <div
                class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4 transform hover:-translate-y-1 transition-transform">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.825-1.106-2.296 0-3.121C10.544 7.719 11.275 7.5 12 7.5c.725 0 1.45.22 2.003.659l.879.659m0-2.25h.008v.008h-.008v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-slate-500 text-sm font-semibold">PENDAPATAN HARI INI</h3>
                    <p class="text-2xl font-bold text-slate-800">Rp{{ number_format($revenueToday, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Card Pesanan Hari Ini --}}
            <div
                class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4 transform hover:-translate-y-1 transition-transform">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-slate-500 text-sm font-semibold">PESANAN HARI INI</h3>
                    <p class="text-2xl font-bold text-slate-800">{{ $ordersTodayCount }}</p>
                </div>
            </div>

            {{-- Card Pesanan Pending --}}
            <div
                class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4 transform hover:-translate-y-1 transition-transform">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="h-8 w-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-slate-500 text-sm font-semibold">PESANAN PENDING</h3>
                    <p class="text-2xl font-bold text-slate-800">{{ $pendingOrdersCount }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            {{-- Grafik Penjualan --}}
            <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-slate-800 mb-4">Grafik Penjualan (7 Hari Terakhir)</h3>
                <canvas id="salesChart"></canvas>
            </div>
            {{-- Daftar Pesanan Terbaru --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-slate-800 mb-4">Pesanan Terbaru</h3>
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="flex justify-between items-center hover:bg-slate-50 p-2 rounded-lg">
                            <div>
                                <p class="font-semibold text-slate-700">{{ $order->customer_name }}</p>
                                <p class="text-sm text-slate-500">{{ $order->order_code }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-orange-600">Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="text-blue-600 text-sm hover:underline">Lihat</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center py-4">Belum ada pesanan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($chartData) !!},
                    borderColor: 'rgb(249, 115, 22)', // Warna Oranye
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
