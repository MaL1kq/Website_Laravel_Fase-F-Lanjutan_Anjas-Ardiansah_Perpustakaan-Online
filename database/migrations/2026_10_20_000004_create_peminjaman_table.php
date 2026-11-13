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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian');
            $table->date('tanggal_dikembalikan')->nullable();
            $table->enum('status_peminjaman', ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat'])->default('menunggu');
            $table->decimal('denda', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
