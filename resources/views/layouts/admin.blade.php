<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50">
    <div x-data="{
        sidebarOpen: false,
        deleteModalOpen: false,
        deleteFormAction: '',
        deleteMessage: 'Anda yakin ingin menghapus item ini?'
    }"
        @open-delete-modal.window="
            deleteModalOpen = true;
            deleteFormAction = $event.detail.action;
            if ($event.detail.message) {
                deleteMessage = $event.detail.message;
            }
         "
        class="relative min-h-screen md:flex">

        <div @click="sidebarOpen = false" x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black/30 md:hidden" x-cloak>
        </div>

        <div class="w-64 bg-white text-slate-700 flex flex-col fixed inset-y-0 left-0 z-30 transform md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-slate-200"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8">
                    <span class="text-xl font-bold">Admin</span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden p-1">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- ======================================================== --}}
            {{--               NAVIGASI DENGAN IKON BARU                --}}
            {{-- ======================================================== --}}
            <nav class="flex-grow p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-orange-100 text-orange-600' : 'hover:bg-slate-100' }}">
                    {{-- Ikon Baru: Dashboard (Grid) --}}
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg font-semibold transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-orange-100 text-orange-600' : 'hover:bg-slate-100' }}">
                    {{-- Ikon Baru: Manajemen Produk (Tumpukan Kotak) --}}
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-5.571 3m0 0l5.571 3-4.179 2.25-5.571-3 4.179-2.25z" />
                    </svg>
                    <span>Manajemen Produk</span>
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg font-semibold transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-orange-100 text-orange-600' : 'hover:bg-slate-100' }}">
                    {{-- Ikon Baru: Manajemen Pesanan (Clipboard) --}}
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Manajemen Pesanan</span>
                </a>
                {{-- ... setelah link Manajemen Pesanan ... --}}
                <a href="{{ route('admin.promotions.index') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg font-semibold transition-colors {{ request()->routeIs('admin.promotions.*') ? 'bg-orange-100 text-orange-600' : 'hover:bg-slate-100' }}">

                    {{-- Ikon Tag dari Heroicons --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-6 w-6 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>

                    <span>Manajemen Promo</span>
                </a>
                <a href="{{ route('admin.tables.index') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg font-semibold transition-colors {{ request()->routeIs('admin.tables.*') ? 'bg-orange-100 text-orange-600' : 'hover:bg-slate-100' }}">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <span>Manajemen Meja</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-200">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center px-4 py-2.5 rounded-lg font-semibold text-slate-600 hover:bg-slate-100 hover:text-red-600 transition-colors">
                        {{-- Ikon Baru: Logout (Panah Keluar) --}}
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex flex-col md:ml-64">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <button @click.stop="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-semibold text-slate-800">@yield('title', 'Dashboard')</h1>
                <div></div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50">
                @yield('content')
            </main>
        </div>
        <div x-show="deleteModalOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">

            <div @click.outside="deleteModalOpen = false" x-show="deleteModalOpen"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-md bg-white rounded-xl shadow-lg text-center p-6">

                {{-- Ikon Peringatan --}}
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>

                {{-- Judul dan Pesan --}}
                <h3 class="text-lg font-bold text-slate-800 mt-5">Konfirmasi Penghapusan</h3>
                <p class="mt-2 text-sm text-slate-500" x-text="deleteMessage"></p>

                {{-- Tombol Aksi --}}
                <div class="mt-6 flex justify-center space-x-4">
                    <button type="button" @click="deleteModalOpen = false"
                        class="px-4 py-2 bg-slate-200 text-slate-800 rounded-lg hover:bg-slate-300 font-semibold">
                        Batal
                    </button>
                    {{-- Form ini akan di-submit saat tombol "Hapus" ditekan --}}
                    <form :action="deleteFormAction" method="POST" x-ref="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</body>

</html>
