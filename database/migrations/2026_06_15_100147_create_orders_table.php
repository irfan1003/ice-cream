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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id_order');
            $table->unsignedBigInteger('customer_id')->index('orders_customer_id_foreign');
            $table->unsignedBigInteger('sales_id')->index('orders_sales_id_foreign');
            $table->unsignedBigInteger('created_by')->nullable()->index('orders_created_by_foreign');
            $table->string('order_number')->nullable()->unique();
            $table->date('order_date');
            $table->decimal('subtotal', 10);
            $table->decimal('tax_amount', 10);
            $table->decimal('discount_total', 10)->nullable();
            $table->decimal('grand_total', 10);
            $table->enum('status', ['pending_sales', 'pending_coordinator', 'pending_director', 'revised', 'approved', 'rejected', 'pending_admin', 'completed', 'paid']);
            $table->text('rejected_note')->nullable();
            $table->string('invoice_pdf')->nullable();
            $table->string('barcode_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
