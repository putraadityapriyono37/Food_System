<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'bank_name',
        'card_holder_name',
        'last_four_digits',
        'expiry_date',
    ];
}
