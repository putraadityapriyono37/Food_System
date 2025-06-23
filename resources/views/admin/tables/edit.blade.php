@extends('layouts.admin')
@section('title', 'Edit Meja')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Edit Meja: {{ $table->name }}</h1>
        <form action="{{ route('admin.tables.update', $table) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-slate-700 font-semibold mb-2">Nama/Nomor Meja</label>
                <input type="text" id="name" name="name"
                    class="w-full border-slate-300 rounded-lg p-3 focus:border-orange-500 focus:ring-orange-500"
                    value="{{ old('name', $table->name) }}" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-slate-700 font-semibold mb-2">Status</label>
                <select name="status" id="status"
                    class="w-full border-slate-300 rounded-lg p-3 bg-white focus:border-orange-500 focus:ring-orange-500"
                    required>
                    <option value="available" @if (old('status', $table->status) == 'available') selected @endif>Tersedia (Available)
                    </option>
                    <option value="occupied" @if (old('status', $table->status) == 'occupied') selected @endif>Terisi (Occupied)</option>
                </select>
            </div>
            <div class="flex items-center space-x-4 mt-6">
                <button type="submit"
                    class="bg-orange-500 text-white font-bold px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors">Simpan
                    Perubahan</button>
                <a href="{{ route('admin.tables.index') }}" class="text-slate-600 hover:text-slate-800">Batal</a>
            </div>
        </form>
    </div>
@endsection
