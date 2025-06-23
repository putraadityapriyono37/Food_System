<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi saat membuat data baru secara massal
    protected $fillable = [
        'name',
        'slug',
        'description',
        'time_estimation',
        'price',
        'rating',
        'image',
        'category',
        'is_best_seller',
        'is_available',
    ];
    protected $with = ['variants'];

    // app/Models/Product.php
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
