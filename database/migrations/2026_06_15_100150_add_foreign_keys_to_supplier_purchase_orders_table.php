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
        Schema::table('supplier_purchase_orders', function (Blueprint $table) {
            $table->foreign(['created_by'], 'supplier_po_created_by_foreign')->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['supplier_id'], 'supplier_po_supplier_id_foreign')->references(['id_supplier'])->on('suppliers')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_purchase_orders', function (Blueprint $table) {
            $table->dropForeign('supplier_po_created_by_foreign');
            $table->dropForeign('supplier_po_supplier_id_foreign');
        });
    }
};
