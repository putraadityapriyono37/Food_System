<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    /**
     * FIX: Tambahkan fungsi relasi ini.
     * Mendefinisikan bahwa satu Meja bisa memiliki banyak Pesanan (one-to-many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        // Fungsi ini memberitahu Laravel bahwa model 'Table'
        // terhubung ke model 'Order' melalui kolom 'table_id'.
        return $this->hasMany(Order::class);
    }
}
