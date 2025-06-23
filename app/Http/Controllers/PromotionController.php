<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function show(Promotion $promotion)
    {
        // Pastikan promo aktif
        if (!$promotion->is_active) {
            abort(404);
        }

        // Kirim data ke view baru
        return view('promo-detail', compact('promotion'));
    }
}
