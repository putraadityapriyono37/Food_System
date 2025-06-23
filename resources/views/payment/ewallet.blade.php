@extends('layouts.app')
@section('title', 'Pembayaran E-Wallet')

@section('content')
    <div class="font-sans" x-data="{ selectedWallet: null, showQrScreen: false }">
        {{-- Header dengan tombol kembali yang berfungsi --}}
        @include('partials.header-payment', [
            'title' => 'E - Wallet',
            'backUrl' => route('payment.cancel', $order),
        ])
        <div class="flex-grow flex items-center justify-center p-4" x-data="{
            secondsRemaining: 300,
            expired: false,
            get minutes() {
                return Math.floor(this.secondsRemaining / 60).toString().padStart(2, '0');
            },
            get seconds() {
                return (this.secondsRemaining % 60).toString().padStart(2, '0');
            },
            init() {
                const timer = setInterval(() => {
                    if (this.secondsRemaining > 0) {
                        this.secondsRemaining--;
                    } else {
                        this.expired = true;
                        clearInterval(timer);
                    }
                }, 1000);
            }
        }">

            <div class="container mx-auto px-4 py-8">
                <div x-show="!showQrScreen" x-transition>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (['GoPay', 'OVO', 'DANA', 'ShopeePay'] as $wallet)
                            <button @click="selectedWallet = '{{ $wallet }}'"
                                class="p-4 border-2 rounded-xl text-left transition-all flex items-center space-x-4"
                                :class="selectedWallet === '{{ $wallet }}' ? 'border-orange-500 bg-orange-50' :
                                    'border-slate-200 hover:bg-slate-50'">
                                <img src="{{ asset('images/ewallet_logos/' . strtolower($wallet) . '.png') }}"
                                    class="h-8 w-8 object-contain">
                                <span class="font-bold text-lg">{{ $wallet }}</span>
                            </button>
                        @endforeach
                    </div>
                    <div class="fixed bottom-0 left-0 right-0 bg-white p-4 border-t">
                        <button @click="showQrScreen = true" :disabled="!selectedWallet"
                            class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-orange-600 transition-colors">
                            Lanjutkan dengan <span x-text="selectedWallet"></span>
                        </button>
                    </div>
                </div>

                <div x-show="showQrScreen" x-cloak x-transition class="text-center">
                    <p class="text-slate-600">Pindai kode di bawah ini menggunakan aplikasi <strong
                            x-text="selectedWallet"></strong></p>
                    <div class="mt-4 inline-block bg-white p-6 rounded-2xl shadow-lg">
                        <img src="{{ asset('images/fake-qris.png') }}" alt="Contoh QRIS" class="w-64 h-64">
                    </div>
                    <div class="mt-4">
                        <template x-if="!expired">
                            <p class="text-slate-500">
                                Selesaikan pembayaran dalam
                                <span class="font-bold text-lg text-orange-600" x-text="`${minutes}:${seconds}`"></span>
                            </p>
                        </template>
                        <template x-if="expired">
                            <div class="bg-red-100 text-red-700 font-bold p-3 rounded-lg">
                                Waktu pembayaran telah habis.
                            </div>
                        </template>
                    </div>

                    <div class="mt-6 text-lg">
                        <p>Total Pembayaran: <span
                                class="font-bold text-orange-600">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    <div class="fixed bottom-0 left-0 right-0 bg-white p-4 border-t">
                        <form action="{{ route('payment.process', $order) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-orange-600 transition-colors">
                                Pembayaran Berhasil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
