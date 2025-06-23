<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Menampilkan daftar semua meja.
     */
    public function index()
    {
        $tables = Table::latest()->paginate(10);
        return view('admin.tables.index', compact('tables'));
    }

    /**
     * Menampilkan form untuk membuat meja baru.
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Menyimpan meja baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tables,name',
            'status' => 'required|in:available,occupied',
        ]);

        Table::create($validated);

        return redirect()->route('admin.tables.index')->with('success', 'Meja baru berhasil ditambahkan!');
    }


    /**
     * Menampilkan form untuk mengedit meja.
     */
    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    /**
     * Mengupdate data meja di database.
     */
    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tables,name,' . $table->id,
            'status' => 'required|in:available,occupied',
        ]);

        $table->update($validated);

        return redirect()->route('admin.tables.index')->with('success', 'Data meja berhasil diperbarui!');
    }

    /**
     * Menghapus meja dari database.
     */
    public function destroy(Table $table)
    {
        // Tambahkan logika untuk memeriksa jika meja sedang digunakan oleh order (opsional)
        if ($table->orders()->whereNotIn('status', ['completed', 'cancelled'])->exists()) {
            return back()->with('error', 'Meja tidak bisa dihapus karena sedang digunakan.');
        }

        $table->delete();

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus!');
    }
}
