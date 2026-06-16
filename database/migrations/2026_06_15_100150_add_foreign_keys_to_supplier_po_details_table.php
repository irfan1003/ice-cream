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
        Schema::table('supplier_po_details', function (Blueprint $table) {
            $table->foreign(['po_supplier_id'])->references(['id_po_supplier'])->on('supplier_purchase_orders')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id_product'])->on('products')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_po_details', function (Blueprint $table) {
            $table->dropForeign('supplier_po_details_po_supplier_id_foreign');
            $table->dropForeign('supplier_po_details_product_id_foreign');
        });
    }
};
