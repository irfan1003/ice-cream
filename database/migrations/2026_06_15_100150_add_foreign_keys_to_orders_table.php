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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['customer_id'])->references(['id_customer'])->on('customers')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['sales_id'])->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_created_by_foreign');
            $table->dropForeign('orders_customer_id_foreign');
            $table->dropForeign('orders_sales_id_foreign');
        });
    }
};
