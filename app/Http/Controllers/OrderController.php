<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Menyimpan order baru dan mengarahkan ke alur pembayaran yang sesuai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|in:cashier,ewallet,epayment',
        ]);

        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $order = null;

        try {
            DB::beginTransaction();

            $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
            $total = $subtotal * 1.11;

            $order = Order::create([
                'customer_name'  => $request->customer_name,
                'payment_method' => $request->payment_method,
                'order_type'     => session('order_type', 'dine_in'),
                'table_id'       => session('table_id'),
                'order_code'     => 'POS-' . strtoupper(Str::random(6)),
                'total_amount'   => $total,
                'status'         => 'pending',
            ]);

            if ($order->order_type === 'dine_in' && $order->table_id) {
                Table::find($order->table_id)->update(['status' => 'occupied']);
            }

            foreach ($cartItems as $id => $item) {
                if (isset($item['is_bundle']) && $item['is_bundle']) {
                    // JIKA INI PAKET PROMO
                    OrderItem::create([
                        'order_id'       => $order->id,
                        'product_id'     => null,
                        'promotion_id'   => Str::after($id, 'bundle_'),
                        'quantity'       => $item['quantity'],
                        'price_per_item' => $item['price'],
                        'item_details'   => json_encode([
                            'name' => $item['name'],
                            'products' => $item['items'],
                        ]),
                    ]);
                } else {
                    // JIKA INI PRODUK BIASA
                    OrderItem::create([
                        'order_id'       => $order->id,
                        'product_id'     => $item['product_id'],
                        'quantity'       => $item['quantity'],
                        'price_per_item' => $item['price'],
                        'item_details'   => json_encode([
                            'name' => $item['name'],
                        ]),
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e); // Biarkan ini aktif untuk sementara
        }

        // Logika redirect pembayaran
        if ($order->payment_method == 'cashier') {
            session()->forget(['cart', 'customer_name']);
            return redirect()->route('order.show', $order);
        } elseif (in_array($order->payment_method, ['ewallet', 'epayment'])) {
            return redirect()->route('payment.' . $order->payment_method, $order);
        }

        return redirect()->route('home');
    }

    /**
     * Menampilkan halaman sukses setelah order dibuat atau dibayar.
     */
    public function show(Order $order)
    {
        // FIX: Menggunakan nama relasi 'items' sesuai dengan model Anda.
        $order->load('items');

        return view('order.show', compact('order'));
    }
}
