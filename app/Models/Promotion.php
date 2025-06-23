<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection; // <-- Tambahkan ini

class Promotion extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * ACCESSOR BARU: Mengambil SEMUA produk yang ada dalam paket promo.
     * Fungsi ini memungkinkan Anda memanggil $promo->products (jamak)
     * dan akan mengembalikan koleksi semua produk dalam paket tersebut.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductsAttribute(): Collection
    {
        // Decode kolom promo_data menjadi objek
        $promoData = json_decode($this->attributes['promo_data']);

        // Cek apakah 'product_ids' ada dan merupakan sebuah array
        if (isset($promoData->product_ids) && is_array($promoData->product_ids)) {
            // Ambil semua produk yang ID-nya ada di dalam array 'product_ids'
            return Product::whereIn('id', $promoData->product_ids)->get();
        }

        // Jika tidak ada, kembalikan koleksi kosong untuk mencegah error
        return new Collection();
    }
}
