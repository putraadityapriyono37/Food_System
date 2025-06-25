<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::orderBy('name')->get();
        return view('admin.tables.index', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:tables,name',
        ]);

        Table::create($validated);

        return redirect()->route('admin.tables.index')->with('success', 'Meja baru berhasil ditambahkan.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:tables,name,' . $table->id,
            'status' => 'required|in:available,occupied,unavailable',
        ]);

        $table->update($validated);

        return redirect()->route('admin.tables.index')->with('success', 'Data meja berhasil diperbarui.');
    }

    /**
     * FIX: Tambahkan pengecekan sebelum menghapus.
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        // Cek apakah meja ini memiliki relasi dengan pesanan apa pun.
        if ($table->orders()->exists()) {
            // Jika ya, kembalikan dengan pesan error.
            return back()->with('error', 'Tidak dapat menghapus meja karena sudah memiliki riwayat pesanan.');
        }

        // Jika tidak ada pesanan terkait, lanjutkan penghapusan.
        $table->delete();

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus.');
    }
}
