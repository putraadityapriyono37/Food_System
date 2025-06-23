<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SavedCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini ada

class PaymentController extends Controller
{
    // Method ini menampilkan halaman simulasi E-Wallet
    public function ewallet(Order $order)
    {
        return view('payment.ewallet', ['order' => $order]);
    }

    // Method ini menampilkan halaman E-Payment
    public function epayment(Order $order)
    {
        $savedCards = SavedCard::where('customer_name', $order->customer_name)
            ->get()->groupBy('bank_name');
        return view('payment.epayment', compact('order', 'savedCards'));
    }

    // Memproses pembayaran dari E-Wallet
    public function process(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->status = 'paid';
            $order->save();
            session()->forget(['cart', 'customer_name']);
        });

        return redirect()->route('order.show', $order)->with('payment_success', 'Pembayaran E-Wallet Anda telah berhasil!');
    }

    // Method ini HANYA MENYIMPAN KARTU BARU dan mengembalikan jawaban JSON
    public function addCard(Request $request, Order $order)
    {
        $validated = $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number'      => 'required|string|min:16|max:16',
            'expiry_month'     => 'required|string|size:2',
            'expiry_year'      => 'required|string|size:4',
            'bank_name'        => 'required|string',
            'cvc'              => 'required|string|min:3|max:4'
        ]);

        // Membungkus proses simpan ke DB dengan transaction
        $newCard = DB::transaction(function () use ($validated, $order) {
            $expiryDate = $validated['expiry_month'] . '/' . substr($validated['expiry_year'], -2);

            return SavedCard::create([
                'customer_name'      => $order->customer_name,
                'bank_name'          => $validated['bank_name'],
                'card_holder_name'   => $validated['card_holder_name'],
                'last_four_digits'   => substr($validated['card_number'], -4),
                'expiry_date'        => $expiryDate,
            ]);
        });

        // Bangun URL tujuan untuk redirect di frontend
        $redirectUrl = route('payment.epayment', [
            'order'       => $order,
            'newCardId'   => $newCard->id,
            'newCardBank' => $newCard->bank_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kartu baru berhasil disimpan!',
            'redirectUrl' => $redirectUrl,
        ]);
    }

    // Memproses pembayaran akhir dengan KARTU TERSIMPAN
    public function processWithSavedCard(Request $request, Order $order)
    {
        $request->validate(['saved_card_id' => 'required|exists:saved_cards,id']);

        DB::transaction(function () use ($order) {
            $order->status = 'paid';
            $order->save();
            session()->forget(['cart', 'customer_name']);
        });

        return redirect()->route('order.show', $order)->with('payment_success', 'Pembayaran dengan kartu tersimpan berhasil!');
    }

    // Method ini menangani pembatalan pembayaran
    public function cancel(Order $order)
    {
        $customerName = $order->customer_name;
        if ($order->status == 'pending') {
            $order->delete();
        }
        session(['customer_name' => $customerName]);
        return redirect()->route('cart.index')->with('info', 'Pembayaran dibatalkan, keranjang Anda telah dikembalikan.');
    }
}
