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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->foreignId('customer_id')->constrained('customers', 'id_customer');
            $table->foreignId('sales_id')->constrained('users', 'id_user');
            $table->foreignId('created_by')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('order_number')->nullable()->unique();
            $table->date('order_date');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('discount_total', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2);
            $table->enum('status', ['pending_sales', 'pending_coordinator', 'pending_director', 'revised', 'approved', 'rejected', 'panding_admin', 'completed', 'paid']);
            $table->text('rejected_note')->nullable();
            $table->string('invoice_pdf')->nullable();
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
