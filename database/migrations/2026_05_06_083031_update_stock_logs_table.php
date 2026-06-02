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
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->enum('verification_status', ['pending', 'sesuai', 'tidak_sesuai'])
                ->default('pending')
                ->after('reference');

            $table->text('warehouse_note')
                ->nullable()
                ->after('verification_status');

            $table->enum('final_status', ['draft', 'completed'])
                ->default('draft')
                ->after('warehouse_note');

            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->text('description')->nullable();

            $table->dropColumn([
                'verification_status',
                'warehouse_note',
                'final_status'
            ]);
        });
    }
};
