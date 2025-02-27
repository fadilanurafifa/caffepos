<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->constrained('users')->onDelete('cascade');
            $table->bigInteger('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('sub_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('keranjang');
    }
};

