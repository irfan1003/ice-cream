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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('id_po');
            $table->foreignId('customer_id')->constrained('customers', 'id_customer');
            $table->foreignId('sales_id')->constrained('users', 'id_user');
            $table->foreignId('created_by')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('po_number')->nullable()->unique();
            $table->date('po_date');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('discount_total', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->enum('status', ['pending_sales', 'pending_coordinator', 'pending_admin', 'pending_director', 'approved', 'revised', 'stock_arrived', 'converted', 'rejected']);
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
