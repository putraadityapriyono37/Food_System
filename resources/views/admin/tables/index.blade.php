@extends('layouts.admin')
@section('title', 'Manajemen Meja')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Meja</h1>
            <a href="{{ route('admin.tables.create') }}"
                class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all">
                <svg class="h-5 w-5 -ml-1 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Meja</span>
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-100 border-b-2 border-slate-200">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold text-slate-600">Nama/Nomor Meja</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Status</th>
                        <th class="p-3 text-center text-sm font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables as $table)
                        <tr class="border-b border-slate-100 hover:bg-orange-50 transition-colors">
                            <td class="p-3 font-semibold text-slate-700">{{ $table->name }}</td>
                            <td class="p-3 text-center">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full capitalize {{ $table->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ str_replace('_', ' ', $table->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.tables.edit', $table) }}"
                                    class="text-orange-600 hover:underline font-semibold">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-500">Belum ada data meja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $tables->links() }}</div>
    </div>
@endsection
