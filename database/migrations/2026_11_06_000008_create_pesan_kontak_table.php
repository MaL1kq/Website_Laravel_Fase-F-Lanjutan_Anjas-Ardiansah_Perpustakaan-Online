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
        Schema::create('pesan_kontak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->string('email');
            $table->string('no_telepon')->nullable();
            $table->string('subjek');
            $table->text('pesan');
            $table->text('balasan_admin')->nullable();
            $table->enum('status', ['belum_dibaca', 'dibaca', 'dibalas'])->default('belum_dibaca');
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('dibalas_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_kontak');
    }
};
