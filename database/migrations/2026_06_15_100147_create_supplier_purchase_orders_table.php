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
        Schema::create('supplier_purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id_po_supplier');
            $table->unsignedBigInteger('supplier_id')->index('supplier_po_supplier_id_foreign');
            $table->unsignedBigInteger('created_by')->index('supplier_po_created_by_foreign');
            $table->string('po_number')->nullable()->unique();
            $table->date('po_date');
            $table->enum('status', ['pending', 'received', 'pending_director', 'pending_office', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_purchase_orders');
    }
};
