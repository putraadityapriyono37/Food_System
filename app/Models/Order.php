<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_name',
        'order_type',
        'table_id',
        'total_amount',
        'status',
        'payment_method',
    ];

    // Satu Order memiliki banyak OrderItem
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
