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
        // Tabel Keranjang Belanja (Cart)
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->timestamps();
        });

        // Tabel Pesanan / Pembelian Buku
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status', [
                'menunggu_pembayaran',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_pembayaran');
            $table->string('metode_pembayaran');
            $table->string('nama_penerima');
            $table->string('no_telepon');
            $table->text('alamat_pengiriman');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Tabel Item / Detail Pesanan
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_detail');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('keranjang');
    }
};
