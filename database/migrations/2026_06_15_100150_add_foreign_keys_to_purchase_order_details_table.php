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
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->foreign(['po_id'])->references(['id_po'])->on('purchase_orders')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_id'])->references(['id_product'])->on('products')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_details', function (Blueprint $table) {
            $table->dropForeign('purchase_order_details_po_id_foreign');
            $table->dropForeign('purchase_order_details_product_id_foreign');
        });
    }
};
