<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Logika untuk mencari berdasarkan kode pesanan atau nama customer
        if ($request->filled('search')) {
            $query->where('order_code', 'like', '%' . $request->search . '%')
                ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        // Logika untuk memfilter berdasarkan status
        if ($request->filled('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Ambil data dengan urutan terbaru, sertakan jumlah item, dan paginasi
        // withCount('items') sangat efisien untuk menghitung relasi
        $orders = $query->withCount('items')->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail dari satu pesanan.
     */
    public function show(Order $order)
    {
        // Eager load relasi 'items' dan 'items.product' untuk efisiensi query
        // Ini memastikan kita bisa mengakses detail produk dari setiap item tanpa query tambahan
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mengupdate status dari sebuah pesanan.
     */
    // app/Http/Controllers/Admin/OrderController.php

    public function updateStatus(Request $request, Order $order)
    {
        // Sesuaikan aturan 'in' dengan semua status yang ada di database Anda
        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,completed,cancelled',
        ]);

        $order->update($validated);

        // === LOGIKA PENTING: "LEPAS" MEJA JIKA ORDER SELESAI/BATAL ===
        if ($order->table_id && ($order->status === 'completed' || $order->status === 'cancelled')) {
            $table = Table::find($order->table_id);
            if ($table) {
                $table->update(['status' => 'available']);
            }
        }
        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
