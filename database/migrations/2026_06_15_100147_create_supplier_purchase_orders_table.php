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
        Schema::create('supplier_purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id_po_supplier');
            $table->unsignedBigInteger('supplier_id')->index('supplier_po_supplier_id_foreign');
            $table->unsignedBigInteger('created_by')->index('supplier_po_created_by_foreign');
            $table->string('po_number')->nullable()->unique();
            $table->date('po_date');
            $table->enum('status', [
                'pending_director_po', // Menunggu persetujuan awal Direktur (Baru diinput)
                'revised',             // Dikembalikan oleh Direktur ke Admin untuk diedit/direvisi (Missing status!)
                'ordered',             // Disetujui Direktur, sudah diexport & dikirim ke Supplier (Menunggu barang datang)
                'pending_director_rec',// Barang sudah dicek gudang, menunggu verifikasi kedatangan oleh Direktur
                'pending_office',      // Sudah diverifikasi Direktur, menunggu Admin Kantor untuk klik selesai
                'received',            // Selesai, stok resmi bertambah
                'rejected'             // Ditolak total / Batal
            ])->default('pending_director_po');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_purchase_orders');
    }
};
