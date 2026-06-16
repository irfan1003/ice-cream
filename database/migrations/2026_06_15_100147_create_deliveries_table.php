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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id_deliver');
            $table->unsignedBigInteger('order_id')->index('deliveries_order_id_foreign');
            $table->unsignedBigInteger('driver_id')->nullable()->index('deliveries_driver_id_foreign');
            $table->string('spb_number');
            $table->string('barcode_gudang')->nullable();
            $table->string('barcode_office')->nullable();
            $table->enum('delivery_status', ['ready', 'pending_admin_kantor', 'pending_admin_gudang', 'ditolak', 'shipped', 'delivered'])->default('pending_admin_kantor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
