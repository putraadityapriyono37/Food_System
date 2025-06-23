<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_cards', function (Blueprint $table) {
            $table->id();
            // Kita gunakan nama customer sebagai cara simpel menghubungkan kartu
            $table->string('customer_name');
            $table->string('bank_name'); // e.g., 'BRI', 'BCA'
            $table->string('card_holder_name');
            $table->string('last_four_digits');
            $table->string('expiry_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_cards');
    }
};
