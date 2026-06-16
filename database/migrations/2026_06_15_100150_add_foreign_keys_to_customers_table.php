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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['zone_id'])->references(['id_zone'])->on('zones')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_user_id_foreign');
            $table->dropForeign('customers_zone_id_foreign');
        });
    }
};
