<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_rating_to_products_table.php
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menambah kolom rating setelah kolom 'price'
            // DECIMAL(2, 1) cocok untuk format seperti 4.8, 4.7, 5.0
            // nullable() berarti boleh kosong, default(null) artinya nilai awalnya kosong
            $table->decimal('rating', 2, 1)->nullable()->default(null)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
