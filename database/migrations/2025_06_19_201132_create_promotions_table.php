<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ini akan membuat tabel 'promotions' saat Anda menjalankan `php artisan migrate`.
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_path');
            $table->boolean('is_active')->default(true);
            $table->string('type')->default('special_price'); // Sesuai controller
            $table->json('promo_data'); // Kolom untuk menyimpan JSON data promo
            $table->timestamps(); // Membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Ini akan menghapus tabel jika Anda menjalankan rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
