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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id_po');
            $table->unsignedBigInteger('customer_id')->index('purchase_orders_customer_id_foreign');
            $table->unsignedBigInteger('sales_id')->index('purchase_orders_sales_id_foreign');
            $table->unsignedBigInteger('created_by')->nullable()->index('purchase_orders_created_by_foreign');
            $table->string('po_number')->nullable()->unique();
            $table->date('po_date');
            $table->decimal('subtotal', 10);
            $table->decimal('tax_amount', 10);
            $table->decimal('discount_total', 10);
            $table->decimal('grand_total', 10);
            $table->enum('status', ['pending_sales', 'pending_coordinator', 'pending_admin', 'pending_director', 'approved', 'revised', 'rejected']);
            $table->text('rejected_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
