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
        Schema::table('deliveries', function (Blueprint $table) {
            $table->foreign(['driver_id'])->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['order_id'])->references(['id_order'])->on('orders')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign('deliveries_driver_id_foreign');
            $table->dropForeign('deliveries_order_id_foreign');
        });
    }
};
