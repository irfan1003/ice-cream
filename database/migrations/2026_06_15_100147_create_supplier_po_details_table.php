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
        Schema::create('supplier_po_details', function (Blueprint $table) {
            $table->bigIncrements('id_supplier_po_detail');
            $table->unsignedBigInteger('po_supplier_id')->index('supplier_po_details_po_supplier_id_foreign');
            $table->unsignedBigInteger('product_id')->index('supplier_po_details_product_id_foreign');
            $table->integer('qty');
            $table->boolean('is_compatible')->default(true);
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_po_details');
    }
};
