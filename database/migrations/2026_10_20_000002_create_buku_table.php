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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('penulis');
            $table->string('penerbit');
            $table->integer('tahun_terbit');
            $table->string('isbn')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_buku')->onDelete('set null');
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->string('cover')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
