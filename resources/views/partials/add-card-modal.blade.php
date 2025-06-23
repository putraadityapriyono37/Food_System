{{-- File ini sekarang tidak punya x-data sendiri. Semua merujuk ke data induk. --}}
<div x-show="showAddCardModal" @keydown.escape.window="showAddCardModal = false" @click.away="showAddCardModal = false"
    x-cloak class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-40">
    <div class="bg-white rounded-2xl w-full max-w-md" x-show="showAddCardModal" x-transition.opacity>
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-2xl font-bold">Add Card</h2>
            <button @click="showAddCardModal = false"
                class="p-1 rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

        </div>
        {{-- Form ini sekarang memanggil fungsi dari induknya --}}
        <form @submit.prevent="submitCardForm" x-ref="addCardForm" class="p-6">
            @csrf
            {{-- Kita tidak perlu input 'bank_name' di sini, karena sudah dihandle oleh JavaScript --}}

            <div x-show="formErrorMessage" x-transition
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
                <p x-text="formErrorMessage"></p>
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm text-slate-600">CARD HOLDER NAME</label>
                <input type="text" name="card_holder_name" x-model="cardForm.name"
                    class="w-full mt-1 p-3 bg-slate-100 rounded-lg border-slate-200 focus:border-orange-500 focus:ring-orange-500"
                    required>
            </div>

            <div class="mb-4">
                <label class="font-semibold text-sm text-slate-600">CARD NUMBER</label>
                <input type="text" name="card_number" x-model="cardForm.number" placeholder="xxxx-xxxx-xxxx-xxxx"
                    maxlength="16"
                    class="w-full mt-1 p-3 bg-slate-100 rounded-lg border-slate-200 focus:border-orange-500 focus:ring-orange-500"
                    required>
                <p x-show="cardForm.number && (cardForm.number.length !== 16 || !/^\d+$/.test(cardForm.number))"
                    class="text-red-500 text-xs mt-1" x-cloak>
                    Nomor kartu harus 16 digit angka.
                </p>
            </div>

            <div class="flex space-x-4 mb-8">
                <div class="w-1/2">
                    <label class="font-semibold text-sm text-slate-600">EXPIRE DATE</label>
                    <div class="flex space-x-2">
                        <select name="expiry_month" x-model="cardForm.expiry_month"
                            class="w-full mt-1 p-3 bg-slate-100 rounded-lg border-slate-200 focus:border-orange-500 focus:ring-orange-500"
                            required>
                            <option value="">MM</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                    {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                        <select name="expiry_year" x-model="cardForm.expiry_year"
                            class="w-full mt-1 p-3 bg-slate-100 rounded-lg border-slate-200 focus:border-orange-500 focus:ring-orange-500"
                            required>
                            <option value="">YYYY</option>
                            @php $currentYear = date('Y'); @endphp
                            @for ($y = 0; $y < 15; $y++)
                                <option value="{{ $currentYear + $y }}">{{ $currentYear + $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="w-1/2">
                    <label class="font-semibold text-sm text-slate-600">CVC</label>
                    <input type="text" name="cvc" x-model="cardForm.cvc" placeholder="***" maxlength="4"
                        class="w-full mt-1 p-3 bg-slate-100 rounded-lg border-slate-200 focus:border-orange-500 focus:ring-orange-500"
                        required>
                    <p x-show="cardForm.cvc && cardForm.cvc.length < 3" class="text-red-500 text-xs mt-1" x-cloak>CVC
                        minimal 3 digit.</p>
                </div>
            </div>

            <button type="submit" :disabled="isCardFormInvalid() || loading"
                class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-orange-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                <span x-show="!loading">SIMPAN KARTU</span>
                <span x-show="loading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </form>
    </div>
</div>
