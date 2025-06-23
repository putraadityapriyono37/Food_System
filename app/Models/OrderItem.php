<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_per_item',
        'promotion_id', // <-- TAMBAHKAN INI
        'item_details', // <-- TAMBAHKAN INI
    ];

    /**
     * Mendefinisikan bahwa setiap OrderItem dimiliki oleh satu Order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mendefinisikan bahwa setiap OrderItem terhubung dengan satu Product.
     * Ini akan null jika itemnya adalah paket promo.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * (Opsional) Relasi ke model Promotion.
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
