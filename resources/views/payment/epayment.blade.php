@extends('layouts.app')
@section('title', 'Pembayaran E-Payment')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen" x-data="{
        selectedBank: null,
        selectedCardId: '{{ request('newCardId') }}' || null,
        showAddCardModal: false,
        showCardDropdown: false,
        savedCards: {{ $savedCards->toJson() }},
        loading: false,
    
        // SEMUA LOGIKA UNTUK MODAL SEKARANG ADA DI SINI
        cardForm: {
            name: '{{ old('card_holder_name', $order->customer_name) }}',
            number: '{{ old('card_number', '') }}',
            expiry_month: '{{ old('expiry_month', '') }}',
            expiry_year: '{{ old('expiry_year', '') }}',
            cvc: ''
        },
        formErrorMessage: '',
    
        // Fungsi ini berjalan saat halaman pertama kali dimuat
        init() {
            if ('{{ request('newCardId') }}' && '{{ request('newCardBank') }}') {
                this.selectedBank = '{{ request('newCardBank') }}';
                this.selectedCardId = '{{ request('newCardId') }}';
            }
        },
    
        // Pengecekan form modal secara real-time
        isCardFormInvalid() {
            return this.cardForm.name.trim() === '' ||
                this.cardForm.number.trim().length !== 16 ||
                !/^\d+$/.test(this.cardForm.number) ||
                this.cardForm.expiry_month === '' ||
                this.cardForm.expiry_year === '' ||
                this.cardForm.cvc.trim().length < 3;
        },
    
        // Fungsi AJAX untuk menyimpan kartu baru
        submitCardForm() {
            this.loading = true;
            this.formErrorMessage = '';
            let formData = new FormData(this.$refs.addCardForm);
    
            // Menambahkan bank_name ke formData secara manual
            formData.append('bank_name', this.selectedBank);
    
            fetch('{{ route('payment.add_card', $order) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    }
                })
                .then(response => response.json().then(data => ({ ok: response.ok, data })))
                .then(({ ok, data }) => {
                    if (ok && data.success) {
                        window.location.href = data.redirectUrl;
                    } else {
                        this.formErrorMessage = data.message || 'Mohon periksa kembali isian Anda.';
                    }
                })
                .catch(error => { this.formErrorMessage = 'Gagal menghubungi server.'; })
                .finally(() => { this.loading = false; });
        },
    
        // Helper function untuk mendapatkan detail kartu yang sedang dipilih
        getSelectedCard() {
            if (!this.selectedBank || !this.selectedCardId) return null;
            if (!this.savedCards[this.selectedBank]) return null;
            return this.savedCards[this.selectedBank].find(card => card.id == this.selectedCardId);
        }
    }" x-init="init()">

        {{-- Header --}}
        @include('partials.header-payment', [
            'title' => 'E - Payment',
            'backUrl' => route('payment.cancel', $order),
        ])

        <div class="container mx-auto px-4 py-8 pb-32">
            <div class="flex justify-around items-start text-center mb-8">
                @foreach (['BRI', 'BCA', 'Mandiri', 'BSI'] as $bank)
                    <button @click="selectedBank = '{{ $bank }}'; selectedCardId = null"
                        class="w-20 group focus:outline-none">
                        <div class="relative w-full h-16 flex items-center justify-center p-1 rounded-xl transition-all duration-300 transform group-hover:scale-110"
                            :class="selectedBank === '{{ $bank }}' ? 'shadow-lg ring-2 ring-orange-500' :
                                'bg-white shadow-sm'">
                            <img src="{{ asset('images/bank_logos/' . strtolower($bank) . '.png') }}"
                                alt="{{ $bank }} Logo" class="max-h-8 object-contain">
                            <div x-show="selectedBank === '{{ $bank }}'" x-transition
                                class="absolute -top-2 -right-2 bg-orange-500 text-white rounded-full h-5 w-5 flex items-center justify-center border-2 border-white">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="text-sm font-semibold mt-2 text-slate-600">Bank {{ $bank }}</span>
                    </button>
                @endforeach
            </div>

            <div class="bg-white rounded-xl shadow p-6 min-h-[150px] flex flex-col justify-center">
                <template x-if="selectedBank">
                    <div class="relative">
                        <template x-if="savedCards[selectedBank] && savedCards[selectedBank].length > 0">
                            <div>
                                <button @click="showCardDropdown = !showCardDropdown"
                                    class="w-full p-4 border rounded-lg text-lg flex justify-between items-center hover:bg-slate-50 text-left">
                                    <div x-show="!getSelectedCard()">
                                        <span class="text-slate-500">Pilih Kartu Tersimpan</span>
                                    </div>
                                    <div x-show="getSelectedCard()" class="flex items-center space-x-3">
                                        <img :src="`{{ asset('images/bank_logos/') }}/${selectedBank.toLowerCase()}.png`"
                                            class="h-5">
                                        <span class="font-semibold"
                                            x-text="`•••• ${getSelectedCard()?.last_four_digits}`"></span>
                                    </div>
                                    <svg class="h-5 w-5 text-slate-400 transition-transform"
                                        :class="showCardDropdown ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="showCardDropdown" @click.outside="showCardDropdown = false" x-transition
                                    class="absolute top-full left-0 right-0 mt-1 bg-white border rounded-lg shadow-xl z-10">
                                    <template x-for="card in savedCards[selectedBank]" :key="card.id">
                                        <div @click="selectedCardId = card.id; showCardDropdown = false"
                                            class="p-4 hover:bg-orange-50 cursor-pointer flex items-center space-x-3">
                                            <img :src="`{{ asset('images/bank_logos/') }}/${selectedBank.toLowerCase()}.png`"
                                                class="h-5">
                                            <span
                                                x-text="`${card.card_holder_name} - •••• ${card.last_four_digits}`"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="!savedCards[selectedBank] || savedCards[selectedBank].length === 0">
                            <div class="text-center text-slate-500 py-4">
                                <img :src="`{{ asset('images/bank_logos/') }}/${selectedBank.toLowerCase()}.png`"
                                    class="h-8 mx-auto mb-2 opacity-50">
                                <p class="font-bold">No <span x-text="selectedBank.toUpperCase()"></span> Card added</p>
                                <p class="text-sm">You can add a <span x-text="selectedBank"></span> card and save it for
                                    later</p>
                            </div>
                        </template>

                        <button @click="showAddCardModal = true"
                            class="w-full mt-4 border-2 border-dashed rounded-lg py-3 font-bold text-orange-600 hover:bg-orange-50 transition-colors flex items-center justify-center space-x-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Tambahkan Baru</span>
                        </button>
                    </div>
                </template>

                <template x-if="!selectedBank">
                    <div class="flex items-center justify-center h-full">
                        <p class="text-slate-400 font-semibold">Pilih salah satu kartu</p>
                    </div>
                </template>
            </div>

            <div class="fixed bottom-0 left-0 right-0 bg-white p-4 border-t shadow-inner">
                <div class="container mx-auto">
                    <div class="flex justify-between items-center text-lg mb-4">
                        <span class="text-slate-600 font-bold">TOTAL:</span>
                        <span
                            class="text-2xl font-extrabold text-slate-800">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <form id="pay-form" action="{{ route('payment.pay_with_saved', $order) }}" method="POST">
                        @csrf
                        <input type="hidden" name="saved_card_id" x-model="selectedCardId">
                    </form>
                    <button type="submit" form="pay-form" :disabled="!selectedCardId"
                        class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-orange-600 transition-colors">
                        BAYAR & KONFIRMASI
                    </button>
                </div>
            </div>
        </div>

        @include('partials.add-card-modal')
    </div>

    <style>
        [x-cloak] {
            display: none;
        }
    </style>
@endsection
