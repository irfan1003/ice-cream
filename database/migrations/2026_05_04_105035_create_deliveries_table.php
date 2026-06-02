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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('id_deliver');
            $table->foreignId('order_id')->constrained('orders', 'id_order');
            $table->foreignId('driver_id')->nullable()->constrained('users', 'id_user');
            $table->string('spb_number');
            // Trigger untuk logic TTD Digital di JavaScript (PDF-Lib)
            $table->boolean('acc_kantor')->default(false);
            $table->boolean('acc_gudang')->default(false);
            $table->enum('delivery_status', [
                'pending_admin_kantor',
                'pending_admin_gudang',
                'ditolak',
                'ready',
                'shipped',
                'delivered',
            ])
                ->default('pending_admin_kantor');
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
