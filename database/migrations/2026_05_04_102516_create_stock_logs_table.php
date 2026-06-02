<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('product_id')->constrained('products', 'id_product');
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->string('reference');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
