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
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id_order'])->on('orders')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['po_supplier_id'])->references(['id_po_supplier'])->on('supplier_purchase_orders')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['product_id'])->references(['id_product'])->on('products')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'])->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->dropForeign('stock_logs_order_id_foreign');
            $table->dropForeign('stock_logs_po_supplier_id_foreign');
            $table->dropForeign('stock_logs_product_id_foreign');
            $table->dropForeign('stock_logs_user_id_foreign');
        });
    }
};
