<div x-data="{ show: false }" x-intersect:enter="show = true" x-intersect:leave="show = false"
    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'" class="transition-all duration-700 ease-out">

    <div class="relative mt-16">
        {{-- Gambar Produk --}}
        <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-44 h-44 z-10">
            <a href="{{ route('product.show', $product->slug) }}" @click.prevent="checkAndFollowLink($event)">
                @if (!empty($product->image) && file_exists(public_path('storage/' . $product->image)))
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-contain filter drop-shadow-xl transition-transform hover:scale-110 duration-300">
                @else
                    <img src="{{ asset('images/icon-all.png') }}" alt="{{ $product->name }}"
                        class="w-full h-full object-contain filter drop-shadow-lg">
                @endif
            </a>
        </div>

        {{-- Kartu Konten --}}
        <div
            class="bg-white rounded-2xl shadow-lg pt-28 pb-8 px-6 text-center relative flex flex-col flex-grow min-h-[280px]">
            <a href="{{ route('product.show', $product->slug) }}" @click.prevent="checkAndFollowLink($event)"
                class="flex-grow">
                <h4 class="text-xl font-bold text-slate-800">{{ $product->name }}</h4>
                <p class="text-slate-500 mt-1 text-sm capitalize h-10">{{ Str::limit($product->description, 50) }}</p>

                {{-- ======================================================== --}}
                {{--   BAGIAN RATING & ESTIMASI WAKTU YANG SUDAH DIPERBAIKI   --}}
                {{-- ======================================================== --}}
                <div class="flex items-center justify-center space-x-4 my-4 text-sm text-gray-700">
                    @if ($product->rating)
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span
                                class="ml-1 font-semibold text-slate-600">{{ number_format($product->rating, 1, ',') }}</span>
                        </div>
                    @endif

                    @if ($product->rating && $product->time_estimation)
                        <div class="border-l h-5 border-slate-200"></div>
                    @endif

                    @if ($product->time_estimation)
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ml-1 font-semibold text-slate-600">{{ $product->time_estimation }} min</span>
                        </div>
                    @endif
                </div>
            </a>

            {{-- resources/views/partials/product-card.blade.php --}}

            <div class="mt-auto flex justify-between items-center">
                {{-- Harga --}}
                <div class="text-left">
                    @if ($product->variants->isNotEmpty())
                        <div class="text-xs text-slate-500 leading-tight">Mulai dari</div>
                        <div class="text-xl font-extrabold text-slate-900">
                            Rp{{ number_format($product->variants->min('price'), 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-xl font-extrabold text-slate-900">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                <button type="button" @click.stop="checkAndOpenModal({{ $product->toJson() }})"
                    class="bg-orange-500 text-white rounded-full h-12 w-12 flex items-center justify-center shadow-lg transform transition-transform hover:scale-110 hover:bg-orange-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
