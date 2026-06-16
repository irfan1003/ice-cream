<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->unsignedBigInteger('product_id')->index('stock_logs_product_id_foreign');
            $table->unsignedBigInteger('user_id')->index('stock_logs_user_id_foreign');
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->unsignedBigInteger('po_supplier_id')->nullable()->index('stock_logs_po_supplier_id_foreign');
            $table->unsignedBigInteger('order_id')->nullable()->index('stock_logs_order_id_foreign');
            $table->string('reference_note');
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
