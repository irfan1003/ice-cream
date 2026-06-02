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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id('id_po_detail');
            $table->foreignId('po_id')->constrained('purchase_orders', 'id_po');
            $table->foreignId('product_id')->constrained('products', 'id_product');
            $table->integer("qty");
            $table->integer("bonus_qty")->nullable();
            $table->decimal("discount")->nullable();
            $table->decimal("price_at_time");
            $table->decimal("total_item_price", 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purcahe_order_details');
    }
};
