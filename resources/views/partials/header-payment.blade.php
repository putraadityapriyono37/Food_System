{{--
    Header ini KHUSUS untuk halaman pembayaran (e-wallet, e-payment).
    Desainnya sudah disamakan dengan header utama.
--}}
<header class="bg-slate-50/80 backdrop-blur-lg sticky top-0 z-30">
    <div class="container mx-auto px-4">
        {{-- Menggunakan flexbox dengan 3 kolom berukuran sama (w-1/3) --}}
        <div class="flex items-center justify-between" style="height: 80px;">

            <div class="w-1/3">
                <a href="{{ $backUrl }}"
                    class="p-2 -ml-2 text-slate-600 hover:text-orange-500 transition-colors inline-block" title="Kembali">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            </div>

            <div class="w-1/3 text-center">
                <h1 class="text-lg font-bold text-slate-800">{{ $title }}</h1>
            </div>

            <div class="w-1/3"></div>

        </div>
    </div>
</header>
