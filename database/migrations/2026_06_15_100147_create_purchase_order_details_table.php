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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->bigIncrements('id_po_detail');
            $table->unsignedBigInteger('po_id')->index('purchase_order_details_po_id_foreign');
            $table->unsignedBigInteger('product_id')->index('purchase_order_details_product_id_foreign');
            $table->integer('qty');
            $table->integer('bonus_qty');
            $table->decimal('discount')->nullable();
            $table->decimal('price_at_time');
            $table->decimal('total_item_price', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
